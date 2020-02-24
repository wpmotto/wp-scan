<?php

require __DIR__ . '/../vendor/autoload.php';

use Motto\WpScan;

$url = "http://wolfemontcalm.com";
// $url = "http://motto.ca";
$scan = new WpScan($url, [
    'server' => true,
    'endpoints' => true,
    'version' => true,
    'ssl' => true,
    'plugins' => false,
]);

header('Content-Type: application/json');
echo $scan->run()->json();