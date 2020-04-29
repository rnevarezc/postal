<?php

declare(strict_types=1);

namespace Postal\Events\Server;

use Postal\Events\Common\{Event, EventFactory};
use Postal\Events\Server\Concerns\HasServer;
use Postal\Exceptions\InvalidEventPayloadException;

abstract class SendLimit extends EventFactory implements Event 
{
    use HasServer;

    const TYPE = '';

    /**
     * Current Volume of messages
     *
     * @var int
     */
    protected $volume;

    /**
     * Server limit
     *
     * @var int
     */
    protected $limit;

    /**
     * @inheritDoc
     */
    protected static function buildFromPayload(array $payload): Event
    {
        return new static($payload);
    }

    /**
     * @inheritDoc
     */
    protected static function assertPayload(array $payload)
    {
        if (!isset($payload['server']) || !is_array($payload['server'])){
            throw new InvalidEventPayloadException(
                'Invalid Payload provided to build a valid SendLimit Event'
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function __construct(array $payload)
    {
        $this->setServer($payload['server']);
        $this->volume = $payload['volume'];
        $this->limit = $payload['limit'];
    }

    /**
     * Get the Event type
     *
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
    
    /**
     * @inheritDoc
     */
    public function toArray() : array
    {
        return [
            'server' => $this->server,
            'volume' => $this->volume,
            'limit' => $this->limit,
        ];
    }
}