<?php

declare(strict_types=1);

namespace Postal;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Postal\Mail\Mailable as Message;
use Postal\Response\Response;

class Client
{
    /**
     * The Postal Endpoint Hostname
     *
     * @var string
     */
    protected $host;

    /**
     * The API token for a server that you wish to authenticate with.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Additional headers for the HTTP Request
     * 
     * This is useful if you need to send any other authentication headers
     * for your own endpoint. (Basic, Bearer, etc)
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
        // The default headers needed by the Postal API.
        $default = [
            'x-server-api-key' => $this->apiKey,
            'content-type' => 'application/json',
        ];

        return array_merge($this->headers, $default);
    }

    /**
     * Get the PSR7 Request to send to the Endpoint
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
     * Get the API uri.
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
     * Perform the request to the API
     *
     * @param string $resource
     * @param string $action
     * @param Mail $mail
     * @throws RequestException If something happened
     * @return Response
     */
    protected function performRequest(
        string $resource, 
        string $action, 
        array $payload
    ) : ResponseInterface {
        $http = new HttpClient();
        
        return $http->send(
            $this->getRequest($this->getUri($resource, $action), $payload)
        );
    }

    /**
     * Send a Message.
     *
     * @param array $payload
     * @param string $action
     * @return Response
     */
    public function send(Message $message) : Response
    {
        $response = new Response(
            $this->performRequest(
                'send', $message->getType(), $message->getAttributes()
            )
        );

        $message->setResponse($response);

        return $response;
    }

    /**
     * Get the Message Details from the Server.
     *
     * @param integer $id
     * @param array|bool $extra
     * @return Response
     */
    public function getMessageDetails(int $id, $extra = ['status', 'details']) : Response
    {
        $response = new Response(
            $this->performRequest('messages', 'message', [
                'id' => $id,
                '_expansions' => $extra
            ])
        );

        return $response;
    }

    /**
     * Get the Message Deliveries from the Server.
     *
     * @param integer $id
     * @return Response
     */
    public function getMessageDeliveries(int $id) : Response
    {
        $response = new Response(
            $this->performRequest('messages', 'deliveries', ['id' => $id])
        );

        return $response;
    }
}
