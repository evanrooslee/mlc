<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDataSiswaLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_livewire_component_displays_packet_codes()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create packets with codes
        $packet1 = Packet::factory()->create([
            'title' => 'Matematika Kelas 12',
            'code' => 'MTK12'
        ]);

        $packet2 = Packet::factory()->create([
            'title' => 'Fisika Kelas 12',
            'code' => 'FSK12'
        ]);

        // Create a student with multiple packets
        $student = User::factory()->create(['role' => 'student']);
        $student->packets()->attach([$packet1->id, $packet2->id]);

        // Test the Livewire component
        $this->actingAs($admin);

        Livewire::test('admin.tabel-data-siswa')
            ->assertSee($student->name)
            ->assertSee('MTK12, FSK12')
            ->assertDontSee('Matematika Kelas 12')
            ->assertDontSee('Fisika Kelas 12');
    }

    public function test_livewire_component_can_search_by_packet_codes()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create packets with codes
        $packet1 = Packet::factory()->create([
            'title' => 'Matematika Kelas 12',
            'code' => 'MTK12'
        ]);

        $packet2 = Packet::factory()->create([
            'title' => 'Fisika Kelas 12',
            'code' => 'FSK12'
        ]);

        // Create students with different packets
        $student1 = User::factory()->create(['role' => 'student', 'name' => 'John Doe']);
        $student1->packets()->attach($packet1->id);

        $student2 = User::factory()->create(['role' => 'student', 'name' => 'Jane Smith']);
        $student2->packets()->attach($packet2->id);

        // Test searching by packet code
        $this->actingAs($admin);

        Livewire::test('admin.tabel-data-siswa')
            ->set('search', 'MTK12')
            ->assertSee('John Doe')
            ->assertSee('MTK12')
            ->assertDontSee('Jane Smith')
            ->assertDontSee('FSK12');
    }

    public function test_livewire_component_shows_dash_for_no_packets()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create a student without packets
        $student = User::factory()->create(['role' => 'student']);

        // Test the Livewire component
        $this->actingAs($admin);

        Livewire::test('admin.tabel-data-siswa')
            ->assertSee($student->name)
            ->assertSee('-');
    }
}
