<?php

declare(strict_types=1);

namespace Postal\Message;

use Postal\Client;
use Postal\Response;

trait MessageTrait
{
    /**
     * Message Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The Postal response
     *
     * @var Response
     */
    protected $response;

    /**
     * Get the message attributes
     *
     * @return array
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * Set the Response
     *
     * @param Response $response
     * @return void
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get the Processed Message Response;
     *
     * @return Response|null
     */
    public function getResponse() :? Response
    {
        return $this->response;
    }

    /**
     * Get the type of the message, based on the Classname 
     *
     * @return string
     */
    public function getType(): string
    {
        $path = explode('\\', static::class);

        return strtolower(array_pop($path));
    }
}