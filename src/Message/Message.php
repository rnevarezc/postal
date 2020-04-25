<?php

declare(strict_types=1);

namespace Postal\Message;

class Message
{
    protected $id;

    protected $token;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        
        $this->token = $data['token'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
    }

}
