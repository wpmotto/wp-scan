<?php

namespace Motto\Checks;

use Motto\Checks\WpCheck;
use Spatie\SslCertificate\SslCertificate;

class WpSslCheck extends WpCheck {
    
    public function run()
    {
        $certificate = SslCertificate::createForHostName(
            $this->checker->getHost()
        );

        $this->addProps([
            'issuer' => $certificate->getIssuer(),
            'valid' => $certificate->isValid(),
            'expiration_date' => $certificate->expirationDate(),
            'days_to_expiry' => $certificate->expirationDate()->diffInDays(),
            'signature' => $certificate->getSignatureAlgorithm(),
        ]);
    }

}