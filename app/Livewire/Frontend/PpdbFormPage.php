<?php

namespace App\Livewire\Frontend;

use App\Models\PpdbApplication;
use App\Models\PpdbDocument;
use App\Models\ProgramKeahlian;
use App\Support\PpdbPeriodResolver;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Form Pendaftaran PPDB - SMK Negeri 1 Kolaka')]
class PpdbFormPage extends Component
{
    use WithFileUploads;

    #[Url(as: 'periode')]
    public string $selectedPeriod = '';

    public string $nama_lengkap = '';
    public string $nisn = '';
    public string $nik = '';
    public string $jenis_kelamin = 'L';
    public string $tempat_lahir = '';
    public string $tanggal_lahir = '';
    public string $agama = 'Islam';
    public string $alamat_lengkap = '';
    public string $nomor_hp = '';
    public string $email = '';
    public string $asal_sekolah = '';
    public string $nama_ayah = '';
    public string $pekerjaan_ayah = '';
    public string $nama_ibu = '';
    public string $pekerjaan_ibu = '';
    public string $nomor_hp_orang_tua = '';
    public string $track_id = '';
    public string $pilihan_program_1_id = '';
    public string $pilihan_program_2_id = '';
    public string $nilai_rata_rata = '';
    public string $catatan_pendaftar = '';

    public $file_kk;
    public $file_akta;
    public $file_rapor;
    public $file_pas_foto;
    public $file_skl;

    public ?string $submittedNumber = null;

    public function mount(): void
    {
        $period = $this->resolveSelectedPeriod();

        if ($period && $period->tracks->isNotEmpty()) {
            $this->track_id = (string) $period->tracks->first()->id;
        }

        if ($period && $this->selectedPeriod === '') {
            $this->selectedPeriod = (string) $period->id;
        }
    }

    public function updatedSelectedPeriod(): void
    {
        $period = $this->resolveSelectedPeriod();
        $this->track_id = $period && $period->tracks->isNotEmpty() ? (string) $period->tracks->first()->id : '';
    }

    public function submitApplication(): void
    {
        $period = $this->resolveSelectedPeriod();

        if (! $period) {
            $this->addError('period', 'Periode PPDB belum dibuka.');
            return;
        }

        if (! $period->isRegistrationOpen()) {
            $this->addError('period', 'Gelombang atau periode yang dipilih saat ini belum dibuka untuk pendaftaran.');
            return;
        }

        $validated = $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'nullable|string|max:50',
            'alamat_lengkap' => 'required|string',
            'nomor_hp' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'nomor_hp_orang_tua' => 'nullable|string|max:30',
            'track_id' => ['required', Rule::exists('ppdb_tracks', 'id')->where('period_id', $period->id)],
            'pilihan_program_1_id' => 'required|exists:program_keahlian,id',
            'pilihan_program_2_id' => 'nullable|different:pilihan_program_1_id|exists:program_keahlian,id',
            'nilai_rata_rata' => 'nullable|numeric|min:0|max:100',
            'catatan_pendaftar' => 'nullable|string',
            'file_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'file_akta' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'file_rapor' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'file_pas_foto' => 'required|image|max:4096',
            'file_skl' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $nomorPendaftaran = $this->generateRegistrationNumber($period);

        $application = PpdbApplication::create([
            'period_id' => $period->id,
            'track_id' => $validated['track_id'],
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nisn' => $validated['nisn'],
            'nik' => $validated['nik'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'agama' => $validated['agama'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'nomor_hp' => $validated['nomor_hp'],
            'email' => $validated['email'],
            'asal_sekolah' => $validated['asal_sekolah'],
            'nama_ayah' => $validated['nama_ayah'],
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'],
            'nama_ibu' => $validated['nama_ibu'],
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'],
            'nomor_hp_orang_tua' => $validated['nomor_hp_orang_tua'],
            'pilihan_program_1_id' => $validated['pilihan_program_1_id'],
            'pilihan_program_2_id' => $validated['pilihan_program_2_id'] ?: null,
            'nilai_rata_rata' => $validated['nilai_rata_rata'] ?: null,
            'catatan_pendaftar' => $validated['catatan_pendaftar'],
            'status_pendaftaran' => 'submitted',
            'status_berkas' => 'pending',
            'submitted_at' => now(),
        ]);

        $documents = [
            'Kartu Keluarga' => $this->file_kk,
            'Akta Kelahiran' => $this->file_akta,
            'Rapor / Nilai' => $this->file_rapor,
            'Pas Foto' => $this->file_pas_foto,
            'Surat Keterangan Lulus' => $this->file_skl,
        ];

        foreach ($documents as $jenis => $file) {
            if (! $file) {
                continue;
            }

            PpdbDocument::create([
                'application_id' => $application->id,
                'jenis_dokumen' => $jenis,
                'file_path' => $file->store('ppdb/documents', 'public'),
                'status_verifikasi' => 'pending',
            ]);
        }

        $this->submittedNumber = $nomorPendaftaran;
        $this->resetForm();
    }

    protected function generateRegistrationNumber($period): string
    {
        $prefix = sprintf('PPDB-%s-G%s-', $period->tahun_mulai ?? now()->format('Y'), $period->gelombang_ke ?? 1);
        $sequence = PpdbApplication::where('period_id', $period->id)->count() + 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    protected function resetForm(): void
    {
        $trackId = $this->track_id;
        $this->reset([
            'nama_lengkap', 'nisn', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama',
            'alamat_lengkap', 'nomor_hp', 'email', 'asal_sekolah', 'nama_ayah', 'pekerjaan_ayah',
            'nama_ibu', 'pekerjaan_ibu', 'nomor_hp_orang_tua', 'pilihan_program_1_id', 'pilihan_program_2_id',
            'nilai_rata_rata', 'catatan_pendaftar', 'file_kk', 'file_akta', 'file_rapor', 'file_pas_foto', 'file_skl',
        ]);
        $this->jenis_kelamin = 'L';
        $this->agama = 'Islam';
        $this->track_id = $trackId;
        $this->resetValidation();
    }

    public function render()
    {
        $period = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->publicOptions();
        $selectedPeriodId = $period?->id;

        $programs = ProgramKeahlian::tampil()->orderBy('nama_jurusan')->get();
        $applicationsCount = $period ? PpdbApplication::where('period_id', $period->id)->count() : 0;

        return view('livewire.frontend.ppdb-form-page', compact('period', 'programs', 'applicationsCount', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod()
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolvePublic(
            $resolver->resolveInput($this->selectedPeriod),
            [
                'tracks' => fn ($query) => $query->visible(),
                'quotas.programKeahlian',
            ]
        );
    }
}