<?php

require __DIR__ . '/../vendor/autoload.php';

use Motto\WpScan;

$url = "http://wolfemontcalm.com";
// $url = "http://motto.ca";
$scan = new WpScan($url, [
    'server' => false,
    'endpoints' => false,
    'version' => false,
    'ssl' => false,
    'plugins' => false,
]);
$scan->run();

header('Content-Type: text/plain');
print_r($scan);

// header('Content-Type: application/json');
// echo $scan->json();