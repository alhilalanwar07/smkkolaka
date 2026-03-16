<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_ppdb_admin_can_access_ppdb_panel_only(): void
    {
        $this->seed(DatabaseSeeder::class);

        $ppdbAdmin = User::where('email', 'ppdb@smkn1kolaka.sch.id')->firstOrFail();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.ppdb'))
            ->assertOk();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.ppdb.applicants'))
            ->assertOk();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.ppdb.tests'))
            ->assertOk();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.ppdb.re-registration'))
            ->assertOk();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.ppdb.settings'))
            ->assertOk();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.ppdb.analytics'))
            ->assertForbidden();

        $this->actingAs($ppdbAdmin)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_super_admin_can_still_access_ppdb_panel(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'admin@smkn1kolaka.sch.id')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.ppdb'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.applicants'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.tests'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.re-registration'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.settings'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.ppdb.analytics'))
            ->assertOk();
    }
}