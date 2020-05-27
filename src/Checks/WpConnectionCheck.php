<?php

namespace Motto\Checks;

use Motto\Checks\WpCheck;
use GuzzleHttp\Exception\{
    RequestException,
    ConnectException
};

class WpConnectionCheck extends WpCheck {
    
    protected $props = [];

    public function init()
    {
        $client = $this->checker->getClient();
        try {
            $response = $client->get( $this->checker->url() );
            $this->checker->setResponse($response);
            $this->props[$this->checker->getScheme()] = 'valid';
        } 
        catch (ConnectException $e) {
            $response = $e->getHandlerContext();
            $this->checker->clearChecks();
            $this->error([
                'type' => ['curlno' => $response['errno']],
                'message' => $response['error'],
            ]);
        } 
        catch (RequestException $e) {
            $response = $e->getHandlerContext();
            if( isset($response['ssl_verifyresult']) && $response['ssl_verifyresult'] == 0 ) {
                $this->props[$this->checker->getScheme()] = 'invalid';
                $this->checker->disableSslConnection();
                $this->init();
            } else {
                $this->error([
                    'type' => 'RequestException',
                    'response' => $response,
                ]);
            }
        } 
        catch (\Exception $e) {
            $this->checker->clearChecks();
            $this->error([
                'type' => 'generic',
                'message' => $e->getMessage(),
            ]);
        }

        return $this;
    }

    public function run()
    {
        $this->addProps($this->props);
    }
}