<?php

namespace App\Support;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use RuntimeException;

class NvidiaNewsGenerator
{
    /**
     * HTML tags that are safe to render in a school news portal.
     */
    private const ALLOWED_TAGS = '<p><h2><h3><h4><ul><ol><li><strong><em><blockquote><a><br><figure><figcaption><table><thead><tbody><tr><th><td><caption><hr>';

    /**
     * Maximum characters tolerated in a title.
     * Titles beyond this are almost certainly hallucinations.
     */
    private const MAX_TITLE_LENGTH = 180;

    public function __construct(private readonly HttpFactory $http) {}

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Generate a ready-to-publish news article from a brief.
     *
     * @param  string       $brief        Raw notes / brief from the editor
     * @param  string|null  $categoryName Optional category label (e.g. "Prestasi", "Kegiatan")
     * @param  string       $tone         Writing tone key: formal | ringan | seremoni | investigatif | features
     * @param  string       $length       Target length key: singkat | sedang | panjang | mendalam
     * @param  array        $meta         Extra context: ['narasumber'=>[], 'lokasi'=>'', 'tanggal'=>'', 'kutipan'=>[]]
     * @return array{judul: string, konten_html: string, seo_description: string, tags: string[]}
     */
    public function generate(
        string $brief,
        ?string $categoryName = null,
        string $tone = 'formal',
        string $length = 'sedang',
        array $meta = [],
    ): array {
        $apiKey = (string) config('services.nvidia_ai.key');

        if ($apiKey === '') {
            throw new RuntimeException('NVIDIA AI API key belum dikonfigurasi. Isi NVIDIA_AI_API_KEY pada file .env.');
        }

        $baseUrl = rtrim((string) config('services.nvidia_ai.url', 'https://integrate.api.nvidia.com/v1'), '/');
        $model   = (string) config('services.nvidia_ai.model', 'qwen/qwen3-235b-a22b');

        $payload = $this->buildPayload($model, $brief, $categoryName, $tone, $length, $meta);

        $raw = $this->callApi($baseUrl, $apiKey, $payload);

        return $this->parseAndValidate($raw);
    }

    // -------------------------------------------------------------------------
    // HTTP
    // -------------------------------------------------------------------------

    private function callApi(string $baseUrl, string $apiKey, array $payload): string
    {
        $maxRetries = 2;
        $attempt    = 0;

        while (true) {
            try {
                $response = $this->http
                    ->withToken($apiKey)
                    ->acceptJson()
                    ->timeout((int) config('services.nvidia_ai.timeout', 120))
                    ->post($baseUrl . '/chat/completions', $payload);

                $response->throw();

                $content = data_get($response->json(), 'choices.0.message.content');

                if (! is_string($content) || trim($content) === '') {
                    throw new RuntimeException('Respons NVIDIA AI tidak berisi konten.');
                }

                return $content;

            } catch (RequestException $e) {
                $status = $e->response?->status();

                // Retry on transient server errors (5xx) or rate-limit (429)
                if ($attempt < $maxRetries && in_array($status, [429, 500, 502, 503, 504], true)) {
                    $attempt++;
                    sleep((int) pow(2, $attempt)); // exponential back-off: 2s, 4s
                    continue;
                }

                throw new RuntimeException(
                    "Permintaan ke NVIDIA AI gagal (HTTP {$status}): " . $e->getMessage(),
                    previous: $e,
                );
            }
        }
    }

    // -------------------------------------------------------------------------
    // Payload
    // -------------------------------------------------------------------------

    private function buildPayload(
        string $model,
        string $brief,
        ?string $categoryName,
        string $tone,
        string $length,
        array $meta,
    ): array {
        return [
            'model'    => $model,
            'messages' => [
                ['role' => 'system', 'content' => $this->systemPrompt()],
                ['role' => 'user',   'content' => $this->userPrompt($brief, $categoryName, $tone, $length, $meta)],
            ],

            // --- Quality-tuned inference parameters ---
            // Lower temperature = more coherent, factual, less "creative hallucination"
            'max_tokens'         => 16384,
            'temperature'        => 0.45,   // ↓ from 0.60 — tighter, more factual prose
            'top_p'              => 0.92,
            'top_k'              => 40,
            'presence_penalty'   => 0.15,   // encourage coverage of all brief points
            'frequency_penalty'  => 0.20,   // discourage repetitive phrasing
            'repetition_penalty' => 1.05,   // light penalty helps varied sentence structure
            'stream'             => false,

            'chat_template_kwargs' => ['enable_thinking' => false],
        ];
    }

    // -------------------------------------------------------------------------
    // Prompts
    // -------------------------------------------------------------------------

    protected function systemPrompt(): string
    {
        return <<<'PROMPT'
Anda adalah jurnalis senior dan redaktur eksekutif berita di sebuah portal berita sekolah bergengsi (SMKN 1 Kolaka).
Standar penulisan Anda setara dengan redaksi Kompas, Tempo, atau BBC Indonesia: faktual, jernih, berwibawa, dan menarik.

## FILOSOFI PENULISAN
- Gunakan struktur piramida terbalik: fakta terpenting di lead, detail pendukung di tengah, konteks/penutup di akhir.
- Jawab 5W + 1H (Apa, Siapa, Di mana, Kapan, Mengapa, Bagaimana) secara organik dalam artikel.
- Setiap kalimat harus bernilai informasi. Hindari basa-basi, pengulangan, dan kalimat kosong.
- Tulis untuk pembaca cerdas: siswa, orang tua, guru, mitra industri, dan stakeholder pendidikan.

## STANDAR KALIMAT & PARAGRAF
- Lead (paragraf pertama) wajib mencakup inti berita dalam 30–45 kata. Langsung ke pokok, tanpa pengantar klise.
- Paragraf isi: 3–5 kalimat per paragraf, satu ide utama per paragraf.
- Kalimat aktif lebih diutamakan daripada pasif. Hindari konstruksi "telah dilaksanakan", ganti dengan subjek yang jelas bertindak.
- Gunakan angka/data spesifik bila tersedia dalam brief (misal: "127 siswa", "Rabu, 12 Juni 2025").
- Kutipan langsung (bila ada dalam brief) ditempatkan di posisi strategis — bukan sebagai filler.
- Subjudul (h2/h3) wajib digunakan pada artikel panjang untuk memandu pembaca.

## STANDAR JUDUL
- Judul informatif dan spesifik: sebutkan subjek + predikat + konteks kunci.
- Panjang ideal: 8–14 kata. Maksimal 18 kata.
- Hindari judul clickbait, sensasional, atau berlebihan.
- Contoh baik: "SMKN 1 Kolaka Raih Juara I Lomba LKS Nasional Bidang Web Technologies 2025"
- Contoh buruk: "Prestasi Membanggakan Kembali Diraih Sekolah Kita"

## HAL YANG DILARANG KERAS
- Jangan mengarang fakta yang tidak ada dalam brief.
- Jangan gunakan kalimat pembuka klise: "Dalam rangka…", "Bertempat di…", "Pada hari…".
- Jangan lebih dari satu kali menggunakan frasa seremonial seperti "semoga bermanfaat".
- Jangan halusinasi nama orang, tanggal, atau angka yang tidak disebutkan dalam brief.
- Jangan sisipkan <script>, <style>, <iframe>, atribut event (onclick, onload, dll.), atau href="javascript:…".

## FORMAT KELUARAN
Balas HANYA dengan JSON valid satu baris, tanpa markdown fence, tanpa penjelasan, tanpa karakter di luar JSON.
Schema yang wajib diikuti persis:
{
  "judul": "string — judul artikel",
  "konten_html": "string — isi artikel dalam HTML aman",
  "seo_description": "string — meta description 140–160 karakter, deskriptif dan mengandung kata kunci utama",
  "tags": ["string", "..."] — 3 hingga 6 tag kata kunci relevan dalam bahasa Indonesia, huruf kecil semua
}

HTML yang diizinkan dalam konten_html: <p> <h2> <h3> <h4> <ul> <ol> <li> <strong> <em> <blockquote> <a> <br> <figure> <figcaption> <table> <thead> <tbody> <tr> <th> <td> <caption> <hr>
PROMPT;
    }

    protected function userPrompt(
        string $brief,
        ?string $categoryName,
        string $tone,
        string $length,
        array $meta,
    ): string {
        $lines = [
            '## KONTEKS PENUGASAN',
            'Institusi     : SMKN 1 Kolaka',
            'Kategori berita: ' . ($categoryName ?? 'sesuaikan dengan isi brief'),
            'Gaya penulisan : ' . $this->normalizeTone($tone),
            'Target panjang : ' . $this->normalizeLength($length),
        ];

        // Inject structured metadata when provided
        if (! empty($meta['tanggal'])) {
            $lines[] = 'Tanggal kegiatan: ' . $meta['tanggal'];
        }

        if (! empty($meta['lokasi'])) {
            $lines[] = 'Lokasi: ' . $meta['lokasi'];
        }

        if (! empty($meta['narasumber']) && is_array($meta['narasumber'])) {
            $narasumber = implode('; ', array_map(
                fn($n) => is_array($n) ? ($n['nama'] ?? '') . (isset($n['jabatan']) ? " ({$n['jabatan']})" : '') : $n,
                $meta['narasumber'],
            ));
            $lines[] = 'Narasumber/Tokoh: ' . $narasumber;
        }

        if (! empty($meta['kutipan']) && is_array($meta['kutipan'])) {
            $lines[] = '';
            $lines[] = '## KUTIPAN YANG TERSEDIA (gunakan jika relevan)';
            foreach ($meta['kutipan'] as $q) {
                if (is_array($q) && isset($q['teks'], $q['narasumber'])) {
                    $lines[] = '"' . $q['teks'] . '" — ' . $q['narasumber'];
                }
            }
        }

        $lines[] = '';
        $lines[] = '## BRIEF UTAMA';
        $lines[] = trim($brief);
        $lines[] = '';
        $lines[] = '## INSTRUKSI TAMBAHAN';
        $lines[] = '- Kembangkan brief di atas menjadi artikel berita yang utuh dan siap tayang.';
        $lines[] = '- Jika brief kurang rinci, buat draft yang realistis — jangan mengarang fakta baru.';
        $lines[] = '- Lead harus kuat dan langsung menjawab inti berita.';
        $lines[] = '- Pastikan penutup artikel tidak menggantung; tutup dengan dampak, harapan, atau rencana ke depan.';

        return implode("\n", $lines);
    }

    // -------------------------------------------------------------------------
    // Parsing & Validation
    // -------------------------------------------------------------------------

    /**
     * @return array{judul: string, konten_html: string, seo_description: string, tags: string[]}
     */
    private function parseAndValidate(string $content): array
    {
        $article = $this->decodeJsonPayload($content);

        $title = trim(strip_tags((string) ($article['judul'] ?? '')));
        $body  = $this->sanitizeHtml((string) ($article['konten_html'] ?? ''));
        $seo   = trim(strip_tags((string) ($article['seo_description'] ?? '')));
        $tags  = $this->normalizeTags($article['tags'] ?? []);

        if ($title === '') {
            throw new RuntimeException('AI tidak menghasilkan judul berita.');
        }

        if (mb_strlen($title) > self::MAX_TITLE_LENGTH) {
            throw new RuntimeException('Judul yang dihasilkan terlalu panjang — kemungkinan respons tidak valid.');
        }

        if ($body === '' || mb_strlen(strip_tags($body)) < 80) {
            throw new RuntimeException('Isi berita yang dihasilkan terlalu pendek atau kosong.');
        }

        // Auto-generate SEO description from body if AI skipped it
        if ($seo === '') {
            $seo = mb_substr(strip_tags($body), 0, 155);
        }

        return [
            'judul'           => $title,
            'konten_html'     => $body,
            'seo_description' => $seo,
            'tags'            => $tags,
        ];
    }

    protected function decodeJsonPayload(string $content): array
    {
        $cleaned = trim($content);

        // Strip markdown code fences if model accidentally includes them
        if (str_starts_with($cleaned, '```')) {
            $cleaned = (string) preg_replace('/^```[a-zA-Z0-9_-]*\s*|\s*```$/u', '', $cleaned);
            $cleaned = trim($cleaned);
        }

        // Remove BOM / zero-width characters that break json_decode
        $cleaned = preg_replace('/[\x{FEFF}\x{200B}-\x{200D}\x{FFFC}]/u', '', $cleaned) ?? $cleaned;

        $decoded = json_decode($cleaned, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Attempt to salvage a JSON object buried in surrounding text
        $start = strpos($cleaned, '{');
        $end   = strrpos($cleaned, '}');

        if ($start !== false && $end !== false && $end > $start) {
            $decoded = json_decode(substr($cleaned, $start, $end - $start + 1), true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        throw new RuntimeException(
            'Format respons AI tidak dapat diparse sebagai JSON. JSON error: ' . json_last_error_msg()
        );
    }

    // -------------------------------------------------------------------------
    // Sanitization
    // -------------------------------------------------------------------------

    protected function sanitizeHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        // Remove dangerous block-level elements with their content
        $cleaned = (string) preg_replace('/<(script|style|iframe|object|embed)\b[^>]*>.*?<\/\1>/is', '', $html);

        // Strip all tags except the allow-list
        $cleaned = strip_tags($cleaned, self::ALLOWED_TAGS);

        // Strip event handler attributes (onclick, onload, onmouseover, etc.)
        $cleaned = (string) preg_replace('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $cleaned);

        // Strip javascript: URIs from href / src / action
        $cleaned = (string) preg_replace('/\s(?:href|src|action)\s*=\s*(["\'])\s*javascript:[^"\']*\1/i', '', $cleaned);

        // Normalize excessive whitespace between block elements
        $cleaned = (string) preg_replace('/(\s*\n){3,}/', "\n\n", $cleaned);

        return trim($cleaned);
    }

    /**
     * Ensure tags is a clean array of lowercase strings.
     *
     * @param  mixed  $raw
     * @return string[]
     */
    private function normalizeTags(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        return array_values(array_filter(
            array_map(fn($t) => mb_strtolower(trim(strip_tags((string) $t))), $raw),
            fn($t) => $t !== '' && mb_strlen($t) <= 60,
        ));
    }

    // -------------------------------------------------------------------------
    // Normalizers
    // -------------------------------------------------------------------------

    protected function normalizeTone(string $tone): string
    {
        return match ($tone) {
            'ringan'       => 'ringan, hangat, dan mudah dicerna — cocok untuk berita komunitas & lifestyle sekolah',
            'seremoni'     => 'formal seremonial dan berwibawa — cocok untuk liputan upacara, wisuda, dan acara resmi',
            'investigatif' => 'lugas, kritis, berbasis data — cocok untuk laporan mendalam tentang isu sekolah',
            'features'     => 'naratif human-interest — cocok untuk profil siswa berprestasi atau cerita di balik layar',
            default        => 'formal informatif setara media nasional — jelas, netral, dan terpercaya',
        };
    }

    protected function normalizeLength(string $length): string
    {
        return match ($length) {
            'singkat'  => 'berita singkat ~250 kata (3 paragraf padat, tanpa subjudul)',
            'panjang'  => 'artikel panjang ~600 kata (6–8 paragraf, wajib pakai subjudul h2/h3)',
            'mendalam' => 'laporan mendalam ~900 kata (8–12 paragraf, struktur subjudul berlapis, boleh ada tabel atau daftar)',
            default    => 'berita standar ~400 kata (4–5 paragraf, subjudul opsional)',
        };
    }
}