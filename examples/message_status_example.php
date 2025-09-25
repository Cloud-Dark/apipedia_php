<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Apipedia\Apipedia;

// Initialize with your credentials
$apipedia = new Apipedia('your_app_key', 'your_auth_key');

try {
    // Example message ID (replace with actual message ID)
    $messageId = 'your_message_id_here';

    echo "=== Message Status Examples ===\n\n";

    // 1. Get all message statuses
    echo "1. Getting all message statuses...\n";
    $result = $apipedia->getMessageStatusAll($messageId);
    echo "Response: " . json_encode($result->getResult(), JSON_PRETTY_PRINT) . "\n\n";

    // 2. Get last status
    echo "2. Getting last status...\n";
    $result = $apipedia->getLastStatus($messageId);
    echo "Response: " . json_encode($result->getResult(), JSON_PRETTY_PRINT) . "\n\n";

    // 3. Get last receipt status
    echo "3. Getting last receipt status...\n";
    $result = $apipedia->getLastReceiptStatus($messageId);
    echo "Response: " . json_encode($result->getResult(), JSON_PRETTY_PRINT) . "\n\n";

    echo "=== All tests completed successfully! ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Make sure to replace 'your_app_key', 'your_auth_key', and 'your_message_id_here' with actual values.\n";
}