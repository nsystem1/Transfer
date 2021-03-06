<?php namespace Keios\TransferToTools\API;
/**
 * Base API Class, provides means of performing TransferTo WebPortal API calls
 */

use Keios\TransferToTools\API\Contracts\RequestClientInterface;
use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\Exceptions\NoCredentialsException;

/**
 * Class ApiConnector
 *
 *  Currently available command aliases:
 *
 *  'ping' => 'PingApiCommand'
 *
 *  'checkWallet' => 'CheckWalletApiCommand'
 *
 * @package Keios\TransferToTools
 */
class ApiConnector
{
    /**
     * Stores request client instance
     * @var RequestClientInterface
     */
    protected $requestClient;

    /**
     * Store response parser instance
     * @var XMLResponseParser
     */
    protected $responseParser;

    /**
     * Store api command factory instance
     * @var ApiCommandFactory
     */
    protected $apiCommandFactory;

    /**
     * Builds ApiConnector Object
     * @param RequestClientInterface $requestClient
     */
    public function __construct(RequestClientInterface $requestClient)
    {
        $this->requestClient = $requestClient;
        $this->requestClient->boot();
        $this->apiCommandFactory = new ApiCommandFactory();
        $this->responseParser = new XMLResponseParser();
    }

    /**
     * Sets authentication for API calls from this instance
     * @param TransferToApiKeyInterface $apiKey
     */
    public function authenticateBy(TransferToApiKeyInterface $apiKey)
    {
        $this->apiCommandFactory->setKey($apiKey);
    }

    /**
     * Performs dynamic alias lookup and command execution
     * @param $name
     * @param $arguments
     * @return ApiResponse
     *
     * @throws Exceptions\InvalidApiCommandException
     * @throws NoCredentialsException
     */
    public function __call($name, $arguments)
    {

        if (empty($arguments))
            $arguments[] = [];

        $apiCommand = $this->apiCommandFactory->make($name, $arguments);

        $responseXML = $this->requestClient->processRequest($apiCommand->getMethod(), $apiCommand->getBody());

        $statusCode = $this->requestClient->getLastStatusCode();

        $responseObject = new ApiResponse($responseXML, $statusCode);

        //todo - send object not string, but service files must be changed for this first
        return $responseObject->toXmlString();
    }

} 