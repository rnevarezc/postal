<?php

declare(strict_types=1);

namespace Postal\Mail;

use Postal\Mail\Concerns\{HasAttributes, HasMessages, HasResponse};

class Mail implements Mailable
{
    use HasResponse;
    use HasAttributes;
    use HasMessages;

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

    /**
     * Default Constructor
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {  
        $this->setAttributes($attributes);
    }

    /**
     * Set The e-mail addresses of the recipients
     *
     * @param array $addresses
     * @return self
     */
    public function setTo(array $addresses)
    {
        $this->attributes['to'] = $addresses;

        return $this;
    }

    /**
     * Add "to" recipient(s)
     *
     * @param array|string $address
     * @return self
     */
    public function addTo($address)
    {
        return $this->addRecipients($address, 'to');
    }

    /**
     * Add "cc" recipient(s)
     *
     * @param array|string $address
     * @return self
     */
    public function addCc($address)
    {
        return $this->addRecipients($address, 'cc');
    }

    /**
     * Add "bcc" recipient(s)
     *
     * @param array|string $address
     * @return self
     */
    public function addBcc($address)
    {
        return $this->addRecipients($address, 'bcc');
    }

    /**
     * Set the email address for the From header
     *
     * @param string $address
     * @return self
     */
    public function setFrom(string $address)
    {
        $this->attributes['from'] = $address;

        return $this;
    }

    /**
     * Set the email address for the Sender header
     *
     * @param string $address
     * @return self
     */
    public function setSender(string $address)
    {
        $this->attributes['sender'] = $address;

        return $this;
    }

    /**
     * Set the subject of the e-mail
     *
     * @param string $subject
     * @return self
     */
    public function setSubject(string $subject)
    {
        $this->attributes['subject'] = $subject;

        return $this;
    }

    /**
     * Set the tag of the e-mail
     *
     * @param string $tag
     * @return self
     */
    public function setTag($tag)
    {
        $this->attributes['tag'] = $tag;

        return $this;
    }

    /**
     * Set the reply-to address for the mail
     *
     * @param string $replyTo
     * @return self
     */
    public function setReplyTo(string $replyTo)
    {
        $this->attributes['reply_to'] = $replyTo;
        
        return $this;
    }

    /**
     * Set The plain text body of the e-mail
     *
     * @param string $content
     * @return self
     */
    public function setPlainBody(string $content)
    {
        $this->attributes['plain_body'] = $content;
        
        return $this;
    }

    /**
     * The HTML body of the e-mail
     *
     * @param string $content
     * @return self
     */
    public function setHtmlBody(string $content)
    {
        $this->attributes['html_body'] = $content;
        
        return $this;
    }

    /**
     * Add additional header
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function addHeader(string $key, string $value)
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
