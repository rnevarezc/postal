<?php

declare(strict_types=1);

namespace Postal\Events\Message;

use Postal\Events\Common\Event;
use Postal\Message\Message;

interface MessageEvent extends Event
{
    const SENT = 'Sent';
    const DELAYED = 'Delayed';
    const DELIVERY_FAILED = 'DeliveryFailed';
    const HELD = 'Held';
    const BOUNCED = 'Bounced';
    const CLICKED = 'LinkClicked';

    /**
     * Get attributes for payload.
     *
     * @param array $query
     * @return array
     */
    public function getMessage() : Message ;

    /**
     * Set the message
     *
     * @param array $message
     * @return void
     */
    public function setMessage(array $message) ;

    /**
     * Get the message type
     *
     * @return string
     */
    public function getType() : string;
}