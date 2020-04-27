<?php

declare(strict_types=1);

namespace Postal\Mail\Concerns;

use Postal\Message\Message;

trait HasMessages
{
    /**
     * Server Message ID
     *
     * @var string
     */
    protected $messageId;

    /**
     * A Hash of Individual Messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Set the messages from a array of data.
     *
     * @param array $data
     * @return void
     */
    public function setMessages(array $data)
    {
        $this->messageId = $data['message_id'];

        $this->messages = $data['messages'];

        foreach($this->messages as $recipient => &$data){
            $data = Message::fromData($data);
        }
    }

    /**
     * Utility function to set the messages data provided by a response.
     *
     * @param array $data
     * @return void
     */
    protected function setResponseData(array $data)
    {
        $this->setMessages($data);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}