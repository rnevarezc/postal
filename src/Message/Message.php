<?php

declare(strict_types=1);

namespace Postal\Message;

use Postal\Client;

class Message implements MessageInterface
{
    use MessageTrait;

    public function __construct(array $attributes = [])
    {
        $this->attributes['to'] = [];
        $this->attributes['cc'] = [];
        $this->attributes['bcc'] = [];
        $this->attributes['headers'] = null;
        $this->attributes['attachments'] = [];
    }

    public function setTo(array $addresses)
    {
        $this->attributes['to'] = $addresses;
    }

    public function addTo($address)
    {
        $this->attributes['to'][] = $address;
    }

    public function addCc($address)
    {
        $this->attributes['cc'][] = $address;
    }

    public function addBcc($address)
    {
        $this->attributes['bcc'][] = $address;
    }

    public function setFrom($address)
    {
        $this->attributes['from'] = $address;
    }

    public function setSender($address)
    {
        $this->attributes['sender'] = $address;
    }

    public function setSubject($subject)
    {
        $this->attributes['subject'] = $subject;
    }

    public function tag($tag)
    {
        $this->attributes['tag'] = $tag;
    }

    public function setReplyTo($replyTo)
    {
        $this->attributes['reply_to'] = $replyTo;
    }

    public function setPlainBody($content)
    {
        $this->attributes['plain_body'] = $content;
    }

    public function setHtmlBody($content)
    {
        $this->attributes['html_body'] = $content;
    }

    public function addHeader($key, $value)
    {
        $this->attributes['headers'][$key] = $value;
    }

    public function addAttachment($filename, $contentType, $data)
    {
        $attachment = [
            'name' => $filename,
            'content_type' => $contentType,
            'data' => base64_encode($data),
        ];

        $this->attributes['attachments'][] = $attachment;
    }
}
