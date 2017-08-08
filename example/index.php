<?php
/**
 * User: ketu.lai <ketu.lai@gmail.com>
 * Date: 17-8-8
 */


require "../vendor/autoload.php";

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<ListMatchingProductsResponse xmlns=\"http://mws.amazonservices.com/schema/Products/2011-10-01\">
   <ListMatchingProductsResult>
        <Products>
            <Product>
                <Name>Google</Name>
            </Product>
        </Products>
    </ListMatchingProductsResult>
</ListMatchingProductsResponse>
";

$arr = \Veda\Utils\Converter\XmlToArray::convert($xml);
var_dump($arr);