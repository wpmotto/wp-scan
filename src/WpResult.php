<?php

namespace Motto;

use JsonSerializable;

class WpResult implements JsonSerializable {

    protected $name;
    protected $data;

    public function __construct( $name, $data = [] )
    {
        $this->name = $name;
        $this->set($data);
    }

    public function name()
    {
        return $this->name;
    }

    public function set( array $data )
    {
        $this->data = $data;
        return $this;
    }
    
    public function jsonSerialize() {
        return $this->data;
    }    
}