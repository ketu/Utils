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

    private function __construct($xml, $version, $encoding)
    {
        $this->document = new DOMDocument($version, $encoding);
        $this->document->loadXML($xml);
    }

    private function getRootElement()
    {
        return $this->document->documentElement;
    }

    public static function convert($xml, $version = "1.0", $encoding="UTF-8")
    {
        $dom = new static($xml, $version, $encoding);
        return [$dom->getRootElement()->tagName=> $dom->convertElement($dom->getRootElement())];
    }


    /**
     * see @http://www.lalit.org/wordpress/wp-content/uploads/2011/07/XML2Array.html?ver=0.2
     * @param mixed $node
     */
    private function convertElement($node)
    {
        $output = [];
        switch ($node->nodeType) {
            case XML_TEXT_NODE:
                $output = trim($node->nodeValue);
                break;
            case XML_ELEMENT_NODE:
                if ($node->hasChildNodes()) {
                    foreach ($node->childNodes as $childNode) {
                        $v = $this->convertElement($childNode);
                        if (isset($childNode->tagName)) {
                            $tagName = $childNode->tagName;
                            if (stripos($tagName, ":") !== false) {
                                list($ns, $tagName) = \explode(":", $tagName);
                            }
                            if (!isset($output[$tagName])) {
                                $output[$tagName] = [];
                            }
                            $output[$tagName][] = $v;
                        } else {
                            if ($v !== "") {
                                $output = $v;
                            }
                        }
                    }
                    if (is_array($output)) {
                        // if only one node of its kind, assign it directly instead if array($value);
                        foreach ($output as $t => $v) {
                            if (is_array($v) && count($v) == 1) {
                                $output[$t] = $v[0];
                            }
                        }
                        if (empty($output)) {
                            $output = '';
                        }
                    }
                } else {
                    $output =  trim($node->nodeValue);
                }
                break;
        }
        return $output;
    }

    public static function fromFile($filename)
    {
        if (file_exists($filename)) {
            $xml = file_get_contents($filename);
            return self::convert($xml);
        }
    }
}