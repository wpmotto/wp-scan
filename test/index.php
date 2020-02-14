<?php

require __DIR__ . '/../vendor/autoload.php';

use Motto\WpScan;

$url = "https://preprod.motto.ca";
$scan = new WpScan($url);

$scan->checks([
    'version' => true,
    'endpoints' => true,
])->run();

header('Content-Type: application/json');
echo $scan->json();