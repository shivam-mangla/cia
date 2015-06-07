<?php

$xml = file_get_contents('post_xml.xml');
echo $xml;
//var_dump($xml);
$url = 'https://auth.uidai.gov.in/1.6/public/9/9/MLTbKYcsgYMq1zgL3WMZYrnyvsarlljxpom2A-QTPc0Zud23shpnqPk';


$post_data = array(
    "xml" => $xml,
);

$stream_options = array(
    'http' => array(
       'method'  => 'POST',
       'header'  => "Content-type: application/xml",
       'input' => $xml,
    ),
);

$context  = stream_context_create($stream_options);
$response = file_get_contents($url, null, $context);
echo $response;
?>


