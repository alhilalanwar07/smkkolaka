<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class TelegramWebhookTest extends TestCase
{
    public function test_webhook_rejects_invalid_secret_header(): void
    {
        Config::set('services.telegram.webhook_secret', 'valid-secret');

        $this->postJson('/telegram/webhook', [
            'update_id' => 1001,
        ])->assertForbidden();
    }

    public function test_start_command_returns_ok_response(): void
    {
        Config::set('services.telegram.webhook_secret', '');
        Config::set('services.telegram.bot_token', '');

        $this->postJson('/telegram/webhook', [
            'update_id' => 1002,
            'message' => [
                'chat' => ['id' => 123456],
                'text' => '/start',
            ],
        ])->assertOk()->assertJson(['ok' => true]);
    }
}
