<?php

namespace Tests\Feature;

use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPackageModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_package_modal_clears_fields_for_olimpiade(): void
    {
        Livewire::test('admin.components.add-package-modal')
            ->set('kurikulum', 'NAS')
            ->set('grade', 9)
            ->set('tingkatan', 'Olimpiade')
            ->assertSet('kurikulum', null)
            ->assertSet('grade', null);
    }

    public function test_add_package_modal_requires_olimpiade_kurikulum_smp_or_sma(): void
    {
        Livewire::test('admin.components.add-package-modal')
            ->set('tingkatan', 'Olimpiade')
            ->set('kurikulum', 'NAS')
            ->call('save')
            ->assertHasErrors(['kurikulum']);

        Livewire::test('admin.components.add-package-modal')
            ->set('tingkatan', 'Olimpiade')
            ->set('kurikulum', 'SMP')
            ->assertHasNoErrors(['kurikulum']);
    }

    public function test_add_package_modal_clears_grade_when_outside_range(): void
    {
        Livewire::test('admin.components.add-package-modal')
            ->set('grade', 12)
            ->set('tingkatan', 'SMP')
            ->assertSet('grade', null)
            ->set('grade', 7)
            ->set('tingkatan', 'SMA')
            ->assertSet('grade', null);
    }

    public function test_edit_package_modal_clears_fields_for_olimpiade(): void
    {
        $packet = Packet::factory()->create([
            'tingkatan' => 'SMP',
            'kurikulum' => 'NAS',
            'grade' => 8,
        ]);

        Livewire::test('admin.components.edit-package-modal', [
            'packageId' => $packet->id,
        ])
            ->set('tingkatan', 'Olimpiade')
            ->assertSet('kurikulum', null)
            ->assertSet('grade', null);
    }
}
