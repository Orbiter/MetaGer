<?php

namespace app\Models\parserSkripte;
use App\Models\Searchengine;
use Log;

class Exalead extends Searchengine 
{
	public $results = [];

	function __construct (\SimpleXMLElement $engine, \App\MetaGer $metager)
	{
		parent::__construct($engine, $metager);
	}

	public function loadResults ($result)
	{
		$result = preg_replace("/\r\n/si", "", $result);
		try {
			$content = simplexml_load_string($result);
		} catch (\Exception $e) {
			abort(500, "$result is not a valid xml string");
		}
		
		if(!$content)
		{
			return;
		}
		$results = $content;
		$prefix = ""; $namespace = "";
		foreach($results->getDocNamespaces() as $strPrefix => $strNamespace) {
    			if(strlen($strPrefix)==0) {
			        $prefix="a"; //Assign an arbitrary namespace prefix.
			}else {
				$prefix="a";
			}
			$namespace = $strNamespace;
		}
		$results->registerXPathNamespace($prefix,$namespace);
		$results = $results->xpath("//a:hits/a:Hit");
		foreach($results as $result)
		{
			$result->registerXPathNamespace($prefix,$namespace);
			$title = $result->xpath("a:metas/a:Meta[@name='title']/a:MetaString[@name='value']")[0]->__toString();
			$link = $result->xpath("a:metas/a:Meta[@name='url']/a:MetaString[@name='value']")[0]->__toString();
			$anzeigeLink = $link;
			if(sizeOf($result->xpath("a:metas/a:Meta[@name='metadesc']/a:MetaString[@name='value']")) === 0)
				$descr = "";
			else
				$descr = $result->xpath("a:metas/a:Meta[@name='metadesc']/a:MetaString[@name='value']")[0]->__toString();
			$this->counter++;
			$this->results[] = new \App\Models\Result(
				$this->engine,
				$title,
				$link,
				$anzeigeLink,
				$descr,
				$this->gefVon,
				$this->counter
			);
		}
	}
}
