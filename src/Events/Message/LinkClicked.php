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
    protected $ip_address;

    /**
     * The user agent originating the Click Event
     *
     * @var string
     */
    protected $user_agent;

    public function __construct(array $payload)
    {
        $this->url = $payload['url'];
        $this->token = $payload['token'];
        $this->ip_address = $payload['ip_address'];
        $this->user_agent = $payload['user_agent'];

        $this->setMessage($payload['message']);
    }

    public function getType(): string
    {
        return static::CLICKED;
    }
    
    /**
     * @inheritDoc
     */
    public function toArray() : array
    {
        return [
            'url' => $this->url,
            'token' => $this->token,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
        ];
    }
}
