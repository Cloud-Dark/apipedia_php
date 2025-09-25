<?php

namespace Apipedia\Tests;

use Apipedia\Apipedia;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ApipediaTest extends TestCase
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

    public function testConstructorWithValidCredentials(): void
    {
        $apipedia = new Apipedia('test_appkey', 'test_authkey');
        $this->assertInstanceOf(Apipedia::class, $apipedia);
    }

    public function testConstructorWithEmptyAppkey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('App key and Auth key are required');

        new Apipedia('', 'test_authkey');
    }

    public function testConstructorWithEmptyAuthkey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('App key and Auth key are required');

        new Apipedia('test_appkey', '');
    }

    public function testWhatsAppMessageSuccess(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => '123456'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->whatsapp('628123456789', 'Hello World');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testWhatsAppWithMediaURL(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => '123456'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->whatsapp('628123456789', 'Check this image', 'https://example.com/image.jpg');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testWhatsAppWithInvalidFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File does not exist: /invalid/path.jpg');

        $this->apipedia->whatsapp('628123456789', 'Hello', '/invalid/path.jpg');
    }

    public function testBulkV1Success(): void
    {
        $expectedResponse = ['status' => 'success', 'sent_count' => 2];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->bulkV1(['628123456789', '628987654321'], 'Hello Everyone');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testBulkV2Success(): void
    {
        $expectedResponse = ['status' => 'success', 'sent_count' => 2];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $numbers = ['628123456789', '628987654321'];
        $messages = ['Hello John', 'Hello Jane'];
        $result = $this->apipedia->bulkV2($numbers, $messages);

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testBulkV2MismatchedArrays(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of recipients must match number of messages');

        $numbers = ['628123456789', '628987654321'];
        $messages = ['Hello John']; // Only one message for two numbers
        $this->apipedia->bulkV2($numbers, $messages);
    }

    public function testTelegramSendMessage(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => 'tg123'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->telegramSendMessage('@testchannel', 'Hello Telegram');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testTelegramSendImage(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => 'tg124'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->telegramSendImage(
            '@testchannel',
            'https://example.com/image.jpg',
            'Check this image'
        );

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testTelegramSendLocation(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => 'tg125'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->telegramSendLocation('@testchannel', -6.2088, 106.8456);

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testTelegramSendButtons(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => 'tg126'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $buttons = [
            [
                ['text' => 'Button 1', 'callback_data' => 'btn1'],
                ['text' => 'Button 2', 'url' => 'https://example.com']
            ]
        ];

        $result = $this->apipedia->telegramSendButtons('@testchannel', 'Choose an option:', $buttons);

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testTelegramSendDocument(): void
    {
        $expectedResponse = ['status' => 'success', 'message_id' => 'tg127'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->telegramSendDocument(
            '@testchannel',
            'https://example.com/document.pdf',
            'Check this document',
            'mydocument.pdf'
        );

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testSmsRegular(): void
    {
        $expectedResponse = ['status' => 'success', 'sms_id' => 'sms123'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->smsRegular('628123456789', 'Hello via SMS');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testSmsVIP(): void
    {
        $expectedResponse = ['status' => 'success', 'sms_id' => 'sms124'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->smsVIP('628123456789', 'VIP SMS message');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testSmsOTP(): void
    {
        $expectedResponse = ['status' => 'success', 'sms_id' => 'sms125'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->smsOTP('628123456789', 'Your OTP is: 123456');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testSmsVVIP(): void
    {
        $expectedResponse = ['status' => 'success', 'sms_id' => 'sms126'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->smsVVIP('628123456789', 'VVIP SMS message');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testAiChat(): void
    {
        $expectedResponse = ['status' => 'success', 'response' => 'AI response here'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->aiChat('Hello AI', 'agent_123', 'text');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testChainableToWhatsApp(): void
    {
        // First call: AI Chat
        $aiResponse = ['status' => 'success', 'response' => 'AI response here'];
        $this->mockHandler->append(new Response(200, [], json_encode($aiResponse)));

        // Second call: WhatsApp
        $whatsappResponse = ['status' => 'success', 'message_id' => '789'];
        $this->mockHandler->append(new Response(200, [], json_encode($whatsappResponse)));

        $result = $this->apipedia
            ->aiChat('Hello AI', 'agent_123')
            ->toWhatsApp('628123456789', 'AI says: ');

        $this->assertEquals($whatsappResponse, $result->getResult());
    }

    public function testChainableToTelegram(): void
    {
        // First call: AI Chat
        $aiResponse = ['status' => 'success', 'data' => ['text' => 'AI response text']];
        $this->mockHandler->append(new Response(200, [], json_encode($aiResponse)));

        // Second call: Telegram
        $telegramResponse = ['status' => 'success', 'message_id' => 'tg789'];
        $this->mockHandler->append(new Response(200, [], json_encode($telegramResponse)));

        $result = $this->apipedia
            ->aiChat('Hello AI', 'agent_123')
            ->toTelegram('@testchannel', 'Bot: ');

        $this->assertEquals($telegramResponse, $result->getResult());
    }

    public function testChainableToSMS(): void
    {
        // First call: AI Chat
        $aiResponse = ['status' => 'success', 'message' => 'AI message here'];
        $this->mockHandler->append(new Response(200, [], json_encode($aiResponse)));

        // Second call: SMS
        $smsResponse = ['status' => 'success', 'sms_id' => 'sms789'];
        $this->mockHandler->append(new Response(200, [], json_encode($smsResponse)));

        $result = $this->apipedia
            ->aiChat('Hello AI', 'agent_123')
            ->toSMS('628123456789', 'Auto: ');

        $this->assertEquals($smsResponse, $result->getResult());
    }

    public function testToWhatsAppWithoutPreviousResult(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No previous result to send');

        $this->apipedia->toWhatsApp('628123456789');
    }

    public function testGetProfile(): void
    {
        $expectedResponse = ['status' => 'success', 'profile' => ['name' => 'Test Profile']];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->getProfile();

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testUpdatePresence(): void
    {
        $expectedResponse = ['status' => 'success'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->updatePresence('628123456789', 'typing', 5000);

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testGetMessageStatusAll(): void
    {
        $expectedResponse = ['status' => 'success', 'statuses' => []];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->getMessageStatusAll('msg123');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testGetLastStatus(): void
    {
        $expectedResponse = ['status' => 'success', 'last_status' => 'delivered'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->getLastStatus('msg123');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testGetLastReceiptStatus(): void
    {
        $expectedResponse = ['status' => 'success', 'receipt_status' => 'read'];
        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse)));

        $result = $this->apipedia->getLastReceiptStatus('msg123');

        $this->assertInstanceOf(Apipedia::class, $result);
        $this->assertEquals($expectedResponse, $result->getResult());
    }

    public function testHelperFunction(): void
    {
        $instance = apipedia('test_app', 'test_auth');
        $this->assertInstanceOf(Apipedia::class, $instance);
    }

    public function testExtractResponseTextVariousFormats(): void
    {
        $reflection = new \ReflectionClass($this->apipedia);
        $method = $reflection->getMethod('extractResponseText');
        $method->setAccessible(true);

        // Test with 'text' field
        $result1 = $method->invoke($this->apipedia, ['text' => 'Hello World']);
        $this->assertEquals('Hello World', $result1);

        // Test with 'message' field
        $result2 = $method->invoke($this->apipedia, ['message' => 'Test Message']);
        $this->assertEquals('Test Message', $result2);

        // Test with 'data' string
        $result3 = $method->invoke($this->apipedia, ['data' => 'Data String']);
        $this->assertEquals('Data String', $result3);

        // Test with 'data' array containing 'text'
        $result4 = $method->invoke($this->apipedia, ['data' => ['text' => 'Nested Text']]);
        $this->assertEquals('Nested Text', $result4);

        // Test with 'data' array containing 'response'
        $result5 = $method->invoke($this->apipedia, ['data' => ['response' => 'Nested Response']]);
        $this->assertEquals('Nested Response', $result5);

        // Test with 'response' field
        $result6 = $method->invoke($this->apipedia, ['response' => 'Direct Response']);
        $this->assertEquals('Direct Response', $result6);

        // Test with unknown format (should return JSON)
        $result7 = $method->invoke($this->apipedia, ['unknown' => 'field']);
        $this->assertEquals('{"unknown":"field"}', $result7);
    }
}
