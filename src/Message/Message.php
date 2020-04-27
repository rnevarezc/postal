<?php

declare(strict_types=1);

namespace Postal\Message;

class Message
{
    /**
     * The ID of the message
     *
     * @var int
     */
    protected $id;

    /**
     * The unique token of the message
     *
     * @var string
     */
    protected $token;

    /**
     * Status data
     *
     * @var array
     */
    protected $status = [];

    /**
     * Additional details
     *
     * @var array
     */
    protected $details = [];

    /**
     * Get a new instance from an array of data
     *
     * @param array $data
     * @return self
     */
    public static function fromData(array $data)
    {
        return new self($data);
    }

    /**
     * Default Constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? $this->id;
        
        $this->token = $data['token'] ?? $this->token;
    }

    /**
     * Get the message ID
     *
     * @return integer
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the token
     *
     * @return string
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * Set the Message status data
     *
     * @param array|null $data
     * @return void
     */
    public function setStatus(?array $data)
    {
        $this->status = is_array($data) ?: $this->status;
    }
    
    /**
     * Set the message details data
     *
     * @param array|null $data
     * @return void
     */
    public function setDetails(?array $data)
    {
        $this->details = is_array($data) ?: $this->details;
    }
    
}
