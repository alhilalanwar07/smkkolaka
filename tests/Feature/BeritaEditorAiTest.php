<?php

namespace Tests\Feature;

use App\Livewire\Admin\BeritaEditor;
use App\Models\KategoriBerita;
use App\Models\User;
use App\Support\NvidiaNewsGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class BeritaEditorAiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_generation_fills_the_berita_editor_fields(): void
    {
        config()->set('services.nvidia_ai.key', 'test-key');

        Http::fake([
            'https://integrate.api.nvidia.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'judul' => 'Siswa SMKN 1 Kolaka Raih Juara IoT Tingkat Provinsi',
                                'konten_html' => '<p>Tim siswa SMKN 1 Kolaka kembali menorehkan prestasi pada ajang Internet of Things tingkat provinsi.</p><p>Kepala sekolah menyampaikan apresiasi atas kerja keras tim dan pembina.</p><p>Prestasi ini diharapkan memperkuat motivasi belajar siswa lain.</p>',
                            ], JSON_THROW_ON_ERROR),
                        ],
                    ],
                ],
            ]),
        ]);

        $user = User::factory()->create();
        $category = KategoriBerita::create([
            'nama_kategori' => 'Prestasi',
            'slug' => 'prestasi',
        ]);

        Livewire::actingAs($user)
            ->test(BeritaEditor::class)
            ->set('kategori_id', (string) $category->id)
            ->set('ai_prompt', 'Tim siswa TKJ meraih juara 1 lomba IoT tingkat provinsi dan membawa pulang piala untuk sekolah.')
            ->set('ai_tone', 'formal')
            ->set('ai_length', 'sedang')
            ->call('generateWithAi')
            ->assertSet('judul', 'Siswa SMKN 1 Kolaka Raih Juara IoT Tingkat Provinsi')
            ->assertSet('konten_html', '<p>Tim siswa SMKN 1 Kolaka kembali menorehkan prestasi pada ajang Internet of Things tingkat provinsi.</p><p>Kepala sekolah menyampaikan apresiasi atas kerja keras tim dan pembina.</p><p>Prestasi ini diharapkan memperkuat motivasi belajar siswa lain.</p>');
    }

    public function test_nvidia_service_parses_fenced_json_and_sanitizes_html(): void
    {
        config()->set('services.nvidia_ai.key', 'test-key');

        Http::fake([
            'https://integrate.api.nvidia.com/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => <<<'JSON'
```json
{"judul":"Agenda Literasi Semester Genap","konten_html":"<p>Program literasi semester genap resmi dimulai.</p><script>alert('x')</script><p><a href=\"javascript:alert(1)\" onclick=\"alert(1)\">Lihat detail</a></p>"}
```
JSON,
                        ],
                    ],
                ],
            ]),
        ]);

        $result = app(NvidiaNewsGenerator::class)->generate(
            brief: 'Sekolah memulai program literasi semester genap untuk seluruh siswa.',
            categoryName: 'Agenda',
            tone: 'formal',
            length: 'singkat',
        );

        $this->assertSame('Agenda Literasi Semester Genap', $result['judul']);
        $this->assertSame('<p>Program literasi semester genap resmi dimulai.</p><p><a>Lihat detail</a></p>', $result['konten_html']);
    }
}