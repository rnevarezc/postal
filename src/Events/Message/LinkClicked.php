<?php

declare(strict_types=1);

namespace Postal\Events\Message;

use Postal\Events\Message\Concerns\HasMessage;

class LinkClicked implements MessageEvent
{  
    use HasMessage;

    /**
     * The url clicked
     *
     * @var string
     */
    protected $url;

    /**
     * Unique token for the MessageLinkClicked
     *
     * @var string
     */
    protected $token;

    /**
     * The IP Address originating the Click Event
     *
     * @var string
     */
    protected $ipAddress;

    /**
     * The user agent originating the Click Event
     *
     * @var string
     */
    protected $userAgent;

    public function __construct(array $payload)
    {
        $this->url = $payload['url'];
        $this->token = $payload['token'];
        $this->ipAddress = $payload['ip_address'];
        $this->userAgent = $payload['user_agent'];

        $this->setMessage($payload['message']);
    }

    public function getType(): string
    {
        return static::CLICKED;
    }
}
