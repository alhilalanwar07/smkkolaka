<?php

namespace App\Support;

use Illuminate\Http\Client\Factory as HttpFactory;
use RuntimeException;

class NvidiaNewsGenerator
{
    public function __construct(private readonly HttpFactory $http) {}

    public function generate(string $brief, ?string $categoryName = null, string $tone = 'formal', string $length = 'sedang'): array
    {
        $apiKey = (string) config('services.nvidia_ai.key');

        if ($apiKey === '') {
            throw new RuntimeException('NVIDIA AI API key belum dikonfigurasi. Isi NVIDIA_AI_API_KEY pada file .env.');
        }

        $baseUrl = rtrim((string) config('services.nvidia_ai.url', 'https://integrate.api.nvidia.com/v1'), '/');

        $response = $this->http
            ->withToken($apiKey)
            ->acceptJson()
            ->timeout((int) config('services.nvidia_ai.timeout', 90))
            ->post($baseUrl . '/chat/completions', [
                'model' => config('services.nvidia_ai.model', 'qwen/qwen3.5-397b-a17b'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->systemPrompt(),
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->userPrompt($brief, $categoryName, $tone, $length),
                    ],
                ],
                'max_tokens' => 16384,
                'temperature' => 0.60,
                'top_p' => 0.95,
                'top_k' => 20,
                'presence_penalty' => 0,
                'repetition_penalty' => 1,
                'stream' => false,
                'chat_template_kwargs' => [
                    'enable_thinking' => false,
                ],
            ]);

        $response->throw();

        $content = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new RuntimeException('Respons NVIDIA AI tidak berisi konten yang dapat dipakai.');
        }

        $article = $this->decodeJsonPayload($content);

        $title = trim(strip_tags((string) ($article['judul'] ?? '')));
        $body = $this->sanitizeHtml((string) ($article['konten_html'] ?? ''));

        if ($title === '' || $body === '') {
            throw new RuntimeException('Respons NVIDIA AI tidak menghasilkan judul dan isi berita yang valid.');
        }

        return [
            'judul' => $title,
            'konten_html' => $body,
        ];
    }

    protected function systemPrompt(): string
    {
        return <<<'PROMPT'
Anda adalah redaktur berita sekolah untuk SMKN 1 Kolaka.
Tugas Anda adalah menyusun draft berita website sekolah dalam Bahasa Indonesia yang jelas, faktual, rapi, dan siap tayang.

Aturan keluaran:
- Balas HANYA dengan JSON valid tanpa markdown, tanpa code fence, tanpa penjelasan tambahan.
- Gunakan schema persis seperti ini: {"judul":"...","konten_html":"..."}
- konten_html harus berupa HTML aman dan siap render, memakai elemen berikut saja bila diperlukan: <p>, <h2>, <h3>, <ul>, <ol>, <li>, <strong>, <em>, <blockquote>, <a>, <br>.
- Jangan gunakan <script>, <style>, <iframe>, atau atribut event seperti onclick.
- Buat isi yang terdengar seperti berita sekolah resmi, bukan iklan.
- Buat lead yang kuat, isi terstruktur, dan penutup yang wajar.
PROMPT;
    }

    protected function userPrompt(string $brief, ?string $categoryName, string $tone, string $length): string
    {
        $categoryLine = $categoryName ? "Kategori berita: {$categoryName}" : 'Kategori berita: sesuaikan dengan konteks brief.';

        return trim(implode("\n", [
            $categoryLine,
            'Gaya penulisan: ' . $this->normalizeTone($tone) . '.',
            'Panjang artikel: ' . $this->normalizeLength($length) . '.',
            'Kembangkan brief berikut menjadi judul dan isi berita HTML yang utuh.',
            'Jika brief kurang rinci, tetap buat draft yang realistis dan tidak berlebihan.',
            '',
            'Brief:',
            trim($brief),
        ]));
    }

    protected function decodeJsonPayload(string $content): array
    {
        $cleaned = trim($content);

        if (str_starts_with($cleaned, '```')) {
            $cleaned = preg_replace('/^```[a-zA-Z0-9_-]*\s*|\s*```$/', '', $cleaned) ?? $cleaned;
            $cleaned = trim($cleaned);
        }

        $decoded = json_decode($cleaned, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        $start = strpos($cleaned, '{');
        $end = strrpos($cleaned, '}');

        if ($start === false || $end === false || $end <= $start) {
            throw new RuntimeException('Format respons NVIDIA AI tidak dapat diparse sebagai JSON.');
        }

        $decoded = json_decode(substr($cleaned, $start, $end - $start + 1), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw new RuntimeException('Format respons NVIDIA AI tidak valid.');
        }

        return $decoded;
    }

    protected function sanitizeHtml(string $html): string
    {
        $cleaned = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', '', $html) ?? $html;
        $cleaned = strip_tags($cleaned, '<p><h2><h3><ul><ol><li><strong><em><blockquote><a><br>');
        $cleaned = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\s(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '', $cleaned) ?? $cleaned;

        return trim($cleaned);
    }

    protected function normalizeTone(string $tone): string
    {
        return match ($tone) {
            'ringan' => 'ringan, hangat, dan informatif',
            'seremoni' => 'formal seremonial dan berwibawa',
            default => 'formal informatif khas website sekolah',
        };
    }

    protected function normalizeLength(string $length): string
    {
        return match ($length) {
            'singkat' => 'sekitar 3 paragraf ringkas',
            'panjang' => 'sekitar 6 sampai 8 paragraf dengan subjudul bila relevan',
            default => 'sekitar 4 sampai 5 paragraf yang padat',
        };
    }
}