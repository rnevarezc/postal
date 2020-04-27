<?php

declare(strict_types=1);

namespace Postal\Events\Message\Concerns;

use Postal\Message\Message;

trait HasStatus
{
    /**
     * Event Status
     *
     * @var string
     */
    public $status;

    /**
     * Detail of the status event
     *
     * @var string
     */
    public $details;

    /**
     * Output of the server Event
     *
     * @var string
     */
    public $output;

    /**
     * Time elapsed 
     *
     * @var float
     */
    public $time;

    /**
     * True if the message was sent with SSL Encription
     *
     * @var bool
     */
    public $sentWithSSL;

    /**
     * Timestamp of the Message event.
     *
     * @var float
     */
    public $timestamp;

    /**
     * The Message associated to the event
     *
     * @var Message
     */
    public $message;

    /**
     * @inheritDoc
     */
    public function __construct(array $payload)
    {
        foreach ($payload as $key => $value){
            $this->{$key} = $value;
        }

        $this->setMessage($payload['message']);
    }

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