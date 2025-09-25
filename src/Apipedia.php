<?php

namespace Apipedia;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\MultipartStream;
use InvalidArgumentException;
use RuntimeException;

class Apipedia
{
    private string $appkey;
    private string $authkey;
    private string $baseURL = 'https://waconsole.apipedia.id/api/create-message';
    private string $bulkV1URL = 'https://waconsole.apipedia.id/api/bulk-messagev1';
    private string $bulkV2URL = 'https://waconsole.apipedia.id/api/bulk-messagev2';
    private string $telegramBaseURL = 'https://waconsole.apipedia.id/api/telegram';
    private string $smsBaseURL = 'https://waconsole.apipedia.id/api/sms';
    private string $aiBaseURL = 'https://waconsole.apipedia.id/api/chat-ai';
    private string $profileBaseURL = 'https://waconsole.apipedia.id/api/profile';
    private string $presenceBaseURL = 'https://waconsole.apipedia.id/api/presence';
    private string $messagesBaseURL = 'https://waconsole.apipedia.id/api/messages';
    private string $statusBaseURL = 'https://waconsole.apipedia.id/api/status';

    private Client $httpClient;
    /** @var array<string, mixed>|null */
    private ?array $result = null;

    public function __construct(string $appkey, string $authkey)
    {
        if (empty($appkey) || empty($authkey)) {
            throw new InvalidArgumentException('App key and Auth key are required');
        }

        $this->appkey = $appkey;
        $this->authkey = $authkey;
        $this->httpClient = new Client([
            'timeout' => 30,
            'verify' => true,
        ]);
    }

    /**
     * @param string $to
     * @param string $message
     * @param string|resource|null $media
     * @return self
     */
    public function whatsapp(string $to, string $message, $media = null): self
    {
        $multipart = [
            ['name' => 'appkey', 'contents' => $this->appkey],
            ['name' => 'authkey', 'contents' => $this->authkey],
            ['name' => 'to', 'contents' => $to],
            ['name' => 'message', 'contents' => $message],
        ];

        if ($media !== null) {
            if (is_string($media)) {
                if (filter_var($media, FILTER_VALIDATE_URL)) {
                    $multipart[] = ['name' => 'file', 'contents' => $media];
                } elseif (file_exists($media)) {
                    $multipart[] = [
                        'name' => 'file',
                        'contents' => fopen($media, 'r'),
                        'filename' => basename($media)
                    ];
                } else {
                    throw new InvalidArgumentException("File does not exist: {$media}");
                }
            } elseif (is_resource($media)) {
                $multipart[] = ['name' => 'file', 'contents' => $media];
            }
        }

        try {
            $response = $this->httpClient->post($this->baseURL, [
                'multipart' => $multipart
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("WhatsApp API Error: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * @param array<string> $toNumbers
     * @param string $message
     * @return self
     */
    public function bulkV1(array $toNumbers, string $message): self
    {
        $to = implode('|', $toNumbers);

        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'to' => $to,
            'message' => $message
        ];

        try {
            $response = $this->httpClient->post($this->bulkV1URL, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Bulk V1 API Error: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * @param array<string> $toNumbers
     * @param array<string> $messages
     * @return self
     */
    public function bulkV2(array $toNumbers, array $messages): self
    {
        if (count($toNumbers) !== count($messages)) {
            throw new InvalidArgumentException('Number of recipients must match number of messages');
        }

        $to = implode('|', $toNumbers);
        $message = implode('|', $messages);

        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'to' => $to,
            'message' => $message
        ];

        try {
            $response = $this->httpClient->post($this->bulkV2URL, [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Bulk V2 API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function telegramSendMessage(string $receiver, string $body): self
    {
        $multipart = [
            ['name' => 'appkey', 'contents' => $this->appkey],
            ['name' => 'authkey', 'contents' => $this->authkey],
            ['name' => 'receiver', 'contents' => $receiver],
            ['name' => 'body', 'contents' => $body],
        ];

        try {
            $response = $this->httpClient->post("{$this->telegramBaseURL}/send_message", [
                'multipart' => $multipart
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Telegram Send Message API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function telegramSendImage(string $receiver, string $imageUrl, string $caption = ''): self
    {
        $multipart = [
            ['name' => 'appkey', 'contents' => $this->appkey],
            ['name' => 'authkey', 'contents' => $this->authkey],
            ['name' => 'receiver', 'contents' => $receiver],
            ['name' => 'image_url', 'contents' => $imageUrl],
        ];

        if (!empty($caption)) {
            $multipart[] = ['name' => 'caption', 'contents' => $caption];
        }

        try {
            $response = $this->httpClient->post("{$this->telegramBaseURL}/send_image", [
                'multipart' => $multipart
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Telegram Send Image API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function telegramSendLocation(string $receiver, float $latitude, float $longitude): self
    {
        $multipart = [
            ['name' => 'appkey', 'contents' => $this->appkey],
            ['name' => 'authkey', 'contents' => $this->authkey],
            ['name' => 'receiver', 'contents' => $receiver],
            ['name' => 'latitude', 'contents' => (string) $latitude],
            ['name' => 'longitude', 'contents' => (string) $longitude],
        ];

        try {
            $response = $this->httpClient->post("{$this->telegramBaseURL}/send_location", [
                'multipart' => $multipart
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Telegram Send Location API Error: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * @param string $receiver
     * @param string $body
     * @param array<array<array<string, string>>> $buttons
     * @return self
     */
    public function telegramSendButtons(string $receiver, string $body, array $buttons): self
    {
        $multipart = [
            ['name' => 'appkey', 'contents' => $this->appkey],
            ['name' => 'authkey', 'contents' => $this->authkey],
            ['name' => 'receiver', 'contents' => $receiver],
            ['name' => 'body', 'contents' => $body],
        ];

        foreach ($buttons as $i => $buttonRow) {
            foreach ($buttonRow as $j => $button) {
                $multipart[] = ['name' => "buttons[{$i}][{$j}][text]", 'contents' => $button['text']];

                if (isset($button['callback_data'])) {
                    $multipart[] = [
                        'name' => "buttons[{$i}][{$j}][callback_data]",
                        'contents' => $button['callback_data']
                    ];
                }

                if (isset($button['url'])) {
                    $multipart[] = ['name' => "buttons[{$i}][{$j}][url]", 'contents' => $button['url']];
                }
            }
        }

        try {
            $response = $this->httpClient->post("{$this->telegramBaseURL}/send_buttons", [
                'multipart' => $multipart
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException(
                "Telegram Send Buttons API Error: {$e->getMessage()}",
                0,
                $e
            );
        }
    }

    public function telegramSendDocument(
        string $receiver,
        string $documentUrl,
        string $caption = '',
        string $filename = ''
    ): self {
        $multipart = [
            ['name' => 'appkey', 'contents' => $this->appkey],
            ['name' => 'authkey', 'contents' => $this->authkey],
            ['name' => 'receiver', 'contents' => $receiver],
            ['name' => 'document_url', 'contents' => $documentUrl],
        ];

        if (!empty($caption)) {
            $multipart[] = ['name' => 'caption', 'contents' => $caption];
        }

        if (!empty($filename)) {
            $multipart[] = ['name' => 'filename', 'contents' => $filename];
        }

        try {
            $response = $this->httpClient->post("{$this->telegramBaseURL}/send_document", [
                'multipart' => $multipart
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Telegram Send Document API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function smsRegular(string $to, string $msg): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'to' => $to,
            'msg' => $msg
        ];

        try {
            $response = $this->httpClient->post("{$this->smsBaseURL}/send-reguler", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("SMS Regular API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function smsVIP(string $to, string $msg): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'to' => $to,
            'msg' => $msg
        ];

        try {
            $response = $this->httpClient->post("{$this->smsBaseURL}/send-vip", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("SMS VIP API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function smsOTP(string $to, string $msg): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'to' => $to,
            'msg' => $msg
        ];

        try {
            $response = $this->httpClient->post("{$this->smsBaseURL}/send-otp", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("SMS OTP API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function smsVVIP(string $to, string $msg): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'to' => $to,
            'msg' => $msg
        ];

        try {
            $response = $this->httpClient->post("{$this->smsBaseURL}/send-vvip", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("SMS VVIP API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function aiChat(string $message, string $agentId, string $format = 'text'): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'message' => $message,
            'agent_id' => $agentId,
            'format' => $format
        ];

        try {
            $response = $this->httpClient->post("{$this->aiBaseURL}/send-message", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("AI Chat API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function toWhatsApp(string $to, string $prefix = ''): self
    {
        if ($this->result === null) {
            throw new RuntimeException('No previous result to send');
        }

        $responseText = $this->extractResponseText($this->result);
        $fullMessage = $prefix . $responseText;

        return $this->whatsapp($to, $fullMessage);
    }

    public function toTelegram(string $receiver, string $prefix = ''): self
    {
        if ($this->result === null) {
            throw new RuntimeException('No previous result to send');
        }

        $responseText = $this->extractResponseText($this->result);
        $fullMessage = $prefix . $responseText;

        return $this->telegramSendMessage($receiver, $fullMessage);
    }

    public function toSMS(string $to, string $prefix = ''): self
    {
        if ($this->result === null) {
            throw new RuntimeException('No previous result to send');
        }

        $responseText = $this->extractResponseText($this->result);
        $fullMessage = $prefix . $responseText;

        return $this->smsRegular($to, $fullMessage);
    }

    public function getProfile(): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey
        ];

        try {
            $response = $this->httpClient->get("{$this->profileBaseURL}/raw", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Profile API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function updatePresence(string $receiver, string $presence, ?int $duration = null): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'receiver' => $receiver,
            'presence' => $presence
        ];

        if ($duration !== null) {
            $data['duration'] = $duration;
        }

        try {
            $response = $this->httpClient->post("{$this->presenceBaseURL}/update", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Presence API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function getMessageStatusAll(string $messageId): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'message_id' => $messageId
        ];

        try {
            $response = $this->httpClient->get("{$this->messagesBaseURL}/status/all", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Message Status API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function getLastStatus(string $messageId): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'message_id' => $messageId
        ];

        try {
            $response = $this->httpClient->get("{$this->statusBaseURL}/last", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Last Status API Error: {$e->getMessage()}", 0, $e);
        }
    }

    public function getLastReceiptStatus(string $messageId): self
    {
        $data = [
            'appkey' => $this->appkey,
            'authkey' => $this->authkey,
            'message_id' => $messageId
        ];

        try {
            $response = $this->httpClient->get("{$this->messagesBaseURL}/status/last/receipt", [
                'json' => $data,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $this->result = json_decode($response->getBody()->getContents(), true);
            return $this;
        } catch (GuzzleException $e) {
            throw new RuntimeException("Last Receipt Status API Error: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getResult(): ?array
    {
        return $this->result;
    }

    /**
     * @param array<string, mixed> $result
     * @return string
     */
    private function extractResponseText(array $result): string
    {
        if (isset($result['text'])) {
            return $result['text'];
        }

        if (isset($result['message'])) {
            return $result['message'];
        }

        if (isset($result['data'])) {
            if (is_string($result['data'])) {
                return $result['data'];
            }

            if (is_array($result['data'])) {
                if (isset($result['data']['text'])) {
                    return $result['data']['text'];
                }

                if (isset($result['data']['response'])) {
                    return $result['data']['response'];
                }

                return json_encode($result['data']) ?: '';
            }
        }

        if (isset($result['response'])) {
            return $result['response'];
        }

        return json_encode($result) ?: '';
    }
}
