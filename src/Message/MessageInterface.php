<?php

declare(strict_types=1);

namespace Postal\Message;

use Postal\Client;
use Postal\Response;

interface MessageInterface
{
    /**
     * Get attributes for payload.
     *
     * @param array $query
     * @return array
     */
    public function getAttributes() : array ;

    /**
     * Set the Response
     *
     * @param Response $response
     * @return void
     */
    public function setResponse(Response $response) ;

    /**
     * Get the message type
     *
     * @return string
     */
    public function getType() : string;
}