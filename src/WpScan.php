<?php

namespace Motto;

use Motto\WpChecker;

class WpScan {
    protected $url;
    protected $client;
    protected $checks = [
        'endpoints' => true,
        'version' => true,
        'plugins' => false,
    ];

    protected $results = [];

    public function __construct( String $url, $checks = [] )
    {
        $this->url = $url;
        $this->checks($checks);
    }

    public function checks( array $checks )
    {
        $this->checks = array_merge($this->checks, $checks);
        return $this;
    }

    public function run()
    {
        $checker = new WpChecker($this->checks, $this->url);
        $this->results = $checker->check()->getResults();
        return $this;
    }

    public function json()
    {
        return json_encode( $this->results );
    }

    public function get()
    {
        return $this->results;
    }
}