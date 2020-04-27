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
        
        $this->setResponseData($response->getData());
    }

    /**
     * Set the Data received from the Response
     *
     * @param array $data
     * @return void
     */
    abstract protected function setResponseData(array $data);

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