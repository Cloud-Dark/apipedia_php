# Apipedia PHP SDK - API Documentation

## Table of Contents

- [Overview](#overview)
- [Authentication](#authentication)
- [WhatsApp API](#whatsapp-api)
- [Bulk Messaging API](#bulk-messaging-api)
- [Telegram Bot API](#telegram-bot-api)
- [SMS API](#sms-api)
- [AI Chat API](#ai-chat-api)
- [Profile API](#profile-api)
- [Presence API](#presence-api)
- [Message Status API](#message-status-api)
- [Chainable Methods](#chainable-methods)
- [Error Handling](#error-handling)
- [Response Format](#response-format)

## Overview

The Apipedia PHP SDK provides a comprehensive interface to the Apipedia API services. All methods are chainable and return the Apipedia instance, allowing for fluent API interactions.

## Authentication

All API methods require valid credentials:

```php
$apipedia = new Apipedia('your_app_key', 'your_auth_key');
```

Or using the helper function:

```php
$apipedia = apipedia('your_app_key', 'your_auth_key');
```

## WhatsApp API

### whatsapp(string $to, string $message, $media = null)

Send a WhatsApp message with optional media attachment.

**Parameters:**
- `$to` (string): Recipient phone number (e.g., '628123456789')
- `$message` (string): Text message content
- `$media` (string|resource|null): Optional media file (URL, file path, or file resource)

**Returns:** `Apipedia` instance for chaining

**Examples:**

```php
// Send text message
$result = $apipedia->whatsapp('628123456789', 'Hello World!');

// Send with image URL
$result = $apipedia->whatsapp('628123456789', 'Check this image', 'https://example.com/image.jpg');

// Send with local file
$result = $apipedia->whatsapp('628123456789', 'Document attached', '/path/to/file.pdf');

// Send with file resource
$fileResource = fopen('/path/to/file.jpg', 'r');
$result = $apipedia->whatsapp('628123456789', 'Image from resource', $fileResource);
```

**Response Format:**
```json
{
    "status": "success",
    "message_id": "wa_12345678",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

**Exceptions:**
- `InvalidArgumentException`: When file path doesn't exist
- `RuntimeException`: When API request fails

## Bulk Messaging API

### bulkV1(array $toNumbers, string $message)

Send the same message to multiple recipients.

**Parameters:**
- `$toNumbers` (array): Array of phone numbers
- `$message` (string): Message to send to all recipients

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$phoneNumbers = ['628123456789', '628987654321', '628555666777'];
$result = $apipedia->bulkV1($phoneNumbers, 'Important announcement!');
```

### bulkV2(array $toNumbers, array $messages)

Send different messages to multiple recipients.

**Parameters:**
- `$toNumbers` (array): Array of phone numbers
- `$messages` (array): Array of messages (must match the number of recipients)

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$phoneNumbers = ['628123456789', '628987654321'];
$messages = ['Hello John!', 'Hello Jane!'];
$result = $apipedia->bulkV2($phoneNumbers, $messages);
```

**Exceptions:**
- `InvalidArgumentException`: When array lengths don't match

## Telegram Bot API

### telegramSendMessage(string $receiver, string $body)

Send a text message to Telegram.

**Parameters:**
- `$receiver` (string): Telegram channel or chat ID (e.g., '@mychannel')
- `$body` (string): Message content

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$result = $apipedia->telegramSendMessage('@mychannel', 'Hello Telegram!');
```

### telegramSendImage(string $receiver, string $imageUrl, string $caption = '')

Send an image to Telegram.

**Parameters:**
- `$receiver` (string): Telegram channel or chat ID
- `$imageUrl` (string): URL of the image
- `$caption` (string): Optional image caption

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$result = $apipedia->telegramSendImage(
    '@mychannel',
    'https://example.com/image.jpg',
    'Beautiful sunset'
);
```

### telegramSendLocation(string $receiver, float $latitude, float $longitude)

Send a location to Telegram.

**Parameters:**
- `$receiver` (string): Telegram channel or chat ID
- `$latitude` (float): Latitude coordinate
- `$longitude` (float): Longitude coordinate

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$result = $apipedia->telegramSendLocation('@mychannel', -6.2088, 106.8456);
```

### telegramSendButtons(string $receiver, string $body, array $buttons)

Send a message with interactive buttons to Telegram.

**Parameters:**
- `$receiver` (string): Telegram channel or chat ID
- `$body` (string): Message content
- `$buttons` (array): 2D array of button configurations

**Button Structure:**
```php
[
    [
        ['text' => 'Button Text', 'callback_data' => 'button_data'],
        ['text' => 'URL Button', 'url' => 'https://example.com']
    ],
    [
        ['text' => 'Another Button', 'callback_data' => 'another_data']
    ]
]
```

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$buttons = [
    [
        ['text' => 'Visit Website', 'url' => 'https://example.com'],
        ['text' => 'Contact Support', 'callback_data' => 'support']
    ]
];

$result = $apipedia->telegramSendButtons('@mychannel', 'Choose an option:', $buttons);
```

### telegramSendDocument(string $receiver, string $documentUrl, string $caption = '', string $filename = '')

Send a document to Telegram.

**Parameters:**
- `$receiver` (string): Telegram channel or chat ID
- `$documentUrl` (string): URL of the document
- `$caption` (string): Optional document caption
- `$filename` (string): Optional custom filename

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$result = $apipedia->telegramSendDocument(
    '@mychannel',
    'https://example.com/report.pdf',
    'Monthly report',
    'january_report.pdf'
);
```

## SMS API

### smsRegular(string $to, string $msg)

Send a regular SMS message.

**Parameters:**
- `$to` (string): Recipient phone number
- `$msg` (string): SMS message content

**Returns:** `Apipedia` instance for chaining

### smsVIP(string $to, string $msg)

Send a VIP priority SMS message.

**Parameters:**
- `$to` (string): Recipient phone number
- `$msg` (string): SMS message content

**Returns:** `Apipedia` instance for chaining

### smsOTP(string $to, string $msg)

Send an OTP (One-Time Password) SMS message.

**Parameters:**
- `$to` (string): Recipient phone number
- `$msg` (string): OTP message content

**Returns:** `Apipedia` instance for chaining

### smsVVIP(string $to, string $msg)

Send a VVIP highest priority SMS message.

**Parameters:**
- `$to` (string): Recipient phone number
- `$msg` (string): SMS message content

**Returns:** `Apipedia` instance for chaining

**Examples:**
```php
// Regular SMS
$result = $apipedia->smsRegular('628123456789', 'Regular message');

// VIP SMS
$result = $apipedia->smsVIP('628123456789', 'Important VIP message');

// OTP SMS
$otpCode = rand(100000, 999999);
$result = $apipedia->smsOTP('628123456789', "Your OTP: {$otpCode}");

// VVIP SMS
$result = $apipedia->smsVVIP('628123456789', 'Critical VVIP message');
```

## AI Chat API

### aiChat(string $message, string $agentId, string $format = 'text')

Interact with AI chat agents.

**Parameters:**
- `$message` (string): Message to send to the AI agent
- `$agentId` (string): ID of the AI agent to use
- `$format` (string): Response format ('text', 'json', etc.)

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$response = $apipedia->aiChat(
    'What is the weather like today?',
    'weather_agent',
    'text'
);

echo $response->getResult()['response'];
```

**Response Format:**
```json
{
    "status": "success",
    "response": "The weather today is sunny with 25°C temperature.",
    "agent_id": "weather_agent",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

## Profile API

### getProfile()

Retrieve profile information for the authenticated account.

**Parameters:** None

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
$profile = $apipedia->getProfile();
$profileData = $profile->getResult();
```

## Presence API

### updatePresence(string $receiver, string $presence, ?int $duration = null)

Update presence status for a specific receiver.

**Parameters:**
- `$receiver` (string): Target phone number or chat ID
- `$presence` (string): Presence status ('typing', 'online', 'offline', etc.)
- `$duration` (int|null): Optional duration in milliseconds

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
// Show typing for 5 seconds
$result = $apipedia->updatePresence('628123456789', 'typing', 5000);

// Set online status
$result = $apipedia->updatePresence('628123456789', 'online');
```

## Message Status API

### getMessageStatusAll(string $messageId)

Get all status updates for a specific message.

**Parameters:**
- `$messageId` (string): Message ID to check

**Returns:** `Apipedia` instance for chaining

### getLastStatus(string $messageId)

Get the last status update for a specific message.

**Parameters:**
- `$messageId` (string): Message ID to check

**Returns:** `Apipedia` instance for chaining

### getLastReceiptStatus(string $messageId)

Get the last receipt status for a specific message.

**Parameters:**
- `$messageId` (string): Message ID to check

**Returns:** `Apipedia` instance for chaining

**Examples:**
```php
// Get all statuses
$statuses = $apipedia->getMessageStatusAll('msg_123456');

// Get last status
$lastStatus = $apipedia->getLastStatus('msg_123456');

// Get receipt status
$receiptStatus = $apipedia->getLastReceiptStatus('msg_123456');
```

## Chainable Methods

These methods allow you to send previous API results to other platforms:

### toWhatsApp(string $to, string $prefix = '')

Send the previous API result to WhatsApp.

**Parameters:**
- `$to` (string): Recipient phone number
- `$prefix` (string): Optional prefix to add before the result

**Returns:** `Apipedia` instance for chaining

### toTelegram(string $receiver, string $prefix = '')

Send the previous API result to Telegram.

**Parameters:**
- `$receiver` (string): Telegram channel or chat ID
- `$prefix` (string): Optional prefix to add before the result

**Returns:** `Apipedia` instance for chaining

### toSMS(string $to, string $prefix = '')

Send the previous API result via SMS.

**Parameters:**
- `$to` (string): Recipient phone number
- `$prefix` (string): Optional prefix to add before the result

**Returns:** `Apipedia` instance for chaining

**Example:**
```php
// AI generates content and sends to multiple platforms
$apipedia
    ->aiChat('Generate daily news summary', 'news_agent')
    ->toWhatsApp('628123456789', '📰 Daily News: ')
    ->toTelegram('@news_channel', '📰 ')
    ->toSMS('628123456789', 'NEWS: ');
```

**Exceptions:**
- `RuntimeException`: When called without a previous result

## Utility Methods

### getResult()

Get the result of the last API call.

**Parameters:** None

**Returns:** `array|null` - The last API response or null if no previous call

**Example:**
```php
$result = $apipedia->whatsapp('628123456789', 'Hello');
$response = $result->getResult();

echo $response['message_id']; // wa_12345678
```

## Error Handling

The SDK uses two main exception types:

### InvalidArgumentException

Thrown for invalid method parameters:
- Empty credentials
- Non-existent file paths
- Mismatched array lengths in bulk methods

### RuntimeException

Thrown for API-related errors:
- Network connectivity issues
- API authentication failures
- Server errors
- Invalid API responses

**Example:**
```php
try {
    $result = $apipedia->whatsapp('invalid_number', 'Test');
} catch (InvalidArgumentException $e) {
    echo "Parameter error: " . $e->getMessage();
} catch (RuntimeException $e) {
    echo "API error: " . $e->getMessage();
}
```

## Response Format

All successful API responses follow this general structure:

```json
{
    "status": "success",
    "data": { ... },
    "message_id": "unique_identifier",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

Error responses:

```json
{
    "status": "error",
    "message": "Error description",
    "error_code": "ERROR_CODE",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

## Rate Limiting

The API may implement rate limiting. The SDK will throw a `RuntimeException` if rate limits are exceeded. Implement appropriate retry logic in your application:

```php
use GuzzleHttp\Exception\TooManyRedirectsException;

function sendWithRetry($apipedia, $to, $message, $maxRetries = 3) {
    for ($i = 0; $i < $maxRetries; $i++) {
        try {
            return $apipedia->whatsapp($to, $message);
        } catch (RuntimeException $e) {
            if (strpos($e->getMessage(), 'rate limit') !== false && $i < $maxRetries - 1) {
                sleep(pow(2, $i)); // Exponential backoff
                continue;
            }
            throw $e;
        }
    }
}
```

## Best Practices

1. **Error Handling**: Always wrap API calls in try-catch blocks
2. **Credentials**: Store API credentials securely using environment variables
3. **File Handling**: Close file resources after use when passing file streams
4. **Rate Limiting**: Implement retry logic with exponential backoff
5. **Logging**: Log API responses for debugging and monitoring
6. **Validation**: Validate phone numbers and other inputs before API calls
7. **Chaining**: Use method chaining for complex workflows
8. **Testing**: Use the provided test suite as examples for your implementation