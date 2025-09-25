<?php

namespace Apipedia\Tests;

use Apipedia\Apipedia;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ExampleUsageTest extends TestCase
{
    private Apipedia $apipedia;
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);

        $this->apipedia = new Apipedia('test_appkey', 'test_authkey');

        // Use reflection to set the mock HTTP client
        $reflection = new \ReflectionClass($this->apipedia);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($this->apipedia, $httpClient);
    }

    public function testBasicWhatsAppUsage(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'message_id' => 'wa_12345'
        ])));

        $result = $this->apipedia->whatsapp('628123456789', 'Hello from PHP SDK!');

        $this->assertEquals('success', $result->getResult()['status']);
        $this->assertEquals('wa_12345', $result->getResult()['message_id']);
    }

    public function testBulkMessagingWorkflow(): void
    {
        // Mock response for bulk messaging
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'sent_count' => 3
        ])));

        $phoneNumbers = ['628123456789', '628987654321', '628555666777'];
        $result = $this->apipedia->bulkV1($phoneNumbers, 'Important announcement for everyone!');

        $this->assertEquals('success', $result->getResult()['status']);
        $this->assertEquals(3, $result->getResult()['sent_count']);
    }

    public function testAiChatWithChaining(): void
    {
        // Mock AI response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'response' => 'The weather today is sunny with 25°C temperature.'
        ])));

        // Mock WhatsApp response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'message_id' => 'wa_67890'
        ])));

        $result = $this->apipedia
            ->aiChat('What is the weather like today?', 'weather_agent')
            ->toWhatsApp('628123456789', 'Weather Update: ');

        $this->assertEquals('success', $result->getResult()['status']);
        $this->assertEquals('wa_67890', $result->getResult()['message_id']);
    }

    public function testTelegramBotWorkflow(): void
    {
        // Mock Telegram image response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'message_id' => 'tg_54321'
        ])));

        $result = $this->apipedia->telegramSendImage(
            '@mychannel',
            'https://example.com/chart.png',
            'Daily sales report'
        );

        $this->assertEquals('success', $result->getResult()['status']);
        $this->assertEquals('tg_54321', $result->getResult()['message_id']);
    }

    public function testSmsOtpWorkflow(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'sms_id' => 'sms_98765'
        ])));

        $otpCode = rand(100000, 999999);
        $result = $this->apipedia->smsOTP(
            '628123456789',
            "Your OTP verification code is: {$otpCode}. Valid for 5 minutes."
        );

        $this->assertEquals('success', $result->getResult()['status']);
        $this->assertEquals('sms_98765', $result->getResult()['sms_id']);
    }

    public function testMultiPlatformNotification(): void
    {
        // Mock AI response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => ['text' => 'Your order #12345 has been shipped and will arrive tomorrow.']
        ])));

        // Mock WhatsApp response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'message_id' => 'wa_111'
        ])));

        // Mock Telegram response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'message_id' => 'tg_222'
        ])));

        // Mock SMS response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'sms_id' => 'sms_333'
        ])));

        // AI generates the notification message
        $aiResult = $this->apipedia->aiChat('Generate shipping notification for order 12345', 'notification_agent');

        // Send to WhatsApp
        $whatsappResult = $this->apipedia->toWhatsApp('628123456789', '📦 ');
        $this->assertEquals('wa_111', $whatsappResult->getResult()['message_id']);

        // Send to Telegram
        $telegramResult = $this->apipedia->toTelegram('@notifications', '📦 ');
        $this->assertEquals('tg_222', $telegramResult->getResult()['message_id']);

        // Send to SMS
        $smsResult = $this->apipedia->toSMS('628123456789', 'SHIPPING: ');
        $this->assertEquals('sms_333', $smsResult->getResult()['sms_id']);
    }

    public function testPresenceAndStatusTracking(): void
    {
        // Mock presence update
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success'
        ])));

        // Mock message send
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'message_id' => 'wa_tracking'
        ])));

        // Mock status check
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'last_status' => 'read'
        ])));

        // Update presence to show typing
        $presenceResult = $this->apipedia->updatePresence('628123456789', 'typing', 3000);
        $this->assertEquals('success', $presenceResult->getResult()['status']);

        // Send message
        $messageResult = $this->apipedia->whatsapp('628123456789', 'Hello! How can I help you today?');
        $messageId = $messageResult->getResult()['message_id'];

        // Check message status
        $statusResult = $this->apipedia->getLastStatus($messageId);
        $this->assertEquals('read', $statusResult->getResult()['last_status']);
    }

    public function testErrorHandlingExample(): void
    {
        // This test demonstrates how errors should be handled
        // We'll test with a 400 error response
        $this->mockHandler->append(new Response(400, [], json_encode([
            'error' => 'Invalid phone number format'
        ])));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/WhatsApp API Error:/');

        $this->apipedia->whatsapp('invalid_number', 'Test message');
    }
}
