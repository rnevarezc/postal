<?php

declare(strict_types=1);

namespace Postal;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Postal\Message\MessageInterface as Message;

class Client
{
    /**
     * The Postal Endpoint Hostname
     *
     * @var string
     */
    protected $host;

    /**
     * The api-key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Additional headers for the Request
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Default Constructor
     *
     * @param string $host
     * @param string $apiKey
     * @param array $headers
     */
    public function __construct(string $host, string $apiKey, array $headers = [])
    {
        $this->setHost($host);
        $this->setApiKey($apiKey);
        $this->setHeaders($headers);
    }

    /**
     * Set the Host
     *
     * @param string $host
     * @return void
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * Set the Api Key
     *
     * @param string $key
     * @return void
     */
    public function setApiKey(string $key)
    {
        $this->apiKey = $key;
    }

    /**
     * Set additional headers
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge(
            $this->headers,
            $headers
        );
    }

    /**
     * Get the default headers for the request merging
     * any additional headers previously set.
     *
     * @return array
     */
    protected function getHeaders() : array
    {
        $default = [
            'x-server-api-key' => $this->apiKey,
            'content-type' => 'application/json',
        ];

        return array_merge($this->headers, $default);
    }

    /**
     * Get the Request to send to the Endpoint
     * 
     * @param string $uri
     * @param Mail $mail
     * @return Request
     */
    protected function getRequest(string $uri, array $payload) : Request
    {
        return new Request('POST', $uri, $this->getHeaders(), json_encode($payload));
    }

    /**
     * Get the api uri.
     *
     * @param string $resource
     * @param string $action
     * @return string
     */
    protected function getUri(string $resource, string $action) : string
    {
        return sprintf('%s/api/v1/%s/%s', $this->host, $resource, $action);
    }

    /**
     * Send the request to the Endpoint
     *
     * @param string $resource
     * @param string $action
     * @param Mail $mail
     * @throws RequestException If something happened
     * @return Response
     */
    protected function performRequest(string $resource, string $action, array $payload) : Response
    {
        $http = new HttpClient();
        
        return new Response($http->send(
            $this->getRequest($this->getUri($resource, $action), $payload)
        ));
    }

    /**
     * Send Endpoint.
     *
     * @param array $payload
     * @param string $action
     * @return Response
     */
    public function send(Message $message) : Response
    {
        $response = $this->performRequest('send', 'message', $message->getAttributes());
        $message->setResponse($response);

        return $response;
    }
}
