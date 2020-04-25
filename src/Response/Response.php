<?php

declare(strict_types=1);

namespace Postal\Response;

use JsonSerializable;
use Postal\Exceptions\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;

class Response implements JsonSerializable
{
    protected $status;

    protected $time;

    protected $flags;

    protected $data;

    /**
     * Default Constructor
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $payload = json_decode((string) $response->getBody(), true);

        $this->assert($payload);

        $this->fill($payload);

        $this->parseData();
    }

    /**
     * Parse the Data provided in the API Response
     *
     * @return void
     */
    protected function parseData()
    {
        
    }

    /**
     * Assert the Response is valid, otherwise throw an Exception.
     * 
     * Sadly Postal currently does not uses HTTP Status Codes, so
     * we must check this against the status field. ;)
     *
     * @param array $payload
     * @throws Postal\Exceptions\InvalidRequestException
     * @return void
     */
    public function assert(array $payload)
    {
        if ( $payload['status'] == 'error' ){
            throw new InvalidRequestException(
                sprintf('%s: %s', $payload['data']['code'], $payload['data']['message'])
            );
        }
    }

    /**
     * Fill the instance with the provided Payload
     *
     * @param array $payload
     * @return void
     */
    protected function fill(array $payload)
    {
        $this->status = $payload['status'];
        $this->time = $payload['time'];
        $this->flags = $payload['flags'];
        $this->data = $payload['data'];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'time' => $this->time,
            'flags' => $this->flags,
            'data' => $this->data,
        ];
    }
}
