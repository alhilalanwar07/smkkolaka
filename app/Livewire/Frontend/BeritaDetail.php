<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Layout('components.layouts.app')]
class BeritaDetail extends Component
{
    public Berita $berita;

    public function mount(string $slug)
    {
        $this->berita = Berita::published()
            ->with(['kategori', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $this->berita->incrementViewCount();
    }

    public function render()
    {
        $related = Berita::published()
            ->where('id', '!=', $this->berita->id)
            ->where('kategori_id', $this->berita->kategori_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $plainContent = trim(preg_replace('/\s+/', ' ', strip_tags($this->berita->konten_html)));
        $metaDescription = Str::limit($plainContent, 160, '...');
        $readingMinutes = max(1, (int) ceil(str_word_count($plainContent) / 200));
        $canonicalUrl = route('berita.show', $this->berita->slug);
        $shareImage = $this->berita->gambar_thumbnail
            ? url(Storage::url($this->berita->gambar_thumbnail))
            : asset('favicon.ico');
        $metaKeywords = collect([
            $this->berita->judul,
            $this->berita->kategori?->nama_kategori,
            'berita sekolah',
            'SMK Negeri 1 Kolaka',
        ])->filter()->implode(', ');
        $publishedAt = optional($this->berita->published_at)->toIso8601String();
        $updatedAt = optional($this->berita->updated_at)->toIso8601String();
        $structuredData = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $this->berita->judul,
            'description' => $metaDescription,
            'image' => [$shareImage],
            'datePublished' => $publishedAt,
            'dateModified' => $updatedAt,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonicalUrl,
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $this->berita->user?->name ?? 'Administrator',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'SMK Negeri 1 Kolaka',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('favicon.ico'),
                ],
            ],
            'articleSection' => $this->berita->kategori?->nama_kategori,
            'wordCount' => str_word_count($plainContent),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('livewire.frontend.berita-detail', compact(
            'related',
            'metaDescription',
            'readingMinutes',
            'canonicalUrl',
            'shareImage'
        ))
            ->title($this->berita->judul . ' - SMK Negeri 1 Kolaka')
            ->layoutData([
                'metaDescription' => $metaDescription,
                'metaKeywords' => $metaKeywords,
                'canonicalUrl' => $canonicalUrl,
                'metaRobots' => 'index,follow,max-image-preview:large',
                'ogType' => 'article',
                'ogTitle' => $this->berita->judul,
                'ogDescription' => $metaDescription,
                'ogImage' => $shareImage,
                'ogUrl' => $canonicalUrl,
                'twitterCard' => 'summary_large_image',
                'twitterTitle' => $this->berita->judul,
                'twitterDescription' => $metaDescription,
                'twitterImage' => $shareImage,
                'articlePublishedTime' => $publishedAt,
                'articleModifiedTime' => $updatedAt,
                'structuredData' => $structuredData,
            ]);
    }
}
