<?php

namespace Motto;

use GuzzleHttp\Client;
use Spatie\SslCertificate\SslCertificate;
use Motto\Checks\WpCheck;
use Motto\Checks\WpConnectionCheck;
use JsonSerializable;
use DOMDocument;
use DOMXpath;

class WpChecker implements JsonSerializable {

    protected $checks = [];
    protected $results = [];
    protected $inits = [];
    protected $client;
    protected $header;
    protected $html;
    protected $dom;
    protected $xpath;
    protected $url;
    protected $host;
    protected $scheme;

    public function __construct( array $checks, string $url )
    {
        $this->dom = new DOMDocument;
        $this->setUrl( $url );
        $this->setClient();
        $this->add(new WpConnectionCheck( $this ));
        $this->collect($checks);
    }

    public function collect( $checks )
    {
        foreach( $checks as $check ) {
            $this->add(new $check( $this ));
        }
    }

    public function add( WpCheck $check )
    {
        if( method_exists( $check, 'init' ) )
            $this->inits[] = $check;

        $this->checks[$check->name()] = $check;
    }

    public function remove( $remove )
    {
        unset($this->checks[$remove]);
        return $this;
    }

    public function clearChecks()
    {
        $this->checks = [];
        return $this;
    }

    public function url()
    {
        return $this->scheme . '://' . $this->host;
    }

    public function getHost()
    {
        return $this->host;
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
            'http_errors' => true,
            'allow_redirects' => false,
            'timeout'  => 2.0,
        ];
        $config = array_merge($options, $defaultConfig);

        $this->client = new Client($config);
        return $this;
    }

    public function setResponse( $response )
    {
        $this->html = $response->getBody()->getContents();
        $this->header = $response->getHeaders();
        @$this->dom->loadHTML($this->html);
        $this->xpath = new DOMXpath($this->dom);
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getXpath()
    {
        return $this->xpath;
    }

    public function disableSslConnection()
    {
        $this->remove('ssl');
        $this->scheme = 'http';
        $this->setClient(['verify' => false]);
    }

    public function check()
    {
        foreach( $this->inits as $check ) {
            $check->init();
        }
        
        foreach( $this->checks as $check ) {
            $check->run();
            $this->results[$check->name()] = $check;
        }

        return $this;
    }

    public function results()
    {
        return $this->results;
    }

    public function result( $name )
    {
        return $this->results[$name];
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

    public function jsonSerialize()
    {
        return $this->results;
    }
}