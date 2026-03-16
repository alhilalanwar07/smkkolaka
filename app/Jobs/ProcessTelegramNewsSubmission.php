<?php

namespace App\Jobs;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\User;
use App\Support\NvidiaNewsGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProcessTelegramNewsSubmission implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $chatId,
        public string $title,
        public string $photoId,
    ) {}

    public function handle(NvidiaNewsGenerator $generator): void
    {
        try {
            $thumbnailPath = $this->downloadTelegramPhoto($this->photoId);

            $article = $generator->generate(
                brief: $this->buildBriefFromTitle($this->title),
                categoryName: $this->resolveCategory()?->nama_kategori,
                tone: 'formal',
                length: 'sedang',
            );

            $author = $this->resolveAuthor();
            $category = $this->resolveCategory();
            $isPublished = (bool) config('services.telegram.news_auto_publish', true);
            $now = now();

            $berita = Berita::create([
                'user_id' => $author->id,
                'kategori_id' => $category->id,
                'judul' => $article['judul'],
                'slug' => $this->uniqueSlug($article['judul']),
                'konten_html' => $article['konten_html'],
                'gambar_thumbnail' => $thumbnailPath,
                'status_publikasi' => $isPublished ? 'published' : 'draft',
                'published_at' => $isPublished ? $now : null,
            ]);

            $publicUrl = route('berita.show', ['slug' => $berita->slug]);
            $statusText = $isPublished ? 'published' : 'disimpan sebagai draft';

            $this->sendTelegramMessage(
                $this->chatId,
                "Berita berhasil {$statusText}.\nJudul: {$berita->judul}\nLink: {$publicUrl}"
            );
        } catch (Throwable $exception) {
            report($exception);
            Log::error('Telegram berita automation failed.', [
                'chat_id' => $this->chatId,
                'message' => $exception->getMessage(),
            ]);

            $this->sendTelegramMessage(
                $this->chatId,
                'Maaf, proses pembuatan berita gagal. Coba lagi dalam beberapa saat atau cek konfigurasi server.'
            );
        }
    }

    private function downloadTelegramPhoto(string $fileId): string
    {
        $token = (string) config('services.telegram.bot_token', '');

        if ($token === '') {
            throw new \RuntimeException('TELEGRAM_BOT_TOKEN belum dikonfigurasi.');
        }

        $fileResponse = Http::acceptJson()
            ->timeout(20)
            ->get("https://api.telegram.org/bot{$token}/getFile", ['file_id' => $fileId])
            ->throw()
            ->json();

        $filePath = (string) data_get($fileResponse, 'result.file_path', '');

        if ($filePath === '') {
            throw new \RuntimeException('Tidak bisa mendapatkan path file dari Telegram.');
        }

        $binary = Http::timeout(30)
            ->get("https://api.telegram.org/file/bot{$token}/{$filePath}")
            ->throw()
            ->body();

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $extension = $extension !== '' ? $extension : 'jpg';
        $storagePath = 'berita/telegram/' . now()->format('Y/m') . '/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($storagePath, $binary);

        return $storagePath;
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

    private function resolveAuthor(): User
    {
        $email = trim((string) config('services.telegram.news_author_email', ''));

        if ($email !== '') {
            $configured = User::query()->where('email', $email)->first();

            if ($configured) {
                return $configured;
            }
        }

        $fallback = User::query()->oldest('id')->first();

        if (! $fallback) {
            throw new \RuntimeException('Belum ada user untuk dijadikan penulis berita Telegram.');
        }

        return $fallback;
    }

    private function resolveCategory(): KategoriBerita
    {
        $slug = trim((string) config('services.telegram.news_default_category_slug', ''));

        if ($slug !== '') {
            $configured = KategoriBerita::query()->where('slug', $slug)->first();

            if ($configured) {
                return $configured;
            }
        }

        $fallback = KategoriBerita::query()->oldest('id')->first();

        if (! $fallback) {
            throw new \RuntimeException('Belum ada kategori berita. Tambahkan kategori terlebih dahulu.');
        }

        return $fallback;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $base = $base !== '' ? $base : 'berita';

        do {
            $slug = $base . '-' . Str::random(5);
        } while (Berita::query()->where('slug', $slug)->exists());

        return $slug;
    }

    private function buildBriefFromTitle(string $title): string
    {
        return trim(implode("\n", [
            'Judul yang diinginkan: ' . $title,
            'Kembangkan menjadi berita resmi sekolah yang faktual dan mudah dipahami.',
            'Gunakan bahasa Indonesia baku dengan nada informatif.',
            'Sertakan konteks kegiatan, pihak terlibat, manfaat kegiatan, dan penutup yang jelas.',
        ]));
    }
}
