<?php

declare(strict_types=1);

namespace Postal\Mail;

use Postal\Mail\Concerns\{HasAttributes, HasResponse};

class Mail implements Mailable
{
    use HasResponse;
    use HasAttributes;

    /**
     * Default attributes
     *
     * @var array
     */
    protected $attributes = [
        'to' => [],
        'cc' => [],
        'bcc' => [],
        'headers' => null,
        'plain_body' => null,
        'attachments' => [],
    ];

    public function __construct(array $attributes = [])
    {  
        $this->setAttributes($attributes);
    }

    public function setTo(array $addresses)
    {
        $this->attributes['to'] = $addresses;
    }

    public function addTo($address)
    {
        return $this->addRecipients($address, 'to');
    }

    public function addCc($address)
    {
        return $this->addRecipients($address, 'cc');
    }

    public function addBcc($address)
    {
        return $this->addRecipients($address, 'bcc');
    }

    public function setFrom(string $address)
    {
        $this->attributes['from'] = $address;

        return $this;
    }

    public function setSender(string $address)
    {
        $this->attributes['sender'] = $address;

        return $this;
    }

    public function setSubject(string $subject)
    {
        $this->attributes['subject'] = $subject;

        return $this;
    }

    public function setTag($tag)
    {
        $this->attributes['tag'] = $tag;

        return $this;
    }

    public function setReplyTo($replyTo)
    {
        $this->attributes['reply_to'] = $replyTo;
        
        return $this;
    }

    public function setPlainBody(string $content)
    {
        $this->attributes['plain_body'] = $content;
        
        return $this;
    }

    public function setHtmlBody(string $content)
    {
        $this->attributes['html_body'] = $content;
        
        return $this;
    }

    public function addHeader($key, $value)
    {
        $this->attributes['headers'][$key] = $value;
        
        return $this;
    }

    public function addAttachment($filename, $contentType, $data)
    {
        $attachment = [
            'name' => $filename,
            'content_type' => $contentType,
            'data' => base64_encode($data),
        ];

        $this->attributes['attachments'][] = $attachment;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'message';
    }
}
