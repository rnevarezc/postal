<?php

declare(strict_types=1);

namespace Postal\Mail;

use Postal\Response\Response;

interface Mailable
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