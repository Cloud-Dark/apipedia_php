<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Apipedia\Apipedia;

// Initialize the SDK with your credentials
$apipedia = new Apipedia('your_app_key', 'your_auth_key');

// Or use the helper function
// $apipedia = apipedia('your_app_key', 'your_auth_key');

echo "=== Basic WhatsApp Usage ===\n";

try {
    // Send a simple WhatsApp message
    $result = $apipedia->whatsapp('628123456789', 'Hello from PHP SDK!');
    echo "Message sent successfully!\n";
    echo "Message ID: " . $result->getResult()['message_id'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== WhatsApp with Media ===\n";

try {
    // Send WhatsApp message with image URL
    $result = $apipedia->whatsapp(
        '628123456789',
        'Check out this image!',
        'https://via.placeholder.com/300x200.jpg'
    );
    echo "Image message sent successfully!\n";
    echo "Message ID: " . $result->getResult()['message_id'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Bulk Messaging ===\n";

try {
    // Send the same message to multiple recipients
    $phoneNumbers = ['628123456789', '628987654321', '628555666777'];
    $result = $apipedia->bulkV1($phoneNumbers, 'Important announcement for everyone!');
    echo "Bulk message sent successfully!\n";
    echo "Sent to " . count($phoneNumbers) . " recipients\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Telegram Integration ===\n";

try {
    // Send message to Telegram channel
    $result = $apipedia->telegramSendMessage('@your_channel', 'Hello from PHP SDK!');
    echo "Telegram message sent successfully!\n";
    echo "Message ID: " . $result->getResult()['message_id'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== SMS Sending ===\n";

try {
    // Send regular SMS
    $result = $apipedia->smsRegular('628123456789', 'Hello via SMS from PHP SDK!');
    echo "SMS sent successfully!\n";
    echo "SMS ID: " . $result->getResult()['sms_id'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== OTP SMS ===\n";

try {
    // Generate and send OTP
    $otpCode = rand(100000, 999999);
    $result = $apipedia->smsOTP('628123456789', "Your OTP verification code is: {$otpCode}. Valid for 5 minutes.");
    echo "OTP SMS sent successfully!\n";
    echo "OTP Code: {$otpCode}\n";
    echo "SMS ID: " . $result->getResult()['sms_id'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== AI Chat ===\n";

try {
    // Interact with AI agent
    $result = $apipedia->aiChat('What is the capital of Indonesia?', 'general_agent', 'text');
    echo "AI Response: " . $result->getResult()['response'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Method Chaining ===\n";

try {
    // AI generates content and sends to WhatsApp
    $result = $apipedia
        ->aiChat('Generate a motivational quote for today', 'motivation_agent')
        ->toWhatsApp('628123456789', '🌟 Daily Motivation: ');

    echo "Chained operation completed successfully!\n";
    echo "Final Message ID: " . $result->getResult()['message_id'] . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Profile Information ===\n";

try {
    // Get profile information
    $result = $apipedia->getProfile();
    $profile = $result->getResult();
    echo "Profile retrieved successfully!\n";
    echo "Account Info: " . json_encode($profile, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Presence Update ===\n";

try {
    // Update presence to show typing
    $result = $apipedia->updatePresence('628123456789', 'typing', 5000);
    echo "Presence updated successfully!\n";
    echo "Status: typing for 5 seconds\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Complete! ===\n";
echo "All basic operations demonstrated.\n";
echo "Remember to replace 'your_app_key' and 'your_auth_key' with your actual credentials.\n";
echo "Also update the phone numbers and channel names with real ones for testing.\n";