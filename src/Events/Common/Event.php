<?php

declare(strict_types=1);

namespace Postal\Events\Common;

use JsonSerializable;

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
}