<?php

namespace Motto\Checks;

interface WpCheckInterface {
    public static function name();
    public function run();
    public function result();
}

    // /**
    //  * TODO: move checks to WpCheck Classes
    //  */
    // public function server()
    // {
    //     $this->results->add('server', [
    //         'server' => $this->header['Server'] ?? null,
    //         'language' => $this->header['X-Powered-By'] ?? null,
    //     ]);
    // }

    // public function ssl()
    // {
    //     $certificate = SslCertificate::createForHostName($this->host);

    //     $this->results->add('endpoints', [
    //         'issuer' => $certificate->getIssuer(),
    //         'valid' => $certificate->isValid(),
    //         'expiration_date' => $certificate->expirationDate(),
    //         'days_to_expiry' => $certificate->expirationDate()->diffInDays(),
    //         'signature' => $certificate->getSignatureAlgorithm(),
    //     ]);
    // }

    // public function endpoints()
    // {
    //     $endpoints = [
    //         '/wp-admin' => false,
    //         '/wp-login.php' => false,
    //     ];
    //     foreach( $endpoints as $uri => $found ) {
    //         $endpoints[$uri] = (
    //             $this->client->get($uri)->getStatusCode() == 200
    //         );
    //     }

    //     $this->results->add('endpoints', [
    //         'endpoints' => $endpoints,
    //         'found' => count(array_filter($endpoints)),
    //     ]);
    // }

    // public function version()
    // {
    //     $meta = $this->xpath->query("//meta[contains(@name,'generator')]");
    //     $version = false;
    //     if( $meta->length > 0 )
    //         $generator = $meta[0]->getAttribute('content');

    //     if( strpos(strtolower($generator), 'wordpress') !== false )
    //         $version = $generator;

    //     $this->results->add('version', [
    //         'generator' => $generator,
    //         'version' => $version,
    //     ]);
    // }    
