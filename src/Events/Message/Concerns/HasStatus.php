<?php

declare(strict_types=1);

namespace Postal\Events\Message\Concerns;

trait HasStatus
{
    use HasMessage;

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
    public $sent_with_ssl;

    /**
     * Timestamp of the Message event.
     *
     * @var float
     */
    public $timestamp;

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
    public function toArray() : array
    {
        return [
            'status' => $this->status,
            'details' => $this->details,
            'time' => $this->time,
            'sent_with_ssl' => $this->sent_with_ssl,
            'timestamp' => $this->timestamp,
        ];
    }
}