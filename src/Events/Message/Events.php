<?php

declare(strict_types=1);

namespace Postal\Events\Message;

use Postal\Events\Common\Event;
use Postal\Events\Common\EventFactory;
use Postal\Exceptions\InvalidEventPayloadException;

abstract class Events extends EventFactory
{
    /**
     * @inheritDoc
     * 
     * @return MessageEvent
     */
    public static function fromPayload(array $payload): Event
    {
        return parent::fromPayload($payload);
    }

    /**
     * Create a Event from a Payload
     *
     * @param array $payload
     * @return MessageEvent
     */
    protected static function buildFromPayload(array $payload): MessageEvent
    {
        // @version 1.0.3: The event is extracted directly from the Payload.
        $event = static::getClass(str_replace('Message', '', $payload['event']));

        if (!class_exists($event)) {
            // This would be very strange indeed, but we should be prepared.
            // Maybe a weird payload was supplied.
            throw new InvalidEventPayloadException(
                'Invalid Payload provided to build a Message Event'
            );
        }

        return new $event($payload['payload']);
    }

    protected static function getClass($classname)
    {
        return __NAMESPACE__ . '\\' . $classname;
    }

    /**
     * Alias of static::fromPayload();
     *
     * @param array $payload
     * @return MessageEvent
     */
    public static function get(array $payload): Event
    {
        return static::fromPayload($payload);
    }

    /**
     * @inheritDoc
     *
     * @param array $payload
     * @return void
     */
    protected static function assertPayload(array $payload)
    {
        if (empty($payload['event'])) {
            throw new InvalidEventPayloadException(
                'Invalid Payload provided to build a Message Event'
            );
        }
    }
}
