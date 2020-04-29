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
    public static function fromPayload(array $payload) : Event
    {
        return parent::fromPayload($payload);
    }
    
    /**
     * Create a Event from a Payload
     *
     * @param array $payload
     * @return MessageEvent
     */
    protected static function buildFromPayload(array $payload) : MessageEvent
    {
        switch ($payload['status']){
            case MessageEvent::SENT:
                $classname = MessageEvent::SENT;
            break;

            case MessageEvent::DELAYED:
                $classname = MessageEvent::DELAYED;
            break;

            case MessageEvent::DELIVERY_FAILED:
                $classname = MessageEvent::DELIVERY_FAILED;
            break;

            case MessageEvent::HELD:
                $classname = MessageEvent::HELD;
            break;

            default:
                if ( isset($payload['bounce'])){
                    $classname = MessageEvent::BOUNCED;
                }elseif( isset($payload['token']) ){
                    $classname = MessageEvent::CLICKED;
                }
            break;
        }

        $event = static::getClass($classname);
        
        return new $event($payload);
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
    public static function get(array $payload) : Event
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
        if ( 
            empty($payload['status']) && 
            empty($payload['bounce']) &&
            empty($payload['token'])
        ){
            throw new InvalidEventPayloadException(
                'Invalid Payload provided to build a Message Event'
            );
        }
    }
}
