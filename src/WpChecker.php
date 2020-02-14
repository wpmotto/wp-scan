<?php

namespace Motto;

use GuzzleHttp\Client;
use DOMDocument;
use DOMXpath;

class WpChecker {

    const VALID_CHECKS = [
        'endpoints',
        'version',
        'plugins',
    ];

    protected $checks = [];
    protected $results = [];
    protected $url;
    protected $client;
    protected $dom;
    protected $xpath;

    public function __construct( array $checks, string $url )
    {
        $this->checks = $this->sanitize($checks);
        $this->url = $url;
        $this->client = new Client(['base_uri' => $this->url]);
        $this->setupDom();
    }

    public function __get( $prop )
    {
        if( method_exists( $this, $prop ) )
            return $this->{$prop}();
    }

    private function sanitize( array $checks )
    {
        return array_intersect(
            array_keys(array_filter($checks)), 
            self::VALID_CHECKS
        );
    }

    private function setupDom()
    {
        $response = $this->client->get($this->url);
        $html = $response->getBody()->getContents();
        $this->dom = new DOMDocument;
        @$this->dom->loadHTML($html);
        $this->xpath = new DOMXpath($this->dom);
    }

    public function check()
    {
        foreach( $this->checks as $check ) {
            $this->results[$check] = $this->{$check};
        }
        return $this;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function endpoints()
    {
        $endpoints = [
            '/wp-admin' => false,
            '/wp-login.php' => false,
        ];
        foreach( $endpoints as $uri => $found ) {
            $endpoints[$uri] = (
                $this->client->get($uri)->getStatusCode() == 200
            );
        }

        return [
            'name' => 'endpoints',
            'endpoints' => $endpoints,
            'found' => count(array_filter($endpoints)),
        ];
    }

    public function version()
    {
        $meta = $this->xpath->query("//meta[contains(@name,'generator')]");
        $version = false;
        if( $meta->length > 0 )
            $generator = $meta[0]->getAttribute('content');

        if( strpos(strtolower($generator), 'wordpress') !== false )
            $version = $generator;

        return [
            'name' => 'version',
            'generator' => $generator,
            'version' => $version,
        ];
    }    
}