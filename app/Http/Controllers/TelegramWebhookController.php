<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTelegramNewsSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if (! $this->isWebhookAuthorized($request)) {
            abort(403);
        }

        $update = $request->all();
        $updateId = (string) data_get($update, 'update_id', '');

        if ($updateId !== '' && ! Cache::add('telegram:update:' . $updateId, true, now()->addDay())) {
            return response()->json(['ok' => true]);
        }

        $message = data_get($update, 'message');

        if (! is_array($message)) {
            return response()->json(['ok' => true]);
        }

        $chatId = data_get($message, 'chat.id');

        if (! is_numeric($chatId)) {
            return response()->json(['ok' => true]);
        }

        $chatId = (string) $chatId;

        if (! $this->isAllowedChat($chatId)) {
            $this->sendTelegramMessage($chatId, 'Chat ini belum diizinkan untuk otomatisasi berita. Hubungi admin sistem.');

            return response()->json(['ok' => true]);
        }

        $text = trim((string) data_get($message, 'text', ''));

        if ($text === '/start') {
            $this->sendTelegramMessage($chatId, 'Kirim foto dengan caption judul berita, atau kirim foto dulu lalu lanjutkan kirim judul di pesan berikutnya.');

            return response()->json(['ok' => true]);
        }

        $photoId = $this->extractLargestPhotoId(data_get($message, 'photo', []));
        $caption = trim((string) data_get($message, 'caption', ''));

        if ($photoId !== null && $caption !== '') {
            $this->dispatchSubmission($chatId, $caption, $photoId);

            return response()->json(['ok' => true]);
        }

        $pendingPhotoKey = $this->pendingPhotoCacheKey($chatId);

        if ($photoId !== null) {
            Cache::put($pendingPhotoKey, $photoId, now()->addMinutes(15));
            $this->sendTelegramMessage($chatId, 'Foto sudah diterima. Sekarang kirim judul beritanya dalam 1 pesan teks.');

            return response()->json(['ok' => true]);
        }

        if ($text !== '' && Cache::has($pendingPhotoKey)) {
            $cachedPhotoId = (string) Cache::pull($pendingPhotoKey);
            $this->dispatchSubmission($chatId, $text, $cachedPhotoId);

            return response()->json(['ok' => true]);
        }

        $this->sendTelegramMessage($chatId, 'Format belum sesuai. Kirim foto + caption judul, atau foto dulu lalu kirim judul.');

        return response()->json(['ok' => true]);
    }

    private function dispatchSubmission(string $chatId, string $title, string $photoId): void
    {
        $safeTitle = mb_strimwidth(trim($title), 0, 80, '...');

        $this->sendTelegramMessage(
            $chatId,
            "Upload Anda sudah terbaca di sistem.\nJudul: {$safeTitle}\nAI sedang membuat draft berita, mohon tunggu..."
        );

        ProcessTelegramNewsSubmission::dispatchSync($chatId, $title, $photoId);
    }

    private function isWebhookAuthorized(Request $request): bool
    {
        $secret = (string) config('services.telegram.webhook_secret', '');

        if ($secret === '') {
            return true;
        }

        return hash_equals($secret, (string) $request->header('X-Telegram-Bot-Api-Secret-Token', ''));
    }

    private function isAllowedChat(string $chatId): bool
    {
        $allowedChats = config('services.telegram.allowed_chat_ids', []);

        if (! is_array($allowedChats) || $allowedChats === []) {
            return true;
        }

        return in_array($chatId, array_map('strval', $allowedChats), true);
    }

    private function extractLargestPhotoId(mixed $photos): ?string
    {
        if (! is_array($photos) || $photos === []) {
            return null;
        }

        $largest = collect($photos)
            ->filter(fn ($photo) => is_array($photo) && data_get($photo, 'file_id'))
            ->sortBy(fn ($photo) => (int) data_get($photo, 'file_size', 0))
            ->last();

        return is_array($largest) ? (string) data_get($largest, 'file_id') : null;
    }

    private function pendingPhotoCacheKey(string $chatId): string
    {
        return 'telegram:pending-photo:' . $chatId;
    }

    private function sendTelegramMessage(string $chatId, string $text): void
    {
        $token = (string) config('services.telegram.bot_token', '');

        if ($token === '') {
            return;
        }

        Http::acceptJson()
            ->timeout(20)
            ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'disable_web_page_preview' => false,
            ]);
    }

}
