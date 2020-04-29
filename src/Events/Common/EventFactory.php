<?php

declare(strict_types=1);

namespace Postal\Events\Common;

use Psr\Http\Message\RequestInterface;

abstract class EventFactory
{
    /**
     * Get a new instance from a Request
     * 
     * This is useful when you implement a PSR-7 Handler (or a controller) that
     * receives a PSR-7 RequestInterface for a Postal Event Webhook
     *
     * @param array $data
     * @return Event
     */
    public static function fromRequest(RequestInterface $request) : Event
    {
        return static::fromPayload(json_decode((string) $request->getBody(), true));
    }

    /**
     * Get a new Event instance from a Payload
     * 
     * Event is created from a array type payload.
     *
     * @param array $payload
     */
    public static function fromPayload(array $payload) : Event 
    {
        static::assertPayload($payload);
        
        return static::buildFromPayload($payload);
    }

    /**
     * Assert the Payload is valid to Build an Event.
     *
     * @param array $payload
     * @throws \Postal\Exceptions\InvalidEventPayloadException;
     * @return void
     */
    abstract protected static function assertPayload(array $payload);

    /**
     * Build a new Event instance from a Payload
     *
     * @param array $payload
     * @return Event
     */
    abstract protected static function buildFromPayload(array $payload) : Event ;
}
