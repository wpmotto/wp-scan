<?php

namespace Motto\Checks;

use Motto\WpChecker;
use JsonSerializable;

abstract class WpCheck implements JsonSerializable {
    
    protected $error;
    protected $props = [];
    protected $checker;

    abstract public function run();

    public function __construct( WpChecker $checker )
    {
        $this->checker = $checker;
        $this->error = false;
    }

    public function json()
    {
        return json_encode($this->props);
    }

    public function name() {
        $class = (new \ReflectionClass($this))->getShortName();
        $words = trim(preg_replace('/[A-Z]/', ' $0', $class));
        $name = str_replace(
            ' Check', '', str_replace('Wp ', '', $words)
        );

        return strtolower($name);
    }

    public function addProp( $prop, $value )
    {
        $this->props[$prop] = $value;
        return $this;
    }

    public function addProps( Array $props )
    {
        $this->props = array_merge($this->props, $props);
        return $this;
    }

    public function error( Array $error )
    {
        $this->error = $error;
    }

    public function getErrors()
    {
        return $this->error;
    }

    public function result() {
        return $this->props;
    }

    public function jsonSerialize()
    {
        return $this->props;
    }
}