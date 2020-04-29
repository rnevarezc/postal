<?php

declare(strict_types=1);

namespace Postal\Response;

use JsonSerializable;
use Postal\Exceptions\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;

class Response implements JsonSerializable
{
    /**
     * Response status:
     *  
     * The status attribute will give you can indication about whether the request 
     * was performed successfully or whether an error occurred. Values which may be 
     * returned are shown below:
     * 
     * success - this means that the request completed successfully and returned 
     * what was expected.
     * 
     * parameter-error - the parameters provided for the action are not valid 
     * and should be revised.
     * 
     * error - an error occurred that didn't fit into the above categories. 
     * This will be accompanied with an error code, a descriptive message and further 
     * attributes which may be useful. 
     * 
     * @var string
     */
    protected $status;

    /**
     * The time attribute shows how long the request took to complete on the server side.
     *
     * @var float
     */
    protected $time;

    /**
     * The flags attribute contains a hash of additional attributes which are relevant 
     * to your request. For example, if you receive an array of data it may be paginated 
     * and this pagination data will be returned in this hash.
     */
    protected $flags;

    /**
     * The data attribute contains the result of your request. 
     * Depending on the status, this will either contain the data requested or 
     * details of any error which has occurred.
    */
    protected $data;

    /**
     * Default Constructor
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $payload = json_decode((string) $response->getBody(), true);

        $this->assertPayload($payload);

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
        // @todo: Maybe this is not necessary (right now)
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
    protected function assertPayload(array $payload)
    {
        $status = $payload['status'];

        if ( $status == 'error' || $status == 'parameter-error' ){
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
     * Get the data of the Response
     *
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
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
