<?php

declare(strict_types=1);

namespace Postal\Events\Message;

use Postal\Events\Message\Concerns\HasStatus;

class DeliveryFailed implements MessageEvent
{  
    use HasStatus;

    public function getType(): string
    {
        return static::DELAYED;
    }
}
