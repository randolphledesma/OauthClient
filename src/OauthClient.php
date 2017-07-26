<?php
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
    public $realm = 'MDG';
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // This should always be TRUE to secure SSL connections

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            self::ACCEPT.self::COLON.self::SPACE.self::APPLICATION_XML,
            self::CONTENT_TYPE.self::COLON.self::SPACE.self::APPLICATION_XML,
            self::AUTHORIZATION.self::COLON.self::SPACE.$this->buildAuthHeaderString($params, $realm, $url, $requestMethod),
        ));

        if ($requestMethod == self::GET) {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($requestMethod));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $result = curl_exec($curl);

        // Check if any error occurred
        if (curl_errno($curl)) {
            throw new \Exception(sprintf(self::SSL_ERROR_MESSAGE, curl_errno($curl), PHP_EOL, curl_error($curl)), curl_errno($curl));
        }

        // Check for errors and throw an exception
        if (($errorCode = curl_getinfo($curl, CURLINFO_HTTP_CODE)) > 300) {
            throw new \Exception($result, $errorCode);
        }

        return $result;
    }

}
