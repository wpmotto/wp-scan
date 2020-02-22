<?php

namespace Motto;

use GuzzleHttp\Client;
use Spatie\SslCertificate\SslCertificate;
use Motto\WpResultCollection;
use Motto\Checks\WpConnectionCheck;
use DOMDocument;
use DOMXpath;

class WpChecker {

    protected $checks = [];
    protected $results = [];
    protected $client;
    protected $header;
    protected $dom;
    protected $xpath;
    protected $url;
    protected $host;
    protected $scheme;

    public function __construct( array $checks, string $url )
    {
        $this->dom = new DOMDocument;
        $this->results = new WpResultCollection;
        $this->setUrl( $url );
        $this->setClient();
        $this->checks = $this->sanitize($checks);
        /**
         * Required check.
         */
        $this->add(WpConnectionCheck::class);
    }

    public function __get( $prop )
    {
        if( method_exists( $this, $prop ) )
            return $this->{$prop}();
    }

    public function add( String $check )
    {
        $this->checks[$check::name()] = $check;
    }

    public function url()
    {
        return $this->scheme . '://' . $this->host;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient( $options = [] )
    {
        $defaultConfig = [
            'base_uri' => $this->url(),
            'verify' => true,
        ];
        $config = array_merge($options, $defaultConfig);

        $this->client = new Client($config);
        return $this;
    }

    public function setResponse( $response )
    {
        $html = $response->getBody()->getContents();
        $this->header = $response->getHeaders();
        @$this->dom->loadHTML($html);
        $this->xpath = new DOMXpath($this->dom);
    }

    private function sanitize( array $checks )
    {
        return array_filter($checks);
    }

    public function disableSslConnection()
    {
        $this->removeCheck('ssl');
        $this->scheme = 'http';
        $this->setClient(['verify' => false]);
    }

    public function check()
    {
        foreach( $this->checks as $name => $class ) {
            $check = new $class($this);
            $result = $check->run()->result();
            $this->results->add($result);
        }

        return $this;
    }

    public function removeCheck( $remove )
    {
        if( !is_array($remove) )
            $remove = [$remove];

        $this->checks = array_diff($this->checks, $remove);
        return $this;
    }

    public function clearChecks()
    {
        $this->checks = [];
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function setUrl( $url, $scheme = 'https' )
    {
        $this->url = $url;
        if( strpos($url, 'http', 0) === false )
            throw new \Exception("Please provide the full URL");

        $url = parse_url($url);
        $this->host = $url['host'];
        $this->scheme = $scheme;
    }
}