<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Ambil config
$botToken = config('services.telegram.bot_token');
$allowedChats = config('services.telegram.allowed_chat_ids');

echo "Bot Token: " . ($botToken ? 'Ada (' . strlen($botToken) . ' karakter)' : 'TIDAK ADA') . "\n";
echo "Allowed Chat IDs: " . json_encode($allowedChats) . "\n\n";

// Test kirim pesan
if (!$botToken) {
    echo "ERROR: TELEGRAM_BOT_TOKEN tidak ada di .env\n";
    exit(1);
}

if (empty($allowedChats)) {
    echo "WARNING: TELEGRAM_ALLOWED_CHAT_IDS kosong, semua chat diizinkan\n\n";
}

// Ambil chat ID dari argument atau gunakan yang pertama dari allowed
$chatId = $argv[1] ?? ($allowedChats[0] ?? null);

if (!$chatId) {
    echo "Usage: php test-telegram.php [CHAT_ID]\n";
    echo "Atau set TELEGRAM_ALLOWED_CHAT_IDS di .env\n";
    exit(1);
}

echo "Mengirim test message ke chat ID: {$chatId}\n";

$response = \Illuminate\Support\Facades\Http::acceptJson()
    ->timeout(20)
    ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
        'chat_id' => $chatId,
        'text' => '🧪 Test notifikasi dari Laravel - ' . now()->format('Y-m-d H:i:s'),
    ]);

if ($response->successful()) {
    echo "✅ Berhasil kirim pesan!\n";
    echo "Response: " . $response->body() . "\n";
} else {
    echo "❌ Gagal kirim pesan!\n";
    echo "Status: " . $response->status() . "\n";
    echo "Response: " . $response->body() . "\n";
}
