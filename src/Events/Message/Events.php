<?php

declare(strict_types=1);

namespace Postal\Events\Message;

use Postal\Events\Common\Event;
use Postal\Events\Common\EventFactory;

abstract class Events extends EventFactory
{
    /**
     * Create a Event from a Payload
     *
     * @param array $payload
     */
    public static function fromPayload(array $payload) : Event
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
                }elseif( isset($payload['token']) )
                    $classname = MessageEvent::CLICKED;
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
     * @return Event
     */
    public static function get(array $payload) : Event
    {
        return static::fromPayload($payload);
    }
}
