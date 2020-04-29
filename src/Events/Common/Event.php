<?php

declare(strict_types=1);

namespace Postal\Events\Common;

interface Event
{
    /**
     * Default constructor method definition
     *
     * @param array $payload
     */
    public function __construct(array $payload);

    /**
     * Get the message type
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Casts the event to an Array
     * 
     * @return array
     */
    public function toArray() : array ;
}