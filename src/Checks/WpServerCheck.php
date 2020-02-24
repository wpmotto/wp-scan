<?php

namespace Motto\Checks;

use Motto\Checks\WpCheck;

class WpServerCheck extends WpCheck {
    
    public function run()
    {
        $header = $this->checker->getHeader();
        $this->addProp('server', $header['Server'] ?? null);
        $this->addProp('language', $header['X-Powered-By'] ?? null);
    }

}