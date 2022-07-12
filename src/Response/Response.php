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
     * This will be accompanied with an error code, a descriptive message and
     * further attributes which may be useful. 
     * 
     * @var string
     */
    protected $status;

    /**
     * The time attribute shows how long the request took to complete on
     * the server side.
     *
     * @var float
     */
    protected $time;

    /**
     * The flags attribute contains a hash of additional attributes which are
     * relevant to your request. For example, if you receive an array of data it
     * may be paginated and this pagination data will be returned in this hash.
     */
    protected $flags;

    /**
     * The data attribute contains the result of your request. 
     * Depending on the status, this will either contain the data requested or 
     * details of any error which has occurred.
    */
    protected $data;
    
    /**
     * Details provided for some error payloads.
    */
    protected $details;

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
        # Postal API is not RESTful, so every request is responded 
        # with a 200 code. We need to confirm if it is marked as an error.
        if ( $payload['status'] != 'success' ){
            
            # Postal API responses are very inconsistent, so we need to try
            # any kind of message received in the payload, otherwise the 
            # message is simply None.
            $message = $payload['data']['message'] ?? $payload['details'] ?? '';
            throw new InvalidRequestException($message);
        }
    }

    /**
     * Fill the instance with the provided Payload
     *
     * @param array $payload
     * @return void
     */
    protected function fill(array $payload): void
    {
        $this->status = $payload['status'];
        $this->time = $payload['time'];
        $this->flags = $payload['flags'];
        $this->data = $payload['data'];
        $this->details = $payload['details'] ?? null;
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
    public function jsonSerialize(): mixed
    {
        return [
            'status' => $this->status,
            'time' => $this->time,
            'flags' => $this->flags,
            'data' => $this->data,
            'details' => $this->details,
        ];
    }
}
