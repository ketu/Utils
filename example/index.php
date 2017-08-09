<?php
/**
 * Date: 2017/8/9 9:14
 */


require "../vendor/autoload.php";

$xml = \Veda\Utils\Converter\XmlToArray::fromFile('xml.xml');

var_dump($xml);