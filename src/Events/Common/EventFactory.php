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
     * @return MessageEvent
     */
    public static function fromRequest(RequestInterface $request) : Event
    {
        return static::fromPayload(json_decode((string) $request->getBody(), true));
    }

    /**
     * Default Constructor
     * 
     * Event is created from a array type payload.
     *
     * @param array $payload
     */
    abstract public static function fromPayload(array $payload) : Event;
}
