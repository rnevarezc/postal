<?php

declare(strict_types=1);

namespace Postal\Events\Message;

use Postal\Events\Message\Concerns\HasMessage;
use Postal\Message\Message;

class Bounced implements MessageEvent
{  
    use HasMessage;
    
    /**
     * The Message Bounce associated to the event
     *
     * @var Message
     */
    protected $bounce;

    public function __construct(array $payload)
    {
        $this->setMessage($payload['original_message']);

        $this->setBounce($payload['bounce']);
    }

    /**
     * Set the messages from a array of data.
     *
     * @param array $data
     * @return void
     */
    public function setBounce(array $data)
    {
        $this->bounce = Message::fromData($data);
    }

    /**
     * Get the bounce Message
     *
     * @return Message
     */
    public function getBounce() : Message
    {
        return $this->bounce;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return static::BOUNCED;
    }

    /**
     * @inheritDoc
     */
    public function toArray() : array
    {
        return [
            'message' => (array) $this->message,
            'bounce' => (array) $this->bounce,
        ];
    }
}
