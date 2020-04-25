<?php

declare(strict_types=1);

namespace Postal\Mail;

use Postal\Mail\Concerns\{HasAttributes, HasResponse};

class Raw implements Mailable
{
    use HasResponse;
    use HasAttributes;

    /**
     * Default attributes
     *
     * @var array
     */
    protected $attributes = [
        'mail_from' => [],
        'rcpt_to' => [],
        'data' => '',
        'bounce' => false,
    ];

    public function __construct(array $attributes = [])
    {  
        $this->setAttributes($attributes);
    }

    public function setRcptTo(array $addresses)
    {
        $this->attributes['rcpt_to'] = $addresses;
    }

    public function addRcptTo($address)
    {
        return $this->addRecipients($address, 'rcpt_to');
    }

    public function setMailFrom(string $address)
    {
        $this->attributes['mail_from'] = $address;

        return $this;
    }

    public function setData(string $data)
    {
        $this->attributes['data'] = $data;
        
        return $this;
    }

    public function setBounce(bool $bounce = true)
    {
        $this->attributes['bounce'] = $bounce;
        
        return $this;
    }

    public function getType() : string
    {
        return 'raw';
    }

}
