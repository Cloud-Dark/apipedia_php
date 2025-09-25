# Apipedia PHP SDK

[![Latest Stable Version](https://poser.pugx.org/apipedia/php-sdk/v/stable)](https://packagist.org/packages/apipedia/php-sdk)
[![Total Downloads](https://poser.pugx.org/apipedia/php-sdk/downloads)](https://packagist.org/packages/apipedia/php-sdk)
[![License](https://poser.pugx.org/apipedia/php-sdk/license)](https://packagist.org/packages/apipedia/php-sdk)
[![PHP Version Require](https://poser.pugx.org/apipedia/php-sdk/require/php)](https://packagist.org/packages/apipedia/php-sdk)

A comprehensive PHP SDK for the Apipedia API, providing seamless integration for WhatsApp, Telegram, SMS, AI Chat, and more messaging services.

## Features

- 📱 **WhatsApp Messaging** - Send text, media, and files
- 🤖 **Telegram Bot Integration** - Messages, images, documents, buttons, locations
- 📨 **SMS Services** - Regular, VIP, OTP, and VVIP SMS
- 🧠 **AI Chat Integration** - Intelligent conversational AI
- 🔗 **Chainable API** - Fluent interface for complex workflows
- 📊 **Message Tracking** - Status monitoring and delivery receipts
- 👥 **Bulk Messaging** - Send to multiple recipients efficiently
- 🔄 **Cross-platform** - Send AI responses across multiple channels

## Installation

Install the package via Composer:

```bash
composer require apipedia/php-sdk
```

## Requirements

- PHP 7.4 or higher
- cURL extension
- JSON extension
- Guzzle HTTP client

## Quick Start

### Basic Usage

```php
<?php
require_once 'vendor/autoload.php';

use Apipedia\Apipedia;

// Initialize with your credentials
$apipedia = new Apipedia('your_app_key', 'your_auth_key');

// Or use the helper function
$apipedia = apipedia('your_app_key', 'your_auth_key');

// Send a WhatsApp message
$result = $apipedia->whatsapp('628123456789', 'Hello from PHP SDK!');
echo "Message sent with ID: " . $result->getResult()['message_id'];
```

## API Documentation

### WhatsApp Messaging

#### Send Text Message

```php
$apipedia->whatsapp('628123456789', 'Hello World!');
```

#### Send Message with Media

```php
// With image URL
$apipedia->whatsapp('628123456789', 'Check this image!', 'https://example.com/image.jpg');

// With local file
$apipedia->whatsapp('628123456789', 'Here is a document', '/path/to/document.pdf');
```

### Bulk Messaging

#### Bulk V1 - Same Message to Multiple Recipients

```php
$phoneNumbers = ['628123456789', '628987654321', '628555666777'];
$apipedia->bulkV1($phoneNumbers, 'Important announcement for everyone!');
```

#### Bulk V2 - Different Messages to Multiple Recipients

```php
$phoneNumbers = ['628123456789', '628987654321'];
$messages = ['Hello John!', 'Hello Jane!'];
$apipedia->bulkV2($phoneNumbers, $messages);
```

### Telegram Integration

#### Send Text Message

```php
$apipedia->telegramSendMessage('@yourchannel', 'Hello Telegram!');
```

#### Send Image

```php
$apipedia->telegramSendImage(
    '@yourchannel',
    'https://example.com/image.jpg',
    'Image caption here'
);
```

#### Send Location

```php
$apipedia->telegramSendLocation('@yourchannel', -6.2088, 106.8456);
```

#### Send Interactive Buttons

```php
$buttons = [
    [
        ['text' => 'Visit Website', 'url' => 'https://example.com'],
        ['text' => 'Contact Support', 'callback_data' => 'support']
    ],
    [
        ['text' => 'More Info', 'callback_data' => 'info']
    ]
];

$apipedia->telegramSendButtons('@yourchannel', 'Choose an option:', $buttons);
```

#### Send Document

```php
$apipedia->telegramSendDocument(
    '@yourchannel',
    'https://example.com/document.pdf',
    'Important document',
    'report.pdf'
);
```

### SMS Services

#### Regular SMS

```php
$apipedia->smsRegular('628123456789', 'Your regular SMS message');
```

#### VIP SMS

```php
$apipedia->smsVIP('628123456789', 'Priority VIP message');
```

#### OTP SMS

```php
$otpCode = rand(100000, 999999);
$apipedia->smsOTP('628123456789', "Your OTP code is: {$otpCode}");
```

#### VVIP SMS

```php
$apipedia->smsVVIP('628123456789', 'Critical VVIP message');
```

### AI Chat Integration

```php
$response = $apipedia->aiChat(
    'What is the weather like today?',
    'weather_agent',
    'text'
);

echo $response->getResult()['response'];
```

### Chainable API - Cross-Platform Messaging

One of the most powerful features of this SDK is the ability to chain API calls, allowing you to send AI-generated responses across multiple platforms:

```php
// AI generates content and sends to WhatsApp
$apipedia
    ->aiChat('Generate a daily motivation quote', 'motivation_agent')
    ->toWhatsApp('628123456789', '🌟 Daily Motivation: ');

// AI processes data and sends to multiple platforms
$apipedia
    ->aiChat('Summarize today\'s sales report', 'analytics_agent')
    ->toWhatsApp('628123456789', '📊 Sales Update: ')
    ->toTelegram('@salesteam', '📈 Team Update: ')
    ->toSMS('628987654321', 'SALES: ');
```

### Message Status and Tracking

#### Get Profile Information

```php
$profile = $apipedia->getProfile();
echo json_encode($profile->getResult());
```

#### Update Presence Status

```php
$apipedia->updatePresence('628123456789', 'typing', 5000);
```

#### Check Message Status

```php
// Get all message statuses
$result = $apipedia->getMessageStatusAll('message_id_here');
echo json_encode($result->getResult());

// Get last status
$result = $apipedia->getLastStatus('message_id_here');
echo json_encode($result->getResult());

// Get last receipt status
$result = $apipedia->getLastReceiptStatus('message_id_here');
echo json_encode($result->getResult());
```

**API Endpoints Used:**
- Get all statuses: `GET https://waconsole.apipedia.id/api/messages/status/all`
- Get last status: `GET https://waconsole.apipedia.id/api/status/last`
- Get last receipt: `GET https://waconsole.apipedia.id/api/messages/status/last/receipt`

## Advanced Examples

### Customer Support Bot

```php
// Automated customer support workflow
$customerQuery = "I need help with my order #12345";

$response = $apipedia
    ->aiChat($customerQuery, 'support_agent')
    ->toWhatsApp('628123456789', '🤖 Support: ')
    ->toTelegram('@support_logs', '📝 Query Log: ');
```

### Multi-Channel Notifications

```php
// Send order notifications across all channels
$orderUpdate = $apipedia
    ->aiChat('Generate shipping notification for order #67890', 'notification_agent');

// Send to customer via WhatsApp
$orderUpdate->toWhatsApp('628123456789', '📦 Shipping Update: ');

// Log to Telegram channel
$orderUpdate->toTelegram('@order_logs', '📋 Order #67890: ');

// Send SMS backup
$orderUpdate->toSMS('628123456789', 'SHIPPING: ');
```

### Bulk Campaign with Personalization

```php
$customers = [
    ['phone' => '628123456789', 'name' => 'John'],
    ['phone' => '628987654321', 'name' => 'Jane'],
    ['phone' => '628555666777', 'name' => 'Bob']
];

foreach ($customers as $customer) {
    $personalizedMessage = $apipedia
        ->aiChat("Generate a personalized welcome message for {$customer['name']}", 'marketing_agent');

    $personalizedMessage->toWhatsApp($customer['phone'], '🎉 Welcome ');
}
```

## Error Handling

The SDK throws `RuntimeException` for API errors and `InvalidArgumentException` for invalid parameters:

```php
try {
    $result = $apipedia->whatsapp('invalid_number', 'Test message');
} catch (InvalidArgumentException $e) {
    echo "Invalid argument: " . $e->getMessage();
} catch (RuntimeException $e) {
    echo "API error: " . $e->getMessage();
}
```

## Configuration

### Custom HTTP Client

You can customize the HTTP client behavior:

```php
use GuzzleHttp\Client;

$customClient = new Client([
    'timeout' => 60,
    'verify' => false, // Only for development
]);

$apipedia = new Apipedia('app_key', 'auth_key');
// Use reflection to set custom client if needed
```

### Environment Variables

For better security, store your credentials in environment variables:

```php
$apipedia = new Apipedia(
    $_ENV['APIPEDIA_APP_KEY'],
    $_ENV['APIPEDIA_AUTH_KEY']
);
```

## Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run code style checks
composer phpcs

# Fix code style issues
composer phpcs-fix

# Run static analysis
composer phpstan
```

## API Reference

### Method Chaining

All methods return the Apipedia instance, allowing for fluent chaining:

```php
$result = $apipedia
    ->method1()
    ->method2()
    ->method3()
    ->getResult();
```

### Available Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `whatsapp($to, $message, $media = null)` | Send WhatsApp message | `Apipedia` |
| `bulkV1($numbers, $message)` | Bulk send same message | `Apipedia` |
| `bulkV2($numbers, $messages)` | Bulk send different messages | `Apipedia` |
| `telegramSendMessage($receiver, $body)` | Send Telegram message | `Apipedia` |
| `telegramSendImage($receiver, $imageUrl, $caption = '')` | Send Telegram image | `Apipedia` |
| `telegramSendLocation($receiver, $lat, $lng)` | Send Telegram location | `Apipedia` |
| `telegramSendButtons($receiver, $body, $buttons)` | Send Telegram buttons | `Apipedia` |
| `telegramSendDocument($receiver, $docUrl, $caption = '', $filename = '')` | Send Telegram document | `Apipedia` |
| `smsRegular($to, $msg)` | Send regular SMS | `Apipedia` |
| `smsVIP($to, $msg)` | Send VIP SMS | `Apipedia` |
| `smsOTP($to, $msg)` | Send OTP SMS | `Apipedia` |
| `smsVVIP($to, $msg)` | Send VVIP SMS | `Apipedia` |
| `aiChat($message, $agentId, $format = 'text')` | AI chat interaction | `Apipedia` |
| `toWhatsApp($to, $prefix = '')` | Chain to WhatsApp | `Apipedia` |
| `toTelegram($receiver, $prefix = '')` | Chain to Telegram | `Apipedia` |
| `toSMS($to, $prefix = '')` | Chain to SMS | `Apipedia` |
| `getProfile()` | Get profile information | `Apipedia` |
| `updatePresence($receiver, $presence, $duration = null)` | Update presence | `Apipedia` |
| `getMessageStatusAll($messageId)` | Get all message statuses | `Apipedia` |
| `getLastStatus($messageId)` | Get last message status | `Apipedia` |
| `getLastReceiptStatus($messageId)` | Get last receipt status | `Apipedia` |
| `getResult()` | Get last API response | `array\|null` |

## Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Support

- 📧 Email: support@apipedia.id
- 📚 Documentation: [https://docs.apipedia.id](https://docs.apipedia.id)
- 🐛 Issues: [GitHub Issues](https://github.com/apipedia/php-sdk/issues)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes and version history.

---

Made with ❤️ by the Apipedia Team