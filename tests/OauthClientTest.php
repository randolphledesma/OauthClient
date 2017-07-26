<?php

use PHPUnit\Framework\TestCase;

/**
 * @covers OauthClient
 */
final class OauthClientTest extends TestCase
{
    protected $configuration;

    public function setUp()
    {
        $this->configuration = [
            'base_uri'=>'https://api.openbankproject.com',
            'request_token_url'=>'https://api.openbankproject.com/oauth/initiate',
            'access_token_url'=>'https://api.openbankproject.com/oauth/token',
            'callback_url'=>'http://httpbin.org',
            'consumer_key'=>'vmnctxhmem0awemhzapw1mrvp2shrommlifd4ibi',
            'consumer_secret'=>'eho2w4p3h2molyr5v4fhtacr1igwe5hdxb01c2p0',
            'private_key_file'=>'',
            'private_key_passphrase'=>'',
            'signature_method'=>'HMAC-SHA1',
            'realm'=>'MDG'
        ];
    }

    public function testConnect()
    {
        $oauth = new OauthClient($this->configuration);
        try {
            $response = $oauth->doRequest([],
                $this->configuration['request_token_url'],
                'POST',
                ''
            );

            //throw new \Exception('error:' . $response);
        } catch (Exception $err) {
            throw new \Exception($err->getMessage());
        }
    }
}
