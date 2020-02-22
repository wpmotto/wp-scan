<?php

namespace Motto;

use Motto\WpChecker;

class WpScan {

    protected $url;
    protected $checks = [
        'server' => \Motto\Checks\WpServerCheck::class,
        'endpoints' => \Motto\Checks\WpEndpointsCheck::class,
        'version' => \Motto\Checks\WpVersionCheck::class,
        'ssl' => \Motto\Checks\WpSslCheck::class,
        'plugins' => \Motto\Checks\WpPluginsCheck::class,
    ];

    public function __construct( String $url, Array $checks = [] )
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