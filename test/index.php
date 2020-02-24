<?php

// TODO: implement API: https://codex.wordpress.org/WordPress.org_API

require __DIR__ . '/../vendor/autoload.php';

use Motto\WpScan;

$url = "http://wolfemontcalm.com";
// $url = "http://motto.ca";

$scan = new WpScan($url, [
    'server' => false,
    'endpoints' => false,
    'version' => false,
    'ssl' => false,
    'plugins' => true,
]);

header('Content-Type: application/json');
echo $scan->run()->json();