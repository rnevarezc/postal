# Postal API Client

This library helps you use the [Postal](https://github.com/atech/postal) API in PHP 7.2 (and above) to send Emails, get Message Details & Deliveries and Implement Events to handle Server Webhooks.

It uses [GuzzleHTTP](https://github.com/guzzle/guzzle) client and PSR-7: [HTTP-PSR7 interfaces](https://www.php-fig.org/psr/psr-7/).

## Installation

Install the library using [Composer](https://getcomposer.org/):

```bash
composer require rnevarezc/postal
```

## Usage

### Using the Client

You will need an API Credential from your Postal Installation to use the API Client.

```php
// Create a new Postal client using the server key of your Postal Installation.
$client = new \Postal\Client('https://postal.yourdomain.com', 'your-api-key');

// Optional: You can add any aditional Headers for your API installation (Maybe Authorization)
// You just add an array of headers:
$headers = [
    'Authorization' => 'Basic RTYtaO54BGBtcG9yYWwyMDIw'
];

$client = new \Postal\Client('https://postal.yourdomain.com', 'your-api-key', $headers);

// Or you can add them manually to a Client Instance:
$client->addHeaders($headers);
```

### Sending an Email

Sending an email is simple. You can follow the example below:

```php
// Create a new Mail message with a Hash of attributes:
$mail = new \Postal\Mail\Mail([
    'to' => ['recipient@mail.com', 'rafael@example.com'],
    'cc' => 'me@github.com',
    'from' => 'me@mail.com',
    'subject' => 'A Postal Email!',
    'plain_body' => 'This is a test of new Postal Client',
    'html_body' => '<p>This is a HTML body!</p>'
]);
```

Or Create a new mail message and add manually each of the Mail attributes

```php
$mail = new \Postal\Mail\Mail;

$mail->addTo('recipient@example.com');

// You can Add TO, CC, BCC recipients using strings or arrays:
$mail->addTo(['john@example.com', 'testing@example.com']);
$mail->addCc('test@example.com');

// Add any custom headers
$mail->addHeader('X-PHP-Test', 'value');

// Set the Subject, Plain Body, HTML Body, from, and sender manually:
$mail->setSubject('My new subject');
$mail->setPlainBody('This is a new text');
$mail->setHtmlBody('<p>This is a new text</p>');

// Finally, when you are ready, send the Message using the client.
// You can capture the API Response if you like.
$response = $client->send($mail);
// This is a instance of \Postal\Response\Response;
```

If you'd like to capture the Messages Dispatched for each recipient of the Mail.
You can do it like so:

```php
//This will return a Hash of 'recipient' => \Postal\Message\Message Instance
$messages = $mail->getMessages();

foreach ($messages as $recipient => $message){
    $recipient = $recipient; //recipient@mail.com
    $id = $message->getId(); // Ex.: 653621
    $token = $message->getToken() // abcdef123
}
```

### Getting Message Details

You can get the Details of a Message using the Client. You just need the Message ID

```php
// Get the Details of the Message: 653621 of the previous example:
$response = $client->getMessageDetails(653621);

// Then use the details:
$data = $response->getData(); // This is an array of the Message Details.

// Additionaly you can specify individual _expansions for the Message Detail,
// or use "true" to retreive them all:
$response = $client->getMessageDetails(653621, ['status', 'details', 'inspection']);

// OR:
$response = $client->getMessageDetails(653621, true);
// This will get all the expansions provided by the Postal API.
```

### Getting Message Deliveries

You can get the Deliveries of a Message using the Client. You just need the Message ID

```php
// Get the Details of the Message: 653621 of the previous example:
$response = $client->getMessageDeliveries(653621);
// This will return a \Postal\Response\Response instance

// Then use the deliveries structure:
$data = $response->getData(); // This is an array of the Message Deliveries
```

## Events and Webhooks!

This library provides support for all the type of Payloads that a Postal Installation can send via Webhooks.

You must configure your postal Installation to send those events to an URL of your choice to be able to use this library.

You can get more information about Webhooks and Payloads in the [Postal Documentation](https://github.com/postalhq/postal/wiki/Webhook-Events-&-Payloads)

### Message Status Events, Bounces & Click Events

Message Status events all receive the same payload (with different data) based on the status of a message.

- ```MessageSent``` - when a message is successfully delivered
- ```MessageDelayed``` - when a message's delivery has been delayed.
- ```MessageDeliveryFailed``` - when a message cannot be delivered.
- ```MessageHeld``` - when a message is held.


- If a message bounces, you'll receive the ```MessageBounced``` event.

- If you have click tracking enabled, the ```MessageLinkClicked``` event will tell you that a user has clicked on a link in one of your e-mails.

The ```Postal\Events\Message\Events``` Class is a Factory that can Parse a Payload of any of these events and build the correct Event Implementation.

Capturing these Events in an Handler (or a Controller) is very easy. You could define a Class in your app and capture the request like this:

```php
...
use Postal\Events\Message\Events;
use Postal\Events\Message\MessageEvent;
use Psr\Http\Message\RequestInterface;

class MessageEventController extends Controller
{
    /**
     * Handle Message Event Webhooks
     *
     * Postal sends the events via a POST method.
     *
     * @param Request $request
     * ...
     */
    public function post(RequestInterface $request) {

       // Here we capture the Event payload provided in the PSR-7
       // Request and parse it into an Event using the Message Events Factory.

       // @var \Postal\Events\Message\MessageEvent
       $event = Events::fromRequest($request);

       // ... Do some Stuff...
    }
}
...
```
If you capture the POST payload in any other way you could send that payload to the Factory directly to build an Event:

```PHP
$event = Events::fromPayload($array);
```

If a invalid Payload is provided, an ```\Postal\Exceptions\InvalidEventPayloadException``` is thrown

#### Using the ```MessageEvent```.

Depending on the Event Type you get an Concrete Implementation of the ```MessageEvent Interface```. The defined types (and their clases) are the same provided by Postal:

```php
...
interface MessageEvent extends Event
{
    const SENT = 'Sent';
    const DELAYED = 'Delayed';
    const DELIVERY_FAILED = 'DeliveryFailed';
    const HELD = 'Held';
    const BOUNCED = 'Bounced';
    const CLICKED = 'LinkClicked';
    ...
}
```
- ```getMessage()``` : Returns an instance of the associated ```\Postal\Message\Message``` associated to the event.
- ```getType()``` : Returns the type of the Event.
- ```toArray()``` : Returns an array representation of the Event.


MessageBounced Event:
- ```getBounce()```: Returns an instance of ```\Postal\Message\Message``` with the Bounced Message.

### Server Events

If you'd like to capture a ```DomainDNSError``` event you can use that specific Event Class:

```php
$event = DomainDNSError::fromPayload($array);
```

The same way, if you'd like to capture any kind of SendLimit Event, you can do it via the Static Methods defined in the Factory Class:

```php
$event = SendLimitApproaching::fromPayload($array);
```

or

```php
$event = SendLimitExceeded::fromPayload($array);
```

The $event variable will have the correct Event depending on the Payload.

It is strongly recommended to use different Handlers (or Controllers) by type of Event and don't use a Common webhook URL to handle them all!


## API Information

You can get more information about the Postal API and Payloads in the [Postal Project Wiki](https://github.com/postalhq/postal/wiki/Using-the-API)
