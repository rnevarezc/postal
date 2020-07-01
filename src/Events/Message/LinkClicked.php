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
    public $url;

    /**
     * Unique token for the MessageLinkClicked
     *
     * @var string
     */
    public $token;

    /**
     * The IP Address originating the Click Event
     *
     * @var string
     */
    public $ip_address;

    /**
     * The user agent originating the Click Event
     *
     * @var string
     */
    public $user_agent;

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
        return static::LINK_CLICKED;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'token' => $this->token,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
        ];
    }
}
