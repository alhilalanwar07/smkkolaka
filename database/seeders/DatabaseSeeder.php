<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\GaleriAlbum;
use App\Models\GaleriItem;
use App\Models\KategoriBerita;
use App\Models\Pegawai;
use App\Models\PpdbApplication;
use App\Models\PpdbDocument;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use App\Models\PpdbTrack;
use App\Models\Pengumuman;
use App\Models\ProfilSekolah;
use App\Models\ProgramKeahlian;
use App\Models\Role;
use App\Models\Setting;
use App\Models\TefaKategori;
use App\Models\TefaProduk;
use App\Models\User;
use App\Support\PpdbSelectionService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles & Admin ──────────────────────────────
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $ppdbAdminRole = Role::create(['name' => 'ppdb-admin', 'guard_name' => 'web']);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@smkn1kolaka.sch.id',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($adminRole);

        $editor = User::create([
            'name' => 'Editor Konten',
            'email' => 'editor@smkn1kolaka.sch.id',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $editor->roles()->attach($editorRole);

        $ppdbAdmin = User::create([
            'name' => 'Admin PPDB',
            'email' => 'ppdb@smkn1kolaka.sch.id',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $ppdbAdmin->roles()->attach($ppdbAdminRole);

        // ── Profil Sekolah ─────────────────────────────
        ProfilSekolah::create([
            'npsn' => '40402345',
            'nama_sekolah' => 'SMK Negeri 1 Kolaka',
            'alamat_lengkap' => 'Jl. Pemuda No. 12, Kelurahan Laloeha, Kecamatan Kolaka, Kabupaten Kolaka, Sulawesi Tenggara 93511',
            'koordinat_peta' => '-4.0454,121.5906',
            'nomor_telepon' => '(0405) 21234',
            'email_resmi' => 'info@smkn1kolaka.sch.id',
            'tautan_sosmed' => [
                'facebook' => 'https://facebook.com/smkn1kolaka',
                'instagram' => 'https://instagram.com/smkn1kolaka',
                'youtube' => 'https://youtube.com/@smkn1kolaka',
                'tiktok' => 'https://tiktok.com/@smkn1kolaka',
            ],
            'teks_sambutan_kepsek' => 'Assalamu\'alaikum Warahmatullahi Wabarakatuh. Puji syukur kita panjatkan ke hadirat Allah SWT atas segala limpahan rahmat dan karunia-Nya. Selamat datang di website resmi SMK Negeri 1 Kolaka, sekolah vokasi unggulan yang telah berdiri sejak tahun 1965 dan terus berkomitmen menghasilkan lulusan yang kompeten, berkarakter, dan siap bersaing di era global. Kami percaya bahwa setiap siswa memiliki potensi luar biasa yang perlu digali dan dikembangkan. Melalui kurikulum berbasis industri, fasilitas modern, serta tenaga pendidik yang profesional, kami siap menjadi mitra terbaik dalam mewujudkan cita-cita generasi muda Kolaka. Mari bersama-sama kita wujudkan pendidikan vokasi berkualitas untuk Indonesia yang lebih maju.',
            'visi_teks' => 'Menjadi lembaga pendidikan vokasi unggulan yang menghasilkan lulusan beriman, berkarakter, kompeten, dan berdaya saing global pada tahun 2030.',
            'misi_teks' => "1. Menyelenggarakan pendidikan berbasis kompetensi sesuai kebutuhan dunia usaha dan dunia industri.\n2. Membentuk peserta didik yang beriman, bertakwa, dan berakhlak mulia.\n3. Mengembangkan kurikulum yang adaptif terhadap perkembangan teknologi dan industri.\n4. Meningkatkan kerjasama dengan dunia usaha dan dunia industri dalam penyelenggaraan teaching factory.\n5. Mengoptimalkan penggunaan teknologi informasi dalam proses pembelajaran dan manajemen sekolah.\n6. Mengembangkan budaya mutu, inovasi, dan kewirausahaan di lingkungan sekolah.\n7. Menyiapkan lulusan yang mampu bersaing di tingkat nasional dan internasional.",
        ]);

        // ── Settings ───────────────────────────────────
        $settings = [
            ['key' => 'site_name', 'value' => 'SMK Negeri 1 Kolaka', 'type' => 'string'],
            ['key' => 'site_tagline', 'value' => 'Center of Excellence — Sekolah Vokasi Unggulan', 'type' => 'string'],
            ['key' => 'footer_text', 'value' => '© 2026 SMK Negeri 1 Kolaka. All rights reserved.', 'type' => 'string'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
        ];
        foreach ($settings as $s) {
            Setting::create($s);
        }

        // ── Program Keahlian ──────────────────────────
        $jurusans = [
            [
                'kode_jurusan' => 'RPL',
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'deskripsi_lengkap' => '<p>Program Keahlian Rekayasa Perangkat Lunak (RPL) membekali siswa dengan kompetensi dalam merancang, membangun, dan mengelola perangkat lunak. Siswa akan mempelajari bahasa pemrograman modern seperti Python, JavaScript, PHP, dan Java, serta framework populer untuk pengembangan web dan mobile.</p><p>Kurikulum kami dirancang bersama mitra industri teknologi terkemuka untuk memastikan relevansi dengan kebutuhan pasar kerja. Siswa juga akan mendapat pengalaman langsung melalui proyek nyata di Teaching Factory.</p>',
                'fasilitas_unggulan' => "Lab Komputer dengan 40 unit PC Core i7\nLab Jaringan Cisco Academy\nServer Room dedicated\nAkses internet fiber optic 100 Mbps\nLisensi software development profesional",
                'prospek_karir' => "Web Developer\nMobile App Developer\nSoftware Engineer\nDatabase Administrator\nUI/UX Designer\nDevOps Engineer\nFreelance Programmer",
            ],
            [
                'kode_jurusan' => 'TKJ',
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'deskripsi_lengkap' => '<p>Program Keahlian Teknik Komputer dan Jaringan (TKJ) menyiapkan tenaga ahli di bidang infrastruktur IT, jaringan komputer, dan administrasi sistem. Siswa akan mempelajari konfigurasi perangkat jaringan, keamanan siber, cloud computing, dan administrasi server.</p><p>Sebagai Cisco Academy, siswa berkesempatan mendapatkan sertifikasi CCNA yang diakui secara internasional.</p>',
                'fasilitas_unggulan' => "Lab Jaringan Cisco Equipment\nLab Hardware & Troubleshooting\nServer Room dengan rack server enterprise\nPerangkat MikroTik untuk praktik\nWorkshop perakitan komputer",
                'prospek_karir' => "Network Administrator\nSystem Administrator\nIT Support Specialist\nCybersecurity Analyst\nCloud Engineer\nTelecommunication Technician",
            ],
            [
                'kode_jurusan' => 'OTKP',
                'nama_jurusan' => 'Otomatisasi dan Tata Kelola Perkantoran',
                'deskripsi_lengkap' => '<p>Program Keahlian Otomatisasi dan Tata Kelola Perkantoran (OTKP) menghasilkan tenaga profesional di bidang administrasi perkantoran modern. Siswa dibekali kemampuan manajemen dokumen digital, korespondensi bisnis, pengelolaan arsip elektronik, serta pengoperasian peralatan kantor otomatis.</p><p>Program ini sangat cocok bagi siswa yang memiliki ketelitian tinggi, kemampuan komunikasi baik, dan minat di bidang manajemen administrasi.</p>',
                'fasilitas_unggulan' => "Lab Simulasi Perkantoran Modern\nLab Komputer Administrasi\nPeralatan kantor otomatis lengkap\nRuang meeting & presentation room\nSoftware manajemen perkantoran",
                'prospek_karir' => "Administrative Assistant\nOffice Manager\nSekretaris Perusahaan\nHuman Resources Staff\nCustomer Service Officer\nEvent Organizer",
            ],
            [
                'kode_jurusan' => 'AKL',
                'nama_jurusan' => 'Akuntansi dan Keuangan Lembaga',
                'deskripsi_lengkap' => '<p>Program Keahlian Akuntansi dan Keuangan Lembaga (AKL) mencetak tenaga ahli di bidang akuntansi, perpajakan, dan keuangan. Siswa mempelajari pencatatan transaksi keuangan, penyusunan laporan keuangan, pengelolaan pajak, serta penggunaan software akuntansi modern seperti MYOB dan Accurate.</p><p>Lulusan program ini siap bekerja di berbagai sektor keuangan maupun melanjutkan pendidikan ke jenjang yang lebih tinggi.</p>',
                'fasilitas_unggulan' => "Lab Akuntansi Komputer\nSoftware MYOB & Accurate berlisensi\nLab Praktik Perbankan Mini\nKalkulator finansial & alat hitung profesional\nPerpustakaan referensi akuntansi",
                'prospek_karir' => "Akuntan Junior\nStaff Keuangan\nTax Consultant Assistant\nBank Teller\nAuditor Internal\nPayroll Administrator",
            ],
            [
                'kode_jurusan' => 'TBSM',
                'nama_jurusan' => 'Teknik dan Bisnis Sepeda Motor',
                'deskripsi_lengkap' => '<p>Program Keahlian Teknik dan Bisnis Sepeda Motor (TBSM) membekali siswa dengan kompetensi teknis perawatan dan perbaikan sepeda motor serta manajemen bengkel. Program ini bekerja sama langsung dengan Honda dan Yamaha untuk kurikulum yang relevan dengan teknologi otomotif terkini.</p><p>Siswa juga akan mendapat pelatihan manajemen bisnis bengkel agar siap menjadi wirausahawan di bidang otomotif.</p>',
                'fasilitas_unggulan' => "Workshop Praktik Otomotif standar AHASS\nUnit sepeda motor training berbagai merek\nPeralatan diagnosis elektronik\nRuang teori multimedia\nBengkel praktik teaching factory",
                'prospek_karir' => "Mekanik Sepeda Motor\nTeknisi Honda/Yamaha\nPemilik Bengkel\nSales & Marketing Otomotif\nInstruktur Training Center\nParts Counter",
            ],
        ];

        $programIds = [];
        foreach ($jurusans as $j) {
            $pk = ProgramKeahlian::create([
                'kode_jurusan' => $j['kode_jurusan'],
                'nama_jurusan' => $j['nama_jurusan'],
                'slug' => Str::slug($j['nama_jurusan']),
                'deskripsi_lengkap' => $j['deskripsi_lengkap'],
                'fasilitas_unggulan' => $j['fasilitas_unggulan'],
                'prospek_karir' => $j['prospek_karir'],
                'status_tampil' => true,
            ]);
            $programIds[$j['kode_jurusan']] = $pk->id;
        }

        // ── PPDB Foundation ──────────────────────────
        $quotaBlueprint = [
            'RPL' => ['reguler' => 48, 'prestasi' => 16, 'afirmasi' => 8],
            'TKJ' => ['reguler' => 48, 'prestasi' => 16, 'afirmasi' => 8],
            'OTKP' => ['reguler' => 24, 'prestasi' => 8, 'afirmasi' => 4],
            'AKL' => ['reguler' => 24, 'prestasi' => 8, 'afirmasi' => 4],
            'TBSM' => ['reguler' => 48, 'prestasi' => 16, 'afirmasi' => 8],
        ];

        $createTracksAndQuotas = function (PpdbPeriod $period) use ($programIds, $quotaBlueprint) {
            $tracks = [
                'reguler' => PpdbTrack::create([
                    'period_id' => $period->id,
                    'nama_jalur' => 'Jalur Reguler',
                    'slug' => 'reguler',
                    'deskripsi' => 'Jalur umum berbasis nilai rapor, kelengkapan berkas, dan verifikasi panitia.',
                    'urutan' => 1,
                ]),
                'prestasi' => PpdbTrack::create([
                    'period_id' => $period->id,
                    'nama_jalur' => 'Jalur Prestasi',
                    'slug' => 'prestasi',
                    'deskripsi' => 'Jalur khusus untuk calon siswa dengan prestasi akademik atau non-akademik.',
                    'urutan' => 2,
                ]),
                'afirmasi' => PpdbTrack::create([
                    'period_id' => $period->id,
                    'nama_jalur' => 'Jalur Afirmasi',
                    'slug' => 'afirmasi',
                    'deskripsi' => 'Jalur afirmasi dengan verifikasi dokumen pendukung sesuai kebijakan sekolah.',
                    'urutan' => 3,
                ]),
            ];

            foreach ($quotaBlueprint as $kode => $items) {
                foreach ($items as $trackKey => $kuota) {
                    PpdbQuota::create([
                        'period_id' => $period->id,
                        'track_id' => $tracks[$trackKey]->id,
                        'program_keahlian_id' => $programIds[$kode],
                        'kuota' => $kuota,
                        'kuota_terisi' => 0,
                        'status_aktif' => true,
                    ]);
                }
            }

            return $tracks;
        };

        $archivedPeriod = PpdbPeriod::create([
            'nama_periode' => 'PPDB Gelombang 1 2026/2027',
            'tahun_ajaran' => '2026/2027',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'gelombang_ke' => 1,
            'gelombang_label' => 'Gelombang 1',
            'tanggal_mulai_pendaftaran' => now()->subMonths(3)->toDateString(),
            'tanggal_selesai_pendaftaran' => now()->subMonths(2)->toDateString(),
            'tanggal_pengumuman' => now()->subMonths(2)->addDays(7)->toDateString(),
            'tanggal_mulai_daftar_ulang' => now()->subMonths(2)->addDays(8)->toDateString(),
            'tanggal_selesai_daftar_ulang' => now()->subMonths(2)->addDays(15)->toDateString(),
            'deskripsi' => 'Gelombang awal PPDB tahun ajaran 2026/2027.',
            'status' => 'archived',
            'status_pengumuman' => 'published',
            'hasil_diumumkan_at' => now()->subMonths(2),
            'catatan_pengumuman' => 'Arsip hasil gelombang pertama.',
            'is_active' => false,
        ]);

        $createTracksAndQuotas($archivedPeriod);

        $period = PpdbPeriod::create([
            'nama_periode' => 'PPDB Gelombang 2 2026/2027',
            'tahun_ajaran' => '2026/2027',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'gelombang_ke' => 2,
            'gelombang_label' => 'Gelombang 2',
            'tanggal_mulai_pendaftaran' => now()->subDays(5)->toDateString(),
            'tanggal_selesai_pendaftaran' => now()->addDays(60)->toDateString(),
            'tanggal_pengumuman' => now()->addDays(75)->toDateString(),
            'tanggal_mulai_daftar_ulang' => now()->subDay()->toDateString(),
            'tanggal_selesai_daftar_ulang' => now()->addDays(7)->toDateString(),
            'deskripsi' => 'Gelombang lanjutan PPDB untuk calon peserta didik baru SMK Negeri 1 Kolaka tahun ajaran 2026/2027.',
            'status' => 'published',
            'status_pengumuman' => 'published',
            'hasil_diumumkan_at' => now()->subHours(2),
            'catatan_pengumuman' => 'Hasil resmi PPDB diumumkan melalui portal sekolah. Peserta yang lulus wajib melakukan daftar ulang sesuai jadwal.',
            'is_active' => true,
        ]);

        $tracks = $createTracksAndQuotas($period);

        $futurePeriod = PpdbPeriod::create([
            'nama_periode' => 'PPDB Gelombang 1 2027/2028',
            'tahun_ajaran' => '2027/2028',
            'tahun_mulai' => 2027,
            'tahun_selesai' => 2028,
            'gelombang_ke' => 1,
            'gelombang_label' => 'Gelombang 1',
            'tanggal_mulai_pendaftaran' => now()->addMonths(9)->toDateString(),
            'tanggal_selesai_pendaftaran' => now()->addMonths(11)->toDateString(),
            'tanggal_pengumuman' => now()->addYear()->toDateString(),
            'tanggal_mulai_daftar_ulang' => now()->addYear()->addDay()->toDateString(),
            'tanggal_selesai_daftar_ulang' => now()->addYear()->addWeek()->toDateString(),
            'deskripsi' => 'Periode awal untuk persiapan PPDB tahun ajaran 2027/2028.',
            'status' => 'published',
            'status_pengumuman' => 'draft',
            'hasil_diumumkan_at' => null,
            'catatan_pengumuman' => null,
            'is_active' => false,
        ]);

        $createTracksAndQuotas($futurePeriod);

        $sampleApplicants = [
            [
                'nama_lengkap' => 'Muhammad Alif Pratama',
                'nisn' => '0098712345',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Kolaka',
                'tanggal_lahir' => '2010-04-10',
                'alamat_lengkap' => 'Jl. Pemuda Baru No. 7, Kolaka',
                'nomor_hp' => '081340001111',
                'asal_sekolah' => 'SMP Negeri 1 Kolaka',
                'track' => 'reguler',
                'pilihan_1' => 'RPL',
                'pilihan_2' => 'TKJ',
                'status_pendaftaran' => 'submitted',
                'status_berkas' => 'pending',
                'skor_tes_dasar' => 78,
                'skor_wawancara' => 80,
            ],
            [
                'nama_lengkap' => 'Sitti Aulia Rahma',
                'nisn' => '0098712355',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Kolaka',
                'tanggal_lahir' => '2010-07-18',
                'alamat_lengkap' => 'Jl. Pendidikan No. 15, Kolaka',
                'nomor_hp' => '081340002222',
                'asal_sekolah' => 'MTs Negeri Kolaka',
                'track' => 'prestasi',
                'pilihan_1' => 'AKL',
                'pilihan_2' => 'OTKP',
                'status_pendaftaran' => 'needs_revision',
                'status_berkas' => 'revision',
                'skor_prestasi' => 88,
                'skor_tes_dasar' => 81,
                'skor_wawancara' => 84,
            ],
            [
                'nama_lengkap' => 'La Ode Fajar Hidayat',
                'nisn' => '0098712365',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Kolaka',
                'tanggal_lahir' => '2010-01-22',
                'alamat_lengkap' => 'Jl. Veteran Lorong 3, Kolaka',
                'nomor_hp' => '081340003333',
                'asal_sekolah' => 'SMP Negeri 3 Kolaka',
                'track' => 'afirmasi',
                'pilihan_1' => 'TBSM',
                'pilihan_2' => 'TKJ',
                'status_pendaftaran' => 'verified',
                'status_berkas' => 'verified',
                'skor_afirmasi' => 91,
                'skor_tes_dasar' => 79,
                'skor_wawancara' => 83,
            ],
            [
                'nama_lengkap' => 'Nur Asmi Cahyani',
                'nisn' => '0098712375',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Pomalaa',
                'tanggal_lahir' => '2010-11-03',
                'alamat_lengkap' => 'Jl. Mekar Sari No. 4, Pomalaa',
                'nomor_hp' => '081340004444',
                'asal_sekolah' => 'SMP Negeri 2 Pomalaa',
                'track' => 'reguler',
                'pilihan_1' => 'TKJ',
                'pilihan_2' => 'RPL',
                'status_pendaftaran' => 'under_review',
                'status_berkas' => 'complete',
                'skor_tes_dasar' => 86,
                'skor_wawancara' => 87,
            ],
            [
                'nama_lengkap' => 'Rahmat Saputra',
                'nisn' => '0098712385',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Kolaka Timur',
                'tanggal_lahir' => '2010-06-29',
                'alamat_lengkap' => 'Desa Lambandia, Kolaka Timur',
                'nomor_hp' => '081340005555',
                'asal_sekolah' => 'SMP Negeri 1 Lambandia',
                'track' => 'prestasi',
                'pilihan_1' => 'RPL',
                'pilihan_2' => 'AKL',
                'status_pendaftaran' => 'accepted',
                'status_berkas' => 'verified',
                'skor_prestasi' => 95,
                'skor_tes_dasar' => 90,
                'skor_wawancara' => 92,
            ],
            [
                'nama_lengkap' => 'Wa Ode Melati Safitri',
                'nisn' => '0098712395',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Kolaka',
                'tanggal_lahir' => '2010-02-14',
                'alamat_lengkap' => 'Jl. Mangga Raya No. 11, Kolaka',
                'nomor_hp' => '081340006666',
                'asal_sekolah' => 'SMP Negeri 4 Kolaka',
                'track' => 'afirmasi',
                'pilihan_1' => 'OTKP',
                'pilihan_2' => 'AKL',
                'status_pendaftaran' => 'rejected',
                'status_berkas' => 'verified',
                'skor_afirmasi' => 72,
                'skor_tes_dasar' => 69,
                'skor_wawancara' => 74,
            ],
        ];

        foreach ($sampleApplicants as $index => $applicant) {
            $application = PpdbApplication::create([
                'period_id' => $period->id,
                'track_id' => $tracks[$applicant['track']]->id,
                'nomor_pendaftaran' => 'PPDB-' . now()->format('Y') . '-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'nama_lengkap' => $applicant['nama_lengkap'],
                'nisn' => $applicant['nisn'],
                'jenis_kelamin' => $applicant['jenis_kelamin'],
                'tempat_lahir' => $applicant['tempat_lahir'],
                'tanggal_lahir' => $applicant['tanggal_lahir'],
                'agama' => 'Islam',
                'alamat_lengkap' => $applicant['alamat_lengkap'],
                'nomor_hp' => $applicant['nomor_hp'],
                'email' => 'calon' . ($index + 1) . '@mail.com',
                'asal_sekolah' => $applicant['asal_sekolah'],
                'nama_ayah' => 'Ayah ' . explode(' ', $applicant['nama_lengkap'])[0],
                'pekerjaan_ayah' => 'Wiraswasta',
                'nama_ibu' => 'Ibu ' . explode(' ', $applicant['nama_lengkap'])[0],
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'nomor_hp_orang_tua' => '08134000999' . $index,
                'pilihan_program_1_id' => $programIds[$applicant['pilihan_1']],
                'pilihan_program_2_id' => $programIds[$applicant['pilihan_2']],
                'nilai_rata_rata' => 84 + ($index * 2),
                'skor_akademik' => 84 + ($index * 2),
                'skor_prestasi' => $applicant['skor_prestasi'] ?? null,
                'skor_afirmasi' => $applicant['skor_afirmasi'] ?? null,
                'skor_tes_dasar' => $applicant['skor_tes_dasar'] ?? null,
                'skor_wawancara' => $applicant['skor_wawancara'] ?? null,
                'skor_berkas' => in_array($applicant['status_berkas'], ['verified', 'complete'], true) ? 90 : 55,
                'catatan_pendaftar' => 'Pendaftar dummy untuk pengujian modul fase 1 PPDB.',
                'catatan_verifikator' => match ($applicant['status_pendaftaran']) {
                    'needs_revision' => 'Scan rapor kurang jelas, mohon upload ulang.',
                    'accepted' => 'Lolos tahap verifikasi administrasi dan direkomendasikan diterima.',
                    'rejected' => 'Kuota jalur penuh dan dokumen afirmasi tidak memenuhi syarat prioritas.',
                    'verified' => 'Seluruh berkas valid dan siap masuk tahap penetapan.',
                    default => null,
                },
                'status_pendaftaran' => $applicant['status_pendaftaran'],
                'status_berkas' => $applicant['status_berkas'],
                'submitted_at' => now()->subDays(2 - min($index, 2)),
                'verified_at' => in_array($applicant['status_pendaftaran'], ['verified', 'accepted', 'rejected'], true) ? now()->subDay() : null,
                'verified_by' => in_array($applicant['status_pendaftaran'], ['verified', 'accepted', 'rejected', 'needs_revision'], true) ? $ppdbAdmin->id : null,
            ]);

            foreach (['Kartu Keluarga', 'Akta Kelahiran', 'Rapor / Nilai', 'Pas Foto'] as $jenisDokumen) {
                PpdbDocument::create([
                    'application_id' => $application->id,
                    'jenis_dokumen' => $jenisDokumen,
                    'file_path' => 'ppdb/sample-' . Str::slug($jenisDokumen) . '-' . ($index + 1) . '.pdf',
                    'status_verifikasi' => match ($applicant['status_pendaftaran']) {
                        'needs_revision' => 'revision',
                        'verified', 'accepted', 'rejected' => 'approved',
                        default => 'pending',
                    },
                    'catatan_verifikasi' => $applicant['status_pendaftaran'] === 'needs_revision' ? 'Perlu unggah ulang dokumen ini.' : null,
                ]);
            }
        }

        app(PpdbSelectionService::class)->processPeriod($period);

        $acceptedForReRegistration = PpdbApplication::where('period_id', $period->id)
            ->where('hasil_seleksi', 'passed')
            ->orderBy('skor_seleksi', 'desc')
            ->first();

        if ($acceptedForReRegistration) {
            $acceptedForReRegistration->update([
                'status_daftar_ulang' => 'verified',
                'daftar_ulang_at' => now()->subHour(),
                'catatan_daftar_ulang' => 'Daftar ulang telah diverifikasi oleh panitia.',
                'verified_daftar_ulang_by' => $ppdbAdmin->id,
                'verified_daftar_ulang_at' => now()->subMinutes(30),
            ]);
        }

        // ── Pegawai ───────────────────────────────────
        $pegawaiData = [
            ['nama_lengkap' => 'Drs. H. Muhammad Arfan, M.Pd.', 'jabatan' => 'Kepala Sekolah', 'nip' => '196508151990031012', 'bidang_tugas' => 'Pimpinan'],
            ['nama_lengkap' => 'Hj. Sitti Rahmawati, S.Pd., M.Si.', 'jabatan' => 'Wakil Kepala Sekolah Bidang Kurikulum', 'nip' => '197204231998022003', 'bidang_tugas' => 'Kurikulum'],
            ['nama_lengkap' => 'Ir. Abdul Kadir, M.T.', 'jabatan' => 'Wakil Kepala Sekolah Bidang Sarana', 'nip' => '196912101995031005', 'bidang_tugas' => 'Sarana Prasarana'],
            ['nama_lengkap' => 'Drs. La Ode Hasanuddin', 'jabatan' => 'Wakil Kepala Sekolah Bidang Kesiswaan', 'nip' => '197001051997031008', 'bidang_tugas' => 'Kesiswaan'],
            ['nama_lengkap' => 'Wa Ode Nurhaliza, S.Kom., M.Cs.', 'jabatan' => 'Kepala Program RPL', 'nip' => '198506102010032015', 'bidang_tugas' => 'Rekayasa Perangkat Lunak'],
            ['nama_lengkap' => 'Andi Firmansyah, S.Kom.', 'jabatan' => 'Kepala Program TKJ', 'nip' => '198709222012031009', 'bidang_tugas' => 'Teknik Komputer & Jaringan'],
            ['nama_lengkap' => 'Hj. Nurhayati, S.Pd.', 'jabatan' => 'Kepala Program OTKP', 'nip' => '197805142003022006', 'bidang_tugas' => 'Otomatisasi Perkantoran'],
            ['nama_lengkap' => 'Muh. Reza Pahlevi, S.E., M.Ak.', 'jabatan' => 'Kepala Program AKL', 'nip' => '198203172007011004', 'bidang_tugas' => 'Akuntansi & Keuangan'],
            ['nama_lengkap' => 'La Ode Safrudin, S.T.', 'jabatan' => 'Kepala Program TBSM', 'nip' => '198801282011011007', 'bidang_tugas' => 'Teknik Sepeda Motor'],
            ['nama_lengkap' => 'Sitti Aminah, S.Pd.', 'jabatan' => 'Guru Bahasa Indonesia', 'nip' => '198005232005022003', 'bidang_tugas' => 'Normatif'],
            ['nama_lengkap' => 'Drs. Ahmad Yani', 'jabatan' => 'Guru Matematika', 'nip' => '196711201993031010', 'bidang_tugas' => 'Normatif'],
            ['nama_lengkap' => 'Fitriani Putri, S.Pd., M.Pd.', 'jabatan' => 'Guru Bahasa Inggris', 'nip' => '199102142016032008', 'bidang_tugas' => 'Normatif'],
            ['nama_lengkap' => 'La Ode Rahman, S.Ag.', 'jabatan' => 'Guru Pendidikan Agama Islam', 'nip' => '197503181999031011', 'bidang_tugas' => 'Normatif'],
            ['nama_lengkap' => 'Nur Hidayat, S.Pd.', 'jabatan' => 'Guru PJOK', 'nip' => '198607092010031012', 'bidang_tugas' => 'Normatif'],
            ['nama_lengkap' => 'Wa Ode Siti Mariam, S.Pd.', 'jabatan' => 'Guru BK', 'nip' => '198904152013032007', 'bidang_tugas' => 'Bimbingan Konseling'],
            ['nama_lengkap' => 'Hasan Basri, S.Sos.', 'jabatan' => 'Kepala Tata Usaha', 'nip' => '197209101996031005', 'bidang_tugas' => 'Tata Usaha'],
        ];

        foreach ($pegawaiData as $p) {
            Pegawai::create([
                'nip' => $p['nip'],
                'nama_lengkap' => $p['nama_lengkap'],
                'jabatan' => $p['jabatan'],
                'bidang_tugas' => $p['bidang_tugas'],
                'status_aktif' => true,
            ]);
        }

        // ── Kategori Berita ───────────────────────────
        $kategoriBerita = [
            'Kegiatan Sekolah', 'Prestasi', 'Pengumuman Resmi', 'PPDB',
            'Kerjasama Industri', 'Teknologi', 'Olahraga', 'Seni & Budaya',
        ];
        $katIds = [];
        foreach ($kategoriBerita as $kb) {
            $k = KategoriBerita::create([
                'nama_kategori' => $kb,
                'slug' => Str::slug($kb),
            ]);
            $katIds[] = $k->id;
        }

        // ── Berita ────────────────────────────────────
        $beritaData = [
            [
                'judul' => 'SMKN 1 Kolaka Raih Juara Umum LKS Tingkat Provinsi Sulawesi Tenggara 2026',
                'kategori_idx' => 1,
                'konten' => '<p>SMK Negeri 1 Kolaka kembali mengukir prestasi gemilang dengan meraih juara umum dalam ajang Lomba Kompetensi Siswa (LKS) tingkat Provinsi Sulawesi Tenggara tahun 2026 yang diselenggarakan di Kota Kendari.</p><p>Dari total 12 bidang lomba yang diikuti, siswa-siswi SMKN 1 Kolaka berhasil membawa pulang 5 medali emas, 4 medali perak, dan 3 medali perunggu. Prestasi ini menjadi yang terbaik dalam sejarah keikutsertaan sekolah di ajang LKS.</p><p>"Keberhasilan ini tidak lepas dari kerja keras siswa, dedikasi guru pembimbing, dan dukungan penuh dari semua stakeholder sekolah," ujar Kepala Sekolah Drs. H. Muhammad Arfan, M.Pd. saat konfrensi pers di aula sekolah.</p><p>Beberapa bidang lomba yang berhasil diraih medali emas antara lain Web Technologies, IT Network Systems Administration, Graphic Design Technology, Accounting, dan Office Administration.</p>',
                'days_ago' => 2,
            ],
            [
                'judul' => 'Penandatanganan MoU dengan PT Telkom Indonesia untuk Program Magang Siswa',
                'kategori_idx' => 4,
                'konten' => '<p>SMK Negeri 1 Kolaka resmi menandatangani nota kesepahaman (MoU) dengan PT Telkom Indonesia Tbk untuk program magang siswa dan pengembangan kurikulum berbasis industri. Penandatanganan dilakukan oleh Kepala Sekolah dan General Manager Telkom Regional VII Sulawesi.</p><p>Melalui kerja sama ini, siswa jurusan TKJ dan RPL akan mendapat kesempatan magang selama 6 bulan di kantor Telkom dan anak perusahaannya. Selain itu, Telkom juga akan memberikan pelatihan sertifikasi bagi guru-guru produktif.</p><p>"Ini adalah langkah strategis untuk memastikan lulusan kami memiliki kompetensi yang benar-benar dibutuhkan industri telekomunikasi dan IT," jelas Wa Ode Nurhaliza, S.Kom., M.Cs., Kaprog RPL.</p>',
                'days_ago' => 5,
            ],
            [
                'judul' => 'Workshop Internet of Things (IoT) Bersama Dosen ITB untuk Siswa Jurusan TKJ',
                'kategori_idx' => 5,
                'konten' => '<p>Dalam rangka meningkatkan kompetensi siswa di bidang teknologi terkini, SMK Negeri 1 Kolaka menggelar workshop Internet of Things (IoT) yang menghadirkan narasumber langsung dari Institut Teknologi Bandung (ITB).</p><p>Workshop yang berlangsung selama tiga hari ini diikuti oleh 60 siswa jurusan TKJ kelas XI dan XII. Para siswa belajar merancang dan membangun prototipe sistem IoT menggunakan mikrokontroler ESP32, sensor-sensor lingkungan, dan platform cloud untuk monitoring data secara real-time.</p><p>Dr. Ir. Budi Santoso dari Departemen Teknik Elektro ITB menyatakan kekagumannya terhadap antusiasme dan kemampuan dasar siswa SMKN 1 Kolaka. "Saya senang melihat siswa-siswa di sini sudah memiliki fondasi yang kuat. Dengan bimbingan yang tepat, mereka bisa menjadi inovator di bidang IoT," ujarnya.</p>',
                'days_ago' => 8,
            ],
            [
                'judul' => 'Tim Futsal SMKN 1 Kolaka Juara 1 Turnamen Antar SMK Se-Sulawesi Tenggara',
                'kategori_idx' => 6,
                'konten' => '<p>Tim futsal SMK Negeri 1 Kolaka berhasil menggondol trofi juara pertama dalam Turnamen Futsal Antar SMK Se-Sulawesi Tenggara 2026 yang dihelat di GOR Bahteramas Kendari.</p><p>Dalam pertandingan final yang berlangsung sengit, tim SMKN 1 Kolaka mengalahkan SMKN 2 Kendari dengan skor 4-2. Gol-gol kemenangan dicetak oleh Andi Pratama (2 gol), La Ode Faisal, dan Muh. Rizky.</p><p>Pelatih tim, Nur Hidayat, S.Pd., menyatakan bangga dengan perjuangan anak-anak didiknya. "Mereka berlatih sangat disiplin selama tiga bulan terakhir. Kemenangan ini adalah buah dari kerja keras mereka," katanya.</p>',
                'days_ago' => 12,
            ],
            [
                'judul' => 'Pengumuman Jadwal dan Syarat Pendaftaran PPDB 2026/2027 SMK Negeri 1 Kolaka',
                'kategori_idx' => 3,
                'konten' => '<p>SMK Negeri 1 Kolaka membuka pendaftaran peserta didik baru (PPDB) tahun ajaran 2026/2027. Pendaftaran dibuka mulai tanggal 1 April hingga 30 Juni 2026 melalui sistem online dan offline.</p><p><strong>Persyaratan Umum:</strong></p><ul><li>Ijazah atau Surat Keterangan Lulus SMP/MTs sederajat</li><li>Rapor semester 1-5 SMP/MTs</li><li>Akta kelahiran</li><li>Kartu Keluarga</li><li>Pas foto 3x4 sebanyak 4 lembar</li><li>Surat keterangan sehat dari dokter</li></ul><p><strong>Daya Tampung:</strong></p><ul><li>RPL: 72 siswa (2 rombel)</li><li>TKJ: 72 siswa (2 rombel)</li><li>OTKP: 36 siswa (1 rombel)</li><li>AKL: 36 siswa (1 rombel)</li><li>TBSM: 72 siswa (2 rombel)</li></ul>',
                'days_ago' => 1,
            ],
            [
                'judul' => 'Siswa RPL Berhasil Develop Aplikasi E-Kantin yang Digunakan Seluruh Warga Sekolah',
                'kategori_idx' => 5,
                'konten' => '<p>Sebuah inovasi membanggakan lahir dari tangan siswa jurusan Rekayasa Perangkat Lunak (RPL) kelas XII. Tim yang terdiri dari Muh. Farhan, Sitti Aisyah, dan La Ode Akbar berhasil mengembangkan aplikasi E-Kantin yang kini digunakan oleh seluruh warga sekolah.</p><p>Aplikasi berbasis web dan mobile ini memungkinkan siswa dan guru untuk memesan makanan dari kantin sekolah secara digital, melakukan pembayaran cashless, dan memantau status pesanan secara real-time.</p><p>"Aplikasi ini lahir dari proyek teaching factory di semester 5. Kami sangat bangga karena ternyata hasilnya benar-benar bermanfaat dan digunakan sehari-hari," ungkap Muh. Farhan, ketua tim pengembang.</p>',
                'days_ago' => 15,
            ],
            [
                'judul' => 'Peringatan Hari Pendidikan Nasional: SMKN 1 Kolaka Gelar Upacara dan Pentas Seni',
                'kategori_idx' => 7,
                'konten' => '<p>Dalam rangka memperingati Hari Pendidikan Nasional (Hardiknas) 2026, SMK Negeri 1 Kolaka menggelar rangkaian kegiatan yang meriah, dimulai dari upacara bendera, lomba antar kelas, hingga pentas seni budaya.</p><p>Upacara peringatan Hardiknas dipimpin langsung oleh Kepala Sekolah dengan pembacaan pidato Menteri Pendidikan. Seluruh civitas akademika hadir dengan khidmat mengenakan pakaian adat daerah masing-masing.</p><p>Acara dilanjutkan dengan pentas seni yang menampilkan berbagai kesenian daerah Sulawesi Tenggara seperti tari Lulo, musik Gambus, dan drama musikal bertema pendidikan. Festival ini menjadi ajang unjuk bakat sekaligus memperkuat kecintaan terhadap budaya lokal.</p>',
                'days_ago' => 20,
            ],
            [
                'judul' => 'Teaching Factory AKL: Siswa Kelola Pembukuan UMKM Binaan Sekolah',
                'kategori_idx' => 0,
                'konten' => '<p>Program Teaching Factory jurusan Akuntansi dan Keuangan Lembaga (AKL) SMK Negeri 1 Kolaka memasuki babak baru dengan peluncuran program pendampingan pembukuan untuk 15 UMKM binaan sekolah di sekitar Kabupaten Kolaka.</p><p>Siswa kelas XI dan XII AKL akan secara langsung membantu para pelaku UMKM dalam menyusun laporan keuangan sederhana, menghitung pajak, dan menggunakan aplikasi akuntansi. Program ini berlangsung selama satu semester penuh.</p><p>"Ini adalah pengalaman belajar yang tidak bisa didapat dari buku. Siswa langsung berhadapan dengan kasus nyata dan belajar berkomunikasi dengan klien," jelas Muh. Reza Pahlevi, S.E., M.Ak., Kaprog AKL.</p>',
                'days_ago' => 25,
            ],
            [
                'judul' => 'SMKN 1 Kolaka Terima Kunjungan Benchmarking dari 5 SMK Se-Sulawesi',
                'kategori_idx' => 0,
                'konten' => '<p>SMK Negeri 1 Kolaka menerima kunjungan studi banding (benchmarking) dari lima SMK yang tersebar di Sulawesi, yaitu SMKN 2 Kendari, SMKN 1 Makassar, SMKN 3 Palu, SMKN 1 Gorontalo, dan SMKN 2 Manado.</p><p>Kunjungan ini bertujuan untuk berbagi praktik terbaik dalam pengelolaan sekolah, implementasi teaching factory, dan strategi peningkatan mutu lulusan. Delegasi dari kelima sekolah mendapat kesempatan berkeliling melihat fasilitas laboratorium, workshop, dan unit produksi.</p><p>"Kami sangat terinspirasi dengan apa yang sudah dicapai SMKN 1 Kolaka, terutama dalam hal teaching factory dan kerjasama industri," ungkap salah satu kepala sekolah peserta benchmarking.</p>',
                'days_ago' => 30,
            ],
            [
                'judul' => 'Pelatihan Cybersecurity untuk Guru TKJ dari BSSN (Badan Siber dan Sandi Negara)',
                'kategori_idx' => 5,
                'konten' => '<p>Guru-guru produktif jurusan Teknik Komputer dan Jaringan (TKJ) SMK Negeri 1 Kolaka mengikuti pelatihan intensif Cybersecurity yang diselenggarakan bekerja sama dengan Badan Siber dan Sandi Negara (BSSN).</p><p>Pelatihan selama lima hari ini mencakup materi ethical hacking, network security, digital forensics, dan incident response. Para guru mendapat sertifikat kompetensi yang akan meningkatkan kualitas pengajaran di kelas.</p><p>"Keamanan siber adalah topik yang sangat penting di era digital ini. Dengan pelatihan ini, guru-guru kami bisa menyampaikan materi yang up-to-date kepada siswa," kata Andi Firmansyah, S.Kom., Kaprog TKJ.</p>',
                'days_ago' => 35,
            ],
        ];

        foreach ($beritaData as $b) {
            Berita::create([
                'user_id' => $admin->id,
                'kategori_id' => $katIds[$b['kategori_idx']],
                'judul' => $b['judul'],
                'slug' => Str::slug($b['judul']),
                'konten_html' => $b['konten'],
                'status_publikasi' => 'published',
                'view_count' => rand(50, 500),
                'published_at' => now()->subDays($b['days_ago']),
            ]);
        }

        // ── Pengumuman ────────────────────────────────
        $pengumumanData = [
            ['judul' => 'Pendaftaran Peserta Didik Baru (PPDB) 2026/2027 Telah Dibuka!', 'isi' => 'Pendaftaran PPDB SMK Negeri 1 Kolaka untuk tahun ajaran 2026/2027 resmi dibuka mulai 1 April 2026. Segera daftarkan diri Anda melalui website resmi atau datang langsung ke sekolah. Info lebih lanjut hubungi panitia PPDB.', 'mulai' => 0, 'akhir' => 90],
            ['judul' => 'Ujian Akhir Semester Genap Dimulai 20 Maret 2026', 'isi' => 'Diberitahukan kepada seluruh siswa bahwa Ujian Akhir Semester (UAS) Genap akan dilaksanakan mulai tanggal 20 Maret hingga 31 Maret 2026. Harap mempersiapkan diri dengan baik. Jadwal lengkap dapat dilihat di papan pengumuman dan website sekolah.', 'mulai' => -5, 'akhir' => 20],
            ['judul' => 'Pengumpulan Berkas Magang Industri Kelas XI Paling Lambat 25 Maret 2026', 'isi' => 'Seluruh siswa kelas XI yang akan mengikuti program Praktik Kerja Lapangan (PKL)/Magang Industri diwajibkan mengumpulkan berkas persyaratan paling lambat tanggal 25 Maret 2026 ke masing-masing wali kelas.', 'mulai' => -10, 'akhir' => 15],
            ['judul' => 'Libur Hari Raya Nyepi dan Ramadhan 1447 H', 'isi' => 'Sehubungan dengan Hari Raya Nyepi dan memasuki bulan suci Ramadhan, kegiatan belajar mengajar diliburkan mulai 28 Maret - 30 Maret 2026. KBM kembali normal pada Senin, 31 Maret 2026.', 'mulai' => -3, 'akhir' => 18],
            ['judul' => 'Pendaftaran Sertifikasi Kompetensi BNSP Batch Maret 2026', 'isi' => 'Pendaftaran uji sertifikasi kompetensi BNSP untuk siswa kelas XII semua jurusan telah dibuka. Biaya sertifikasi GRATIS ditanggung sekolah. Segera daftarkan diri ke kepala program masing-masing.', 'mulai' => -7, 'akhir' => 25],
        ];

        foreach ($pengumumanData as $p) {
            Pengumuman::create([
                'user_id' => $admin->id,
                'judul_pengumuman' => $p['judul'],
                'slug' => Str::slug($p['judul']),
                'isi_pengumuman' => $p['isi'],
                'tanggal_mulai_tampil' => now()->addDays($p['mulai']),
                'tanggal_akhir_tampil' => now()->addDays($p['akhir']),
            ]);
        }

        // ── Agenda ────────────────────────────────────
        $agendaData = [
            ['nama' => 'Upacara Peringatan Hari Pendidikan Nasional', 'deskripsi' => 'Upacara bendera dalam rangka memperingati Hari Pendidikan Nasional 2 Mei 2026. Seluruh warga sekolah wajib hadir.', 'lokasi' => 'Lapangan Utama SMKN 1 Kolaka', 'mulai' => 48, 'durasi' => 3, 'kat' => 'umum'],
            ['nama' => 'Workshop Pembuatan Website dengan Laravel', 'deskripsi' => 'Workshop intensif bagi siswa RPL tentang pengembangan web modern menggunakan framework Laravel dan Livewire.', 'lokasi' => 'Lab RPL Gedung B Lt. 2', 'mulai' => 10, 'durasi' => 8, 'kat' => 'siswa'],
            ['nama' => 'Rapat Koordinasi Persiapan PPDB 2026/2027', 'deskripsi' => 'Rapat koordinasi seluruh panitia PPDB untuk membahas mekanisme, jadwal, dan pembagian tugas.', 'lokasi' => 'Ruang Rapat Utama', 'mulai' => 5, 'durasi' => 3, 'kat' => 'staf'],
            ['nama' => 'Lomba Karya Ilmiah Remaja (KIR) Internal', 'deskripsi' => 'Lomba karya ilmiah remaja tingkat sekolah sebagai seleksi untuk mewakili sekolah di tingkat kabupaten.', 'lokasi' => 'Aula Serbaguna', 'mulai' => 15, 'durasi' => 6, 'kat' => 'siswa'],
            ['nama' => 'Pelatihan K3 untuk Siswa Jurusan TBSM', 'deskripsi' => 'Pelatihan Keselamatan dan Kesehatan Kerja (K3) wajib bagi siswa jurusan Teknik dan Bisnis Sepeda Motor sebelum praktik di bengkel.', 'lokasi' => 'Workshop TBSM', 'mulai' => 7, 'durasi' => 4, 'kat' => 'siswa'],
            ['nama' => 'Kunjungan Industri ke PT Semen Tonasa', 'deskripsi' => 'Kunjungan industri untuk siswa kelas XI semua jurusan ke PT Semen Tonasa di Pangkep, Sulawesi Selatan.', 'lokasi' => 'PT Semen Tonasa, Pangkep', 'mulai' => 22, 'durasi' => 24, 'kat' => 'siswa'],
            ['nama' => 'Seminar Motivasi: Membangun Mental Juara', 'deskripsi' => 'Seminar motivasi untuk seluruh siswa menghadirkan motivator nasional tentang pentingnya mental juara di dunia kerja.', 'lokasi' => 'Aula Serbaguna', 'mulai' => 18, 'durasi' => 3, 'kat' => 'umum'],
            ['nama' => 'Ujian Sertifikasi BNSP Batch Maret 2026', 'deskripsi' => 'Pelaksanaan uji sertifikasi kompetensi BNSP untuk siswa kelas XII seluruh program keahlian.', 'lokasi' => 'Ruang LSP-P1 SMKN 1 Kolaka', 'mulai' => 12, 'durasi' => 8, 'kat' => 'siswa'],
        ];

        foreach ($agendaData as $a) {
            Agenda::create([
                'user_id' => $admin->id,
                'nama_kegiatan' => $a['nama'],
                'slug' => Str::slug($a['nama']),
                'deskripsi_kegiatan' => $a['deskripsi'],
                'lokasi_pelaksanaan' => $a['lokasi'],
                'waktu_mulai' => now()->addDays($a['mulai'])->setTime(8, 0),
                'waktu_selesai' => now()->addDays($a['mulai'])->setTime(8 + $a['durasi'], 0),
                'kategori_peserta' => $a['kat'],
            ]);
        }

        // ── TEFA Kategori & Produk ────────────────────
        $tefaKats = [
            ['nama_kategori' => 'Jasa IT & Digital', 'slug' => 'jasa-it-digital'],
            ['nama_kategori' => 'Jasa Perkantoran', 'slug' => 'jasa-perkantoran'],
            ['nama_kategori' => 'Jasa Otomotif', 'slug' => 'jasa-otomotif'],
            ['nama_kategori' => 'Produk Digital', 'slug' => 'produk-digital'],
            ['nama_kategori' => 'Jasa Keuangan', 'slug' => 'jasa-keuangan'],
        ];
        $tefaKatIds = [];
        foreach ($tefaKats as $tk) {
            $cat = TefaKategori::create($tk);
            $tefaKatIds[$tk['slug']] = $cat->id;
        }

        $tefaProdukData = [
            ['program' => 'RPL', 'kategori' => 'jasa-it-digital', 'nama' => 'Jasa Pembuatan Website Company Profile', 'deskripsi' => 'Pembuatan website company profile profesional dengan desain modern, responsive, SEO-friendly. Termasuk domain .com dan hosting 1 tahun.', 'harga' => 2500000, 'status' => 'tersedia'],
            ['program' => 'RPL', 'kategori' => 'jasa-it-digital', 'nama' => 'Jasa Pembuatan Aplikasi PPOB & Kasir', 'deskripsi' => 'Pengembangan aplikasi Point of Sale (POS) dan Payment Point Online Bank (PPOB) untuk UMKM, lengkap dengan laporan penjualan otomatis.', 'harga' => 5000000, 'status' => 'tersedia'],
            ['program' => 'RPL', 'kategori' => 'produk-digital', 'nama' => 'Template Website Sekolah Premium', 'deskripsi' => 'Template website sekolah siap pakai dengan fitur lengkap: PPDB online, profil, berita, galeri, dan panel admin.', 'harga' => 750000, 'status' => 'tersedia'],
            ['program' => 'TKJ', 'kategori' => 'jasa-it-digital', 'nama' => 'Jasa Instalasi Jaringan LAN & WiFi', 'deskripsi' => 'Pemasangan jaringan LAN dan WiFi untuk kantor, sekolah, atau rumah. Termasuk konfigurasi router, switch, dan access point.', 'harga' => 1500000, 'status' => 'tersedia'],
            ['program' => 'TKJ', 'kategori' => 'jasa-it-digital', 'nama' => 'Jasa Service & Maintenance Komputer', 'deskripsi' => 'Servis komputer/laptop meliputi instal ulang OS, pembersihan virus, upgrade hardware, dan maintenance berkala.', 'harga' => 150000, 'status' => 'tersedia'],
            ['program' => 'TKJ', 'kategori' => 'jasa-it-digital', 'nama' => 'Jasa Setup CCTV & Surveillance System', 'deskripsi' => 'Pemasangan sistem CCTV profesional untuk rumah, kantor, atau toko. Bisa dipantau melalui smartphone 24/7.', 'harga' => 3500000, 'status' => 'pre-order'],
            ['program' => 'OTKP', 'kategori' => 'jasa-perkantoran', 'nama' => 'Jasa Pengetikan & Layout Dokumen', 'deskripsi' => 'Jasa pengetikan dokumen resmi, laporan, skripsi, dan layout majalah/buletin dengan desain profesional.', 'harga' => 50000, 'status' => 'tersedia'],
            ['program' => 'OTKP', 'kategori' => 'jasa-perkantoran', 'nama' => 'Jasa Manajemen Event & Kegiatan', 'deskripsi' => 'Perencanaan dan pengelolaan event seperti seminar, workshop, dan upacara. Termasuk MC, dokumentasi, dan laporan kegiatan.', 'harga' => 2000000, 'status' => 'tersedia'],
            ['program' => 'AKL', 'kategori' => 'jasa-keuangan', 'nama' => 'Jasa Pembukuan UMKM', 'deskripsi' => 'Penyusunan pembukuan dan laporan keuangan sederhana untuk UMKM. Termasuk pencatatan transaksi, laba rugi, dan neraca.', 'harga' => 300000, 'status' => 'tersedia'],
            ['program' => 'AKL', 'kategori' => 'jasa-keuangan', 'nama' => 'Jasa Pelaporan Pajak UMKM', 'deskripsi' => 'Asistensi pelaporan pajak bagi UMKM termasuk perhitungan PPh Final 0.5%, pengisian SPT, dan pelaporan online.', 'harga' => 200000, 'status' => 'tersedia'],
            ['program' => 'TBSM', 'kategori' => 'jasa-otomotif', 'nama' => 'Service Berkala Sepeda Motor', 'deskripsi' => 'Service rutin sepeda motor meliputi ganti oli, tune up, cek kelistrikan, dan pengecekan rem. Untuk semua merek.', 'harga' => 75000, 'status' => 'tersedia'],
            ['program' => 'TBSM', 'kategori' => 'jasa-otomotif', 'nama' => 'Overhaul Mesin Sepeda Motor', 'deskripsi' => 'Perbaikan total mesin sepeda motor termasuk penggantian spare part, boring cylinder, dan balancing. Garansi 3 bulan.', 'harga' => 500000, 'status' => 'tersedia'],
        ];

        foreach ($tefaProdukData as $tp) {
            TefaProduk::create([
                'program_keahlian_id' => $programIds[$tp['program']],
                'kategori_id' => $tefaKatIds[$tp['kategori']],
                'nama_produk_jasa' => $tp['nama'],
                'slug' => Str::slug($tp['nama']),
                'deskripsi' => $tp['deskripsi'],
                'harga_estimasi' => $tp['harga'],
                'status_ketersediaan' => $tp['status'],
            ]);
        }

        // ── Galeri Album & Item ───────────────────────
        $albumData = [
            [
                'judul' => 'Upacara Hardiknas 2026',
                'deskripsi' => 'Dokumentasi kegiatan upacara peringatan Hari Pendidikan Nasional 2 Mei 2026.',
                'tanggal' => '2026-01-15',
                'items' => [
                    'Upacara bendera dipimpin Kepala Sekolah',
                    'Peserta upacara berpakaian adat',
                    'Pembacaan teks Sumpah Pemuda',
                    'Penampilan paduan suara',
                    'Foto bersama seluruh panitia',
                ],
            ],
            [
                'judul' => 'LKS Tingkat Provinsi Sultra 2026',
                'deskripsi' => 'Momen-momen keikutsertaan dan kemenangan siswa SMKN 1 Kolaka dalam LKS Provinsi.',
                'tanggal' => '2026-02-10',
                'items' => [
                    'Tim RPL saat lomba Web Technologies',
                    'Suasana lomba IT Network Systems',
                    'Penyerahan medali emas',
                    'Selebrasi juara umum',
                    'Foto bersama dengan trofi',
                    'Pelepasan kontingen oleh Kepsek',
                ],
            ],
            [
                'judul' => 'Workshop IoT Bersama ITB',
                'deskripsi' => 'Kegiatan workshop Internet of Things (IoT) yang menghadirkan dosen ITB.',
                'tanggal' => '2026-03-01',
                'items' => [
                    'Pembukaan workshop oleh Wakasek',
                    'Praktek merakit sensor IoT',
                    'Sesi diskusi kelompok',
                    'Demo proyek siswa',
                ],
            ],
            [
                'judul' => 'Kunjungan Industri ke Makassar',
                'deskripsi' => 'Kunjungan industri siswa kelas XI ke berbagai perusahaan di Makassar.',
                'tanggal' => '2026-02-20',
                'items' => [
                    'Kunjungan ke kantor Telkom Makassar',
                    'Tour PT Kalla Group',
                    'Foto bersama di Pantai Losari',
                    'Sesi tanya jawab dengan HRD perusahaan',
                    'Makan bersama tim',
                ],
            ],
            [
                'judul' => 'Penandatanganan MoU dengan Telkom',
                'deskripsi' => 'Momen bersejarah penandatanganan kerjasama antara SMKN 1 Kolaka dan PT Telkom Indonesia.',
                'tanggal' => '2026-03-10',
                'items' => [
                    'Prosesi penandatanganan MoU',
                    'Sambutan Kepala Sekolah',
                    'Sambutan GM Telkom Regional VII',
                    'Tukar cinderamata',
                    'Foto bersama jajaran pejabat',
                    'Tur fasilitas sekolah',
                ],
            ],
            [
                'judul' => 'Turnamen Futsal Antar SMK',
                'deskripsi' => 'Aksi tim futsal SMKN 1 Kolaka di turnamen antar SMK se-Sulawesi Tenggara.',
                'tanggal' => '2026-03-05',
                'items' => [
                    'Pertandingan babak penyisihan',
                    'Gol spektakuler di semifinal',
                    'Selebrasi di partai final',
                    'Angkat trofi juara 1',
                    'Sesi foto bersama tim',
                ],
            ],
        ];

        foreach ($albumData as $alb) {
            $album = GaleriAlbum::create([
                'user_id' => $admin->id,
                'judul_album' => $alb['judul'],
                'slug' => Str::slug($alb['judul']),
                'deskripsi_singkat' => $alb['deskripsi'],
                'tanggal_kegiatan' => $alb['tanggal'],
            ]);

            foreach ($alb['items'] as $idx => $caption) {
                GaleriItem::create([
                    'album_id' => $album->id,
                    'tipe_file' => 'foto',
                    'file_path' => 'galeri/placeholder-' . $album->id . '-' . ($idx + 1) . '.jpg',
                    'caption' => $caption,
                ]);
            }
        }
    }
}
