<?php

declare(strict_types=1);

namespace Postal;

use JsonSerializable;
use Postal\Exceptions\InvalidRequestException;
use Psr\Http\Message\ResponseInterface;

class Response implements JsonSerializable
{
    protected $status;

    protected $time;

    protected $messageId;

    protected $messages;

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

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    protected function fill(array $payload)
    {
        $this->status = $payload['status'];
        $this->time = $payload['time'];

        $this->messageId = $payload['data']['message_id'];
        $this->messages = $payload['data']['messages'];
    }

    public function getRecipients() : array
    {
        return $this->messages;
    }

    public function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'time' => $this->time,
            'messageId' => $this->messageId,
            'messages' => $this->messages
        ];
    }
}
