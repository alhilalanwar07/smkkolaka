<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$botToken = config('services.telegram.bot_token');
$webhookSecret = config('services.telegram.webhook_secret');
$appUrl = config('app.url');

if (!$botToken) {
    echo "ERROR: TELEGRAM_BOT_TOKEN tidak ada\n";
    exit(1);
}

$webhookUrl = rtrim($appUrl, '/') . '/telegram/webhook';

echo "Setting webhook...\n";
echo "URL: {$webhookUrl}\n";
echo "Secret: " . ($webhookSecret ? 'Ada' : 'Tidak ada') . "\n\n";

$params = [
    'url' => $webhookUrl,
    'max_connections' => 40,
    'drop_pending_updates' => true, // Hapus update yang pending
];

if ($webhookSecret) {
    $params['secret_token'] = $webhookSecret;
}

$response = \Illuminate\Support\Facades\Http::acceptJson()
    ->timeout(20)
    ->post("https://api.telegram.org/bot{$botToken}/setWebhook", $params);

if ($response->successful()) {
    $data = $response->json();
    
    if ($data['ok'] ?? false) {
        echo "✅ Webhook berhasil di-set!\n";
        echo "Description: " . ($data['description'] ?? 'N/A') . "\n";
    } else {
        echo "❌ Gagal set webhook\n";
        echo "Response: " . $response->body() . "\n";
    }
} else {
    echo "❌ HTTP Error\n";
    echo "Status: " . $response->status() . "\n";
    echo "Response: " . $response->body() . "\n";
}
