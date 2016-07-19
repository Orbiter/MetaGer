#!/usr/bin/perl

use Lingua::Identify qw(:language_identification);

$text = <STDIN>;

$a = langof($text);

print $a;

