<?php

declare(strict_types=1);

namespace Postal\Events\Server;
use Postal\Events\Common\{Event, EventFactory};
use Postal\Events\Server\Concerns\HasServer;

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
    public $checkedAt;

    public $spf = [];

    public $dkim = [];

    public $mx = [];

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
    public static function fromPayload(array $payload): Event
    {
        return new static($payload);
    }
}
