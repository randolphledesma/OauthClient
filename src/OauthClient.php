<?php
/**
 * OauthClient class
 *
 */
class OauthClient
{
    const OAUTH_START_STRING = 'OAuth ';

    const POST = 'POST';
    const PUT = 'PUT';
    const GET = 'GET';

    const SSL_ERROR_MESSAGE = 'SSL Error Code: %s %sSSL Error Message: %s';

    protected $endpoints;

    public $signatureBaseString;
    public $authHeader;
    protected $consumerKey;
    private $privateKey;
    private $version = '1.0';
    private $signatureMethod;
    public $realm;
    public $errorMessage = null;

    public function __construct($configuration)
    {
        $this->endpoints = array(
            'base_uri'=>isset($configuration['base_uri']) ? $configuration['base_uri'] : '',
            'request_token_url'=>isset($configuration['request_token_url']) ? $configuration['request_token_url'] : '',
            'access_token_url'=>isset($configuration['access_token_url']) ? $configuration['access_token_url'] : '',
            'callback_url'=>isset($configuration['callback_url']) ? $configuration['callback_url'] : ''
        );

        $this->signatureMethod = isset($configuration['signature_method']) ? $configuration['signature_method'] : 'RSA-SHA1';
        $this->realm = isset($configuration['realm']) ? $configuration['realm'] : 'Realm';
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

    /**
     * This method allows the class client to override the
     * signature method passed in the constructor.
     *
     * @param string $method
     */
    public function setSignatureMethod($method)
    {
        $this->signatureMethod = $method;
    }

    /**
     * @param array       $params
     * @param string|null $body
     *
     * @return string
     */
    public function doRequestToken($params, $body)
    {
        return $this->doRequest($params, $this->urlService->getRequestUrl(), self::POST, $body);
    }

    /**
     *  Method used for all Http connections.
     *
     * @param array       $params
     * @param string      $url
     * @param string      $requestMethod
     * @param string|null $body
     *
     * @throws \Exception - When connection error
     *
     * @return mixed - Raw data returned from the HTTP connection
     */
    public function doRequest($params, $url, $requestMethod, $body = null)
    {
        if ($body !== null) {
            //$params[self::OAUTH_BODY_HASH] = $this->generateBodyHash($body);
        }

        try {
            return $this->connect($params, $this->realm, $url, $requestMethod, $body);
        } catch (\Exception $e) {
            //$this->errorMessage = $this->checkForErrors($e);
        }
    }

    /**
     * Method to generate the body hash.
     *
     * @param string $body
     *
     * @return string
     */
    public function generateBodyHash($body)
    {
        $sha1Hash = sha1($body, true);

        return base64_encode($sha1Hash);
    }

    /**
     * General method to handle all HTTP connections.
     *
     * @param array       $params
     * @param string      $realm
     * @param string      $url
     * @param string      $requestMethod
     * @param string|null $body
     *
     * @throws \Exception - If connection fails or receives a HTTP status code > 300
     *
     * @return mixed
     */
    private function connect($params, $realm, $url, $requestMethod, $body = null)
    {
        $curl = curl_init($url);

        // Adds the CA cert bundle to authenticate the SSL cert
        //curl_setopt($curl, CURLOPT_CAINFO, __DIR__.self::SSL_CA_CER_PATH_LOCATION);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/xml; charset=utf-8;',
            'Content-Type: application/xml; charset=utf-8;',
            'Authorization: '
            //self::AUTHORIZATION.self::COLON.self::SPACE.$this->buildAuthHeaderString($params, $realm, $url, $requestMethod),
        ));

        //self::AUTHORIZATION.self::COLON.self::SPACE.$this->buildAuthHeaderString($params, $realm, $url, $requestMethod),

        if ($requestMethod == self::GET) {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($requestMethod));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $result = curl_exec($curl);

        throw new Exception('throw');

        // Check if any error occurred
        if (curl_errno($curl)) {
            throw new \Exception(sprintf(self::SSL_ERROR_MESSAGE, curl_errno($curl), PHP_EOL, curl_error($curl)), curl_errno($curl));
        }

        // Check for errors and throw an exception
        if (($errorCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) > 300) {
            throw new \Exception($result);
        }

        curl_close($curl);

        return $result;
    }

}
