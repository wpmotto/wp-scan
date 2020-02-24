<?php

namespace Motto\Checks;

use Motto\Checks\WpCheck;

class WpVersionCheck extends WpCheck {
    
    public function run()
    {
        $meta = $this->checker
                        ->getXpath()
                        ->query("//meta[contains(@name,'generator')]");
                        
        $version = false;
        $generator = false;
        if( $meta->length > 0 ) {
            $generator = $meta[0]->getAttribute('content');
        }

        if( strpos(strtolower($generator), 'wordpress') !== false ) {
            $version = $generator;
        }

        $this->addProp('generator', $generator);
        $this->addProp('version', $version);
    }

}