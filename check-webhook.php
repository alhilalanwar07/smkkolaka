<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$botToken = config('services.telegram.bot_token');

if (!$botToken) {
    echo "ERROR: TELEGRAM_BOT_TOKEN tidak ada\n";
    exit(1);
}

echo "Mengecek webhook info...\n\n";

$response = \Illuminate\Support\Facades\Http::acceptJson()
    ->timeout(20)
    ->get("https://api.telegram.org/bot{$botToken}/getWebhookInfo");

if ($response->successful()) {
    $data = $response->json();
    $result = $data['result'] ?? [];
    
    echo "URL Webhook: " . ($result['url'] ?? 'TIDAK ADA') . "\n";
    echo "Has Custom Certificate: " . ($result['has_custom_certificate'] ?? false ? 'Ya' : 'Tidak') . "\n";
    echo "Pending Update Count: " . ($result['pending_update_count'] ?? 0) . "\n";
    echo "Max Connections: " . ($result['max_connections'] ?? 'default') . "\n";
    echo "IP Address: " . ($result['ip_address'] ?? 'N/A') . "\n";
    
    if (isset($result['last_error_date'])) {
        echo "\n⚠️ LAST ERROR:\n";
        echo "Date: " . date('Y-m-d H:i:s', $result['last_error_date']) . "\n";
        echo "Message: " . ($result['last_error_message'] ?? 'N/A') . "\n";
    }
    
    if (isset($result['last_synchronization_error_date'])) {
        echo "\n⚠️ LAST SYNC ERROR:\n";
        echo "Date: " . date('Y-m-d H:i:s', $result['last_synchronization_error_date']) . "\n";
    }
    
    echo "\n✅ Webhook info berhasil diambil\n";
} else {
    echo "❌ Gagal mengambil webhook info\n";
    echo "Status: " . $response->status() . "\n";
    echo "Response: " . $response->body() . "\n";
}
