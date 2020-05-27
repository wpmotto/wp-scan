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
            try {
                $response = $this->checker->getClient()->get($uri);
                $this->endpoints[$uri] = (
                    $response->getStatusCode() == 200
                );
            } 
            catch (\Exception $e) {
                $this->endpoints[$uri] = false;
            }     
        }

        $this->addProp('endpoints', $this->endpoints);
        $this->addProp('found', count(array_filter($this->endpoints)));
    }

}