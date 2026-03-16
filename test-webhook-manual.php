<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Simulasi request dari Telegram
$request = \Illuminate\Http\Request::create(
    '/telegram/webhook',
    'POST',
    [],
    [],
    [],
    [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN' => config('services.telegram.webhook_secret'),
    ],
    json_encode([
        'update_id' => 999999999,
        'message' => [
            'message_id' => 1,
            'from' => [
                'id' => 482752387,
                'first_name' => 'Test',
            ],
            'chat' => [
                'id' => 482752387,
                'type' => 'private',
            ],
            'date' => time(),
            'text' => '/start',
        ],
    ])
);

echo "Testing webhook endpoint...\n\n";

try {
    $response = $kernel->handle($request);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Response: " . $response->getContent() . "\n\n";
    
    if ($response->getStatusCode() === 200) {
        echo "✅ Webhook endpoint berfungsi!\n";
    } else {
        echo "❌ Webhook endpoint error!\n";
    }
    
    $kernel->terminate($request, $response);
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
