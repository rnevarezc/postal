<?php

declare(strict_types=1);

namespace Postal\Message;

class Message
{
    /**
     * @var int The ID of the message
     */
    public $id;

    /**
     * @var string The unique token of the message
     */
    public $token;

    /**
     * @var string Direction of the message: Incoming | Outgoing
     */
    public $direction;

    /**
     * @var string The server message_id
     */
    public $message_id;

    /**
     * @var string The recipient email address
     */
    public $to;

    /**
     * @var string The sender email address
     */
    public $from;

    /**
     * @var string The subject of the message
     */
    public $subject;

    /**
     * @var float The timestamp of the message
     */
    public $timestamp;

    /**
     * @var string Email Spam status: Spam | NotSpam
     */
    public $spam_status;

    /**
     * @var string The tag of the message
     */
    public $tag;

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
        $attributes = array_keys(get_object_vars($this));
        
        foreach ($data as $key => $value){
            if ( in_array($key, $attributes) ){
                $this->$key = $value;
            }
        }
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

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return (array) $this;
    }
}
