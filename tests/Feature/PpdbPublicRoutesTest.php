<?php

namespace Tests\Feature;

use App\Models\PpdbPeriod;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbPublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_ppdb_routes_are_accessible(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('ppdb.index'))->assertOk();
        $this->get(route('ppdb.form'))->assertOk();
        $this->get(route('ppdb.status'))->assertOk();
        $this->get(route('ppdb.daftar-ulang'))->assertOk();
    }

    public function test_public_ppdb_pages_can_switch_to_another_visible_period(): void
    {
        $this->seed(DatabaseSeeder::class);

        $futurePeriod = PpdbPeriod::where('tahun_ajaran', '2027/2028')->firstOrFail();

        $this->get(route('ppdb.index', ['periode' => $futurePeriod->id]))
            ->assertOk()
            ->assertSee('2027/2028')
            ->assertSee('Gelombang 1');

        $this->get(route('ppdb.form', ['periode' => $futurePeriod->id]))
            ->assertOk()
            ->assertSee('2027/2028')
            ->assertSee('Pendaftaran Belum Dibuka');
    }
}