<?php

declare(strict_types=1);

namespace Postal\Events\Message\Concerns;

use Postal\Message\Message;

trait HasMessage
{
    /**
     * The Message associated to the event
     *
     * @var Message
     */
    public $message;

    /**
     * @inheritDoc
     */
    public function setMessage(array $data)
    {
        $this->message = Message::fromData($data);
    }

    /**
     * @inheritDoc
     */
    public function getMessage() : Message
    {
        return $this->message;
    }
}