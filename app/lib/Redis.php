<?php
namespace App\lib;
/**
 * Very simple Redis implementation, all commands passed in cli format
 * Add commands via cmd ( $command [, $variable1 [, $variable2 ] ] ) method
 * Fire commands via get () o set () methods ( first one will return output, usefull for get operations )
 *
 * Usage:
 * $redis = new redis_cli ( '127.0.0.1', 6379 );
 * $redis -> cmd ( 'SET', 'foo', 'bar' ) -> set ();
 * $foo = $redis -> cmd ( 'GET', 'foo' ) -> get ();
 *
 * $redis -> cmd ( 'HSET', 'hash', 'foo', 'bar' ) -> cmd ( 'HSET', 'hash', 'abc', 'def' ) -> set ();
 * $vals = $redis -> cmd ( 'HVALS', 'hash' ) -> get ();
 *
 * $redis -> cmd ( 'KEYS', 'online*' );
 * $total_online = $redis -> get_len ();
 *
 * Based on http://redis.io/topics/protocol
 */
class Redis
{
    const INTEGER = ':';
    const INLINE = '+';
    const BULK = '$';
    const MULTIBULK = '*';
    const ERROR = '-';
    const NL = "\r\n";

    private $handle = false;
    private $host;
    private $port;
    private $silent_fail;

    private $commands = array ();

    //Timeout for stream, 30 seconds
    private $timeout = 30;

    //Timeout for socket connection
    private $connect_timeout = 3;

    //Use this with extreme caution
    private $force_reconnect = false;

    //Error handling, debug info
    private $last_used_command = '';

    //Error handling function, use set_error_function method ()
    private $error_function = null;

    public function __construct ( $host = false, $port = false, $silent_fail = false, $timeout = 60 )
    {
        if ( $host && $port )
        {
            $this -> connect ( $host, $port, $silent_fail, $timeout );
        }
    }

    //Main method to establish connection
    public function connect ( $host = '127.0.0.1', $port = 6379, $silent_fail = false, $timeout = 60 )
    {
        $this -> host = $host;
        $this -> port = $port;
        $this -> silent_fail = $silent_fail;
        $this -> timeout = $timeout;

        if ( $silent_fail )
        {
            $this -> handle = @fsockopen ( $host, $port, $errno, $errstr, $this -> connect_timeout );

            if ( !$this ->  handle )
            {
                $this -> handle = false;
            }
        }
        else
        {
            $this -> handle = fsockopen ( $host, $port, $errno, $errstr, $this -> connect_timeout );
        }

        if ( is_resource ( $this -> handle ) )
        {
            stream_set_timeout ( $this -> handle, $this -> timeout );
        }
    }

    public function reconnect (  )
    {
        $this -> __destruct ();
        $this -> connect ( $this -> host, $this -> port, $this -> silent_fail );
    }

    public function __destruct ()
    {
        if ( is_resource ( $this -> handle ) )
        {
            fclose ( $this -> handle );
        }
    }

    //Returns all commands array
    public function commands ()
    {
        return $this -> commands;
    }

    //Used to push single command to queue
    public function cmd ()
    {
        if ( !$this -> handle )
        {
            return $this;
        }

        $args = func_get_args ();
        $rlen = count ( $args );

        $output = '*'. $rlen . self::NL;

        foreach ( $args as $arg )
        {
            $output .= '$'. strlen ( $arg ) . self::NL . $arg . self::NL;
        }

        $this -> commands [] = $output;

        return $this;
    }

    //Used to push many commands at once, almost always for setting something
    public function set ()
    {
        if ( !$this -> handle )
        {
            return false;
        }

        //Total size of commands
        $size = $this -> exec ();
        $response = array ();

        for ( $i=0; $i<$size; $i++ )
        {
            $response [] = $this -> get_response ();
        }

        if ( $this -> force_reconnect )
        {
            $this -> reconnect ();
        }

        return $response;
    }

    //Used to get command response
    public function get ( $line = false )
    {
        if ( !$this -> handle )
        {
            return false;
        }

        $return = false;

        if ( $this -> exec () )
        {
            $return = $this -> get_response ();

            if ( $this -> force_reconnect )
            {
                $this -> reconnect ();
            }

        }

        return $return;
    }

    //Used to get length of the returned array. Most useful with `Keys` command
    public function get_len ()
    {
        if ( !$this -> handle )
        {
            return false;
        }

        $return = null;

        if ( $this -> exec () )
        {
            $char = fgetc ( $this -> handle );

            if ( $char == self::BULK )
            {
                $return = sizeof ( $this -> bulk_response () );
            }
            elseif ( $char == self::MULTIBULK )
            {
                $return = sizeof ( $this -> multibulk_response () );
            }

            if ( $this -> force_reconnect )
            {
                $this -> reconnect ();
            }
        }

        return $return;
    }

    //Forces to reconnect after every get() or set(). Use this with extreme caution
    public function set_force_reconnect ( $flag )
    {
        $this -> force_reconnect = $flag;
        return $this;
    }

    //Used to parse single command single response
    private function get_response ()
    {
        $return = false;

        $char = fgetc ( $this -> handle );

        switch ( $char )
        {
            case self::INLINE:
                $return = $this -> inline_response ();
                break;
            case self::INTEGER:
                $return = $this -> integer_response ();
                break;
            case self::BULK:
                $return = $this -> bulk_response ();
                break;
            case self::MULTIBULK:
                $return = $this -> multibulk_response ();
                break;
            case self::ERROR:
                $return = $this -> error_response ();
                break;
        }

        return $return;
    }

    //For inline responses only
    private function inline_response ()
    {
        return trim ( fgets ( $this -> handle ) );
    }

    //For integer responses only
    private function integer_response ()
    {
        return ( int ) trim ( fgets ( $this -> handle ) );
    }

    //For error responses only
    private function error_response ()
    {
        $error = fgets ( $this -> handle );

        if ( $this -> error_function )
        {
            call_user_func ( $this -> error_function, $error .'('. $this -> last_used_command .')' );
        }

        return false;
    }

    //For bulk responses only
    private function bulk_response ()
    {
        $return = trim ( fgets ( $this -> handle ) );

        if ( $return === '-1' )
        {
            $return = null;
        }
        else
        {
            $return = $this -> read_bulk_response ( $return );
        }

        return $return;
    }

    //For multibulk responses only
    private function multibulk_response ()
    {
        $size = trim ( fgets ( $this -> handle ) );
        $return = false;

        if ( $size === '-1' )
        {
            $return = null;
        }
        else
        {
            $return = array ();

            for ( $i = 0; $i < $size; $i++ )
            {
                $tmp = trim ( fgets ( $this -> handle ) );

                if ( $tmp === '-1' )
                {
                    $return [] = null;
                }
                else
                {
                    $return [] = $this -> read_bulk_response ( $tmp );
                }
            }
        }

        return $return;
    }

    //Sends command to the redis
    private function exec ()
    {
        $size = sizeof ( $this -> commands );

        if ( $size < 1 )
        {
            return null;
        }

        if ( $this -> error_function )
        {
            $this -> last_used_command = str_replace ( self::NL, '\\r\\n', implode ( ';', $this -> commands ) );
        }

        $command = implode ( self::NL, $this -> commands ) . self::NL;
        fwrite ( $this -> handle, $command );

        $this -> commands = array ();
        return $size;
    }

    //Bulk response reader
    private function read_bulk_response ( $tmp )
    {
        $response = null;

        $read = 0;
        $size = ( ( strlen ( $tmp ) > 1 && substr ( $tmp, 0, 1 ) === self::BULK ) ? substr ( $tmp, 1 ) : $tmp );

        while ( $read < $size )
        {
            $diff = $size - $read;

            $block_size = $diff > 8192 ? 8192 : $diff;

            $response .= fread ( $this -> handle, $block_size );
            $read += $block_size;
        }

        fgets ( $this -> handle );

        return $response;
    }

    public function set_error_function ( $func )
    {
        $this -> error_function = $func;
    }
}
