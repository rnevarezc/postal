<?php

declare(strict_types=1);

namespace Postal\Events\Server\Concerns;

trait HasServer
{
    /**
     * Server Info
     *
     * @var array
     */
    protected $server = [];

    /**
     * @inheritDoc
     */
    public function setServer(array $data)
    {
        $this->server = $data;
    }

    /**
     * @inheritDoc
     */
    public function getServer() : array
    {
        return $this->server;
    }

    /**
     * Get the server uuid
     *
     * @return string
     */
    public function getServerId() : string
    {
        return $this->server['uuid'];
    }
}