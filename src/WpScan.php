<?php

namespace Motto;

use Motto\WpChecker;

class WpScan {

    protected $url;
    protected $checker;

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
        $this->setChecks($checks);
        $this->checker = new WpChecker($this->checks, $this->url);
    }

    public function setChecks( array $checks )
    {
        $arr = [];
        $checks = array_filter($checks);
        foreach( $checks as $name => $check ) {
            if( $check == true && is_bool($check) ) {
                $arr[$name] = $this->checks[$name];
            } else {
                $arr[$name] = $check;
            }
        }

        $this->checks = $arr;
        return $this;
    }

    public function run()
    {
        $this->checker->check();
        return $this;
    }

    public function json()
    {
        return json_encode( $this->checker );
    }

    public function get()
    {
        return $this->results;
    }

    public function result( $name )
    {
        return $this->checker->result( $name );
    }
}