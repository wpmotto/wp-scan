<?php

namespace Motto\Checks;

use Motto\Checks\WpCheck;

class WpEndpointsCheck extends WpCheck {

    protected $endpoints = [
        '/wp-admin' => false,
        '/wp-login.php' => false,
    ];

    public function run()
    {
        foreach( $this->endpoints as $uri => $found ) {
            $this->endpoints[$uri] = (
                $this->checker->getClient()->get($uri)->getStatusCode() == 200
            );
        }

        $this->addProp('endpoints', $this->endpoints);
        $this->addProp('found', count(array_filter($this->endpoints)));
    }

}