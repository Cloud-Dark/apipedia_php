<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Apipedia\Apipedia;

// Initialize the SDK
$apipedia = new Apipedia('your_app_key', 'your_auth_key');

echo "=== Advanced Workflow Examples ===\n\n";

// Example 1: Customer Support Bot Workflow
echo "1. Customer Support Bot Workflow\n";
echo "--------------------------------\n";

try {
    $customerQuery = "I need help with my order #12345. It hasn't arrived yet.";
    echo "Customer Query: {$customerQuery}\n";

    // AI processes the query and generates a response
    $supportResponse = $apipedia
        ->aiChat("Handle customer support query: {$customerQuery}", 'support_agent')
        ->toWhatsApp('628123456789', '🤖 Support Bot: ')
        ->toTelegram('@support_logs', '📋 Support Query: ');

    echo "✅ Support workflow completed successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Error in support workflow: " . $e->getMessage() . "\n\n";
}

// Example 2: Multi-Channel Marketing Campaign
echo "2. Multi-Channel Marketing Campaign\n";
echo "----------------------------------\n";

try {
    $campaignMessage = "Launch our summer sale campaign";

    // AI generates marketing content
    $campaign = $apipedia->aiChat(
        "Generate an engaging marketing message for: {$campaignMessage}",
        'marketing_agent'
    );

    // Send to multiple channels with different prefixes
    $campaign
        ->toWhatsApp('628123456789', '🎉 Special Offer: ')
        ->toTelegram('@marketing_channel', '📢 Campaign: ')
        ->toSMS('628123456789', 'SALE: ');

    echo "✅ Marketing campaign sent across all channels!\n\n";
} catch (Exception $e) {
    echo "❌ Error in marketing campaign: " . $e->getMessage() . "\n\n";
}

// Example 3: Order Notification System
echo "3. Order Notification System\n";
echo "----------------------------\n";

try {
    $orderDetails = [
        'order_id' => 'ORD-2024-001',
        'customer_name' => 'John Doe',
        'status' => 'shipped',
        'tracking_number' => 'TRK123456789'
    ];

    // AI generates personalized order notification
    $orderNotification = $apipedia->aiChat(
        "Generate order notification for order {$orderDetails['order_id']}, customer {$orderDetails['customer_name']}, status {$orderDetails['status']}, tracking {$orderDetails['tracking_number']}",
        'order_agent'
    );

    // Send notifications
    $orderNotification
        ->toWhatsApp('628123456789', '📦 Order Update: ')
        ->toSMS('628123456789', 'ORDER: ');

    echo "✅ Order notification sent successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Error in order notification: " . $e->getMessage() . "\n\n";
}

// Example 4: Bulk Personalized Messages
echo "4. Bulk Personalized Messages\n";
echo "-----------------------------\n";

try {
    $customers = [
        ['name' => 'Alice', 'phone' => '628111111111', 'last_purchase' => 'Laptop'],
        ['name' => 'Bob', 'phone' => '628222222222', 'last_purchase' => 'Smartphone'],
        ['name' => 'Carol', 'phone' => '628333333333', 'last_purchase' => 'Headphones']
    ];

    foreach ($customers as $customer) {
        try {
            // Generate personalized message for each customer
            $personalizedMessage = $apipedia->aiChat(
                "Generate a personalized follow-up message for customer {$customer['name']} who recently bought {$customer['last_purchase']}",
                'personalization_agent'
            );

            // Send personalized message
            $personalizedMessage->toWhatsApp($customer['phone'], "Hi {$customer['name']}! ");

            echo "✅ Personalized message sent to {$customer['name']}\n";
        } catch (Exception $e) {
            echo "❌ Error sending to {$customer['name']}: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Error in bulk personalization: " . $e->getMessage() . "\n\n";
}

// Example 5: Interactive Telegram Bot
echo "5. Interactive Telegram Bot\n";
echo "---------------------------\n";

try {
    // Send interactive buttons
    $buttons = [
        [
            ['text' => '📊 View Analytics', 'callback_data' => 'analytics'],
            ['text' => '📋 View Reports', 'callback_data' => 'reports']
        ],
        [
            ['text' => '⚙️ Settings', 'callback_data' => 'settings'],
            ['text' => '❓ Help', 'callback_data' => 'help']
        ],
        [
            ['text' => '🌐 Visit Website', 'url' => 'https://apipedia.id']
        ]
    ];

    $result = $apipedia->telegramSendButtons(
        '@your_channel',
        '🤖 Welcome to Apipedia Bot! Choose an option:',
        $buttons
    );

    echo "✅ Interactive Telegram bot menu sent!\n\n";
} catch (Exception $e) {
    echo "❌ Error in Telegram bot: " . $e->getMessage() . "\n\n";
}

// Example 6: Content Distribution Workflow
echo "6. Content Distribution Workflow\n";
echo "-------------------------------\n";

try {
    $contentTopic = "Latest tech news and trends";

    // AI generates content
    $content = $apipedia->aiChat(
        "Generate engaging content about: {$contentTopic}",
        'content_agent'
    );

    // Distribute across platforms with different formatting
    $content
        ->toTelegram('@tech_news', '📰 Tech News: ')
        ->toWhatsApp('628123456789', '📱 Daily Tech: ');

    // Create a summary version for SMS
    $summary = $apipedia->aiChat(
        "Create a short SMS-friendly summary of the previous content",
        'content_agent'
    );

    $summary->toSMS('628123456789', 'TECH: ');

    echo "✅ Content distributed across all platforms!\n\n";
} catch (Exception $e) {
    echo "❌ Error in content distribution: " . $e->getMessage() . "\n\n";
}

// Example 7: Emergency Alert System
echo "7. Emergency Alert System\n";
echo "-------------------------\n";

try {
    $emergencyType = "system maintenance";
    $maintenanceTime = "2024-01-15 02:00 AM";

    // AI generates appropriate emergency message
    $emergencyAlert = $apipedia->aiChat(
        "Generate urgent system maintenance alert for {$emergencyType} scheduled at {$maintenanceTime}",
        'emergency_agent'
    );

    // Send high-priority notifications
    $emergencyAlert
        ->toSMS('628123456789', '🚨 URGENT: ')  // SMS for immediate attention
        ->toWhatsApp('628123456789', '⚠️ Alert: ')  // WhatsApp for detailed info
        ->toTelegram('@alerts_channel', '🔔 System Alert: ');  // Telegram for team

    echo "✅ Emergency alert sent across all channels!\n\n";
} catch (Exception $e) {
    echo "❌ Error in emergency alert: " . $e->getMessage() . "\n\n";
}

// Example 8: Survey and Feedback Collection
echo "8. Survey and Feedback Collection\n";
echo "---------------------------------\n";

try {
    // Send survey via Telegram with buttons
    $surveyButtons = [
        [
            ['text' => '😊 Excellent', 'callback_data' => 'survey_excellent'],
            ['text' => '🙂 Good', 'callback_data' => 'survey_good']
        ],
        [
            ['text' => '😐 Average', 'callback_data' => 'survey_average'],
            ['text' => '😞 Poor', 'callback_data' => 'survey_poor']
        ]
    ];

    $surveyResult = $apipedia->telegramSendButtons(
        '@feedback_channel',
        '📝 How would you rate our service today?',
        $surveyButtons
    );

    // Also send via WhatsApp with a simple message
    $apipedia->whatsApp(
        '628123456789',
        '📝 We value your feedback! Please rate our service from 1-5 and let us know how we can improve.'
    );

    echo "✅ Survey sent via multiple channels!\n\n";
} catch (Exception $e) {
    echo "❌ Error in survey collection: " . $e->getMessage() . "\n\n";
}

// Example 9: Automated Report Generation and Distribution
echo "9. Automated Report Generation\n";
echo "------------------------------\n";

try {
    $reportData = [
        'period' => 'December 2024',
        'total_sales' => '$125,000',
        'growth' => '15%',
        'top_product' => 'Wireless Headphones'
    ];

    // AI generates comprehensive report
    $monthlyReport = $apipedia->aiChat(
        "Generate a monthly business report for {$reportData['period']} with sales {$reportData['total_sales']}, growth {$reportData['growth']}, top product {$reportData['top_product']}",
        'analytics_agent'
    );

    // Distribute report to different stakeholders
    $monthlyReport
        ->toTelegram('@management_channel', '📊 Monthly Report: ')
        ->toWhatsApp('628123456789', '📈 Performance Update: ');

    // Send summary to mobile via SMS
    $reportSummary = $apipedia->aiChat(
        "Create a brief SMS summary of the previous monthly report",
        'analytics_agent'
    );

    $reportSummary->toSMS('628123456789', 'REPORT: ');

    echo "✅ Monthly report generated and distributed!\n\n";
} catch (Exception $e) {
    echo "❌ Error in report generation: " . $e->getMessage() . "\n\n";
}

// Example 10: Smart Presence Management
echo "10. Smart Presence Management\n";
echo "-----------------------------\n";

try {
    $recipient = '628123456789';

    // Show typing indicator
    $apipedia->updatePresence($recipient, 'typing', 3000);
    echo "📝 Showing typing indicator for 3 seconds...\n";

    // Simulate processing time
    sleep(1);

    // Send the actual message
    $response = $apipedia->whatsapp($recipient, 'Hello! I was just preparing this message for you.');

    // Update to online status
    $apipedia->updatePresence($recipient, 'online');
    echo "✅ Message sent with smart presence management!\n\n";
} catch (Exception $e) {
    echo "❌ Error in presence management: " . $e->getMessage() . "\n\n";
}

echo "=== All Advanced Workflows Completed! ===\n";
echo "These examples demonstrate the power of chaining and multi-platform messaging.\n";
echo "Remember to:\n";
echo "- Replace credentials with your actual API keys\n";
echo "- Update phone numbers and channel names\n";
echo "- Customize AI agent IDs based on your available agents\n";
echo "- Handle errors appropriately in production code\n";