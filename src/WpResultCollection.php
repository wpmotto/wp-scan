<?php

namespace Motto;

use Motto\WpResult;
use JsonSerializable;

class WpResultCollection implements JsonSerializable {

    protected $results = [];

    public function add( WpResult $result )
    {
        $this->results[$result->name()] = $result;
        return $this;
    }

    public function get( $name )
    {
        return $this->results[$name];
    }

    public function remove( $name )
    {
        unset($this->results[$name]);
        return $this;
    }

    public function jsonSerialize() {
        return $this->results;
    }    
}