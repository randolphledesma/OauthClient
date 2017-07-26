<?php

namespace MDG\OauthClient;

/**
 * OauthClient class
 *
 */
class OauthClient
{
    protected $urlService;
    public $signatureBaseString;
    public $authHeader;
    protected $consumerKey;
    private $privateKey;
    private $version = '1.0';
    private $signatureMethod = 'RSA-SHA1';
    public $realm = 'eWallet';
    public $errorMessage = null;

    public function __construct()
    {

    }

    /**
     * This method allows the class client to override the
     * private key passed in the constructor.
     *
     * @param PrivateKeyInterface $privateKey
     *
     * @return Connector
     */
    public function setPrivateKey(PrivateKeyInterface $privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Returns the consumer key according environment.
     *
     * @return string
     */
    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

}
