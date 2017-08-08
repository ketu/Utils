<?php
/**
 * @author [ketu.lai]
 * @email [ketu.lai@gmail.com]
 * @create date 2017-03-29 04:59:13
 * @modify date 2017-03-29 04:59:13
 * @desc [description]
 */

namespace Veda\Utils\Converter;

use DOMElement;
use DOMDocument;

class XmlToArray
{

    private $document;

    private function __construct(string $xml)
    {
        $this->document = new \DOMDocument();
        $this->document->loadXML($xml);
    }


    private function getRootElement()
    {
        return $this->document->documentElement;
    }

    public static function convert(string $xml)
    {
        $reader = new static($xml);
        return $reader->convertElement($reader->getRootElement());


    }

    private function convertElement($element)
    {
        $arrayData = [];

        switch ($element->nodeType) {

            case XML_ELEMENT_NODE:
                for ( $i = 0, $m = $element->childNodes->length;$i < $m;$i ++ ) {
                    echo $i;
                    $child = $element->childNodes->item($i);
                    echo $child->tagName;
                }

                    $arrayData[$element->tagName] = $element->nodeValue;

                break;
            case XML_TEXT_NODE:

                $arrayData[$element->tagName] = $element->nodeValue;


                break;

            default:
                $arrayData['dd'] = 'dfs';
                break;
        }


        return $arrayData;
    }

    public static function fromFile($filename)
    {

    }
}