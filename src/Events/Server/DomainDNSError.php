<?php

declare(strict_types=1);

namespace Postal\Events\Server;
use Postal\Events\Common\{Event, EventFactory};
use Postal\Events\Server\Concerns\HasServer;
use Postal\Exceptions\InvalidEventPayloadException;

class DomainDNSError extends EventFactory implements Event 
{  
    use HasServer;

    const TYPE = 'DomainDNSError';

    /**
     * The Domain of the DNS Error Event.
     *
     * @var string
     */
    public $domain;

    /**
     * The UUID of the DNS Error Event.
     *
     * @var string
     */
    public $uuid;

    /**
     * The timestamp of the DNS Error Verification
     *
     * @var float
     */
    public $dns_checked_at;

    /**
     * SPF Records
     *
     * @var array
     */
    public $spf = [];

    /**
     * Dkim records
     *
     * @var array
     */
    public $dkim = [];

    /**
     * MX records 
     *
     * @var array
     */
    public $mx = [];

    /**
     * Return path records
     *
     * @var array
     */
    public $return_path = [];

    /**
     * @inheritDoc
     */
    public function __construct(array $payload)
    {
        $this->domain = $payload['domain'];

        $this->uuid = $payload['uuid'];

        $this->checkedAt = $payload['dns_checked_at'];

        $this->setRecords($payload);

        $this->setServer($payload['server']);
    }

    /**
     * Set the SPF/DKIM/MX records of the Event.
     *
     * @param array $payload
     * @return void
     */
    protected function setRecords(array $payload)
    {
        $records = ['spf', 'dkim', 'mx', 'return_path'];

        foreach ($records as $type){
            $this->{$type} = [
                'status' => $payload[$type . '_status'],
                'error' => $payload[$type . '_error']
            ];
        }
    }

    /**
     * Get the Event type
     *
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * @inheritDoc
     */
    protected static function buildFromPayload(array $payload): Event
    {
        return new static($payload['payload']);
    }

    /**
     * @inheritDoc
     */
    protected static function assertPayload(array $payload)
    {
        if (!isset($payload['event']) || !is_array($payload['payload'])){
            throw new InvalidEventPayloadException(
                'Invalid Payload provided to build a valid SendLimit Event'
            );
        }
    }
    
    /**
     * @inheritDoc
     */
    public function toArray() : array
    {
        return (array) $this;
    }
}
