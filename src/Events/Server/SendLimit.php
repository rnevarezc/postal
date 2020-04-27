<?php

declare(strict_types=1);

namespace Postal\Events\Server;

use Postal\Events\Common\{Event, EventFactory};
use Postal\Events\Server\Concerns\HasServer;

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
    public static function fromPayload(array $payload): Event
    {
        return new static($payload);
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
}