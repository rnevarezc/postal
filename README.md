# Postal API Client

This library helps you use the [Postal](https://github.com/atech/postal) API in PHP 7.2 and above to send Emails, get Message Details & Deliveries and Implement Message Events.

Uses [GuzzleHTTP](https://github.com/guzzle/guzzle) client and PSR-7: [HTTP-PSR7 interfaces](https://www.php-fig.org/psr/psr-7/).

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

// Optional: You can add any aditional Headers for your API installation (Maybe Authentication)
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
    'to' => [
        'recipient@mail.com',
        'rafael@example.com'
    ],
    'cc' => 'me@github.com',
    'from' => 'me@mail.com',
    'subject' => 'A Postal Email$mail!',
    'plain_body' => 'This is a test of new Postal Client'
]);

// Or Create a new mail message and manually each of the Mail attributes
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

// This is a instance of \Postal\Response\Response;
$response = $client->send($mail);

// If you'd like to capture the Messages Dispatched for each recipient of the Mail:
// You can do it like so:

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

// Additionaly you can specify individual _expansions for the Message Detail, or true to retreive them all:
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

To-do: Complete Documentation.


## API Information

You can get more information about the Postal API and Payloads in the [Postal Project Wiki](https://github.com/postalhq/postal/wiki/Using-the-API)
