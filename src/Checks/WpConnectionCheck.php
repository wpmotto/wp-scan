<?php

namespace Motto\Checks;

use Motto\WpChecker;
use Motto\Checks\WpCheckInterface;
use Motto\WpResult;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class WpConnectionCheck implements WpCheckInterface {
    
    CONST CHECK_NAME = 'connection';
    
    protected $checker;
    protected $data = [];

    public function __construct( WpChecker $checker )
    {
        $this->checker = $checker;
    }

    public static function name()
    {
        return self::CHECK_NAME;
    }

    public function append( $data )
    {
        $this->data = array_merge_recursive( $this->data, $data);
        return $this;
    }

    public function run()
    {
        $client = $this->checker->getClient();
        try {
            $response = $client->get( $this->checker->url() );
            $this->checker->setResponse($response);
            $data['scheme'][$this->checker->getScheme()] = 'valid';
            $this->append($data);
        } catch (\Exception $e) {
            $response = $e->getHandlerContext();
            if( $response['ssl_verifyresult'] == 0 ) {
                $data['scheme'][$this->checker->getScheme()] = 'invalid';
                $this->append($data);
                $this->checker->disableSslConnection();
                $this->run();
            }
        } catch (ConnectException $e) {
            $response = $e->getHandlerContext();
            $this->checker->clearChecks();
            $this->append([
                'error' => [
                    'curl_error' => $response['errno'],
                    'message' => $response['error'],
                ],
            ]);
        }

        return $this;
    }

    public function result()
    {
        return new WpResult(self::CHECK_NAME, $this->data);
    }
}