<?php

declare(strict_types=1);

namespace Postal\Mail\Concerns;

use Postal\Response\Response;

trait HasResponse
{
    /**
     * The Postal response
     *
     * @var \Postal\Response\Response
     */
    protected $response;

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
}