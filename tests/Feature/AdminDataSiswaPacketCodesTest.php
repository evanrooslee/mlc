<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDataSiswaPacketCodesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_data_siswa_shows_packet_codes_instead_of_titles()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create some packets with codes
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

        // Login as admin and access data-siswa endpoint
        $this->actingAs($admin);
        $response = $this->get(route('admin.data-siswa.data'));

        $response->assertStatus(200);

        // Decode the JSON response
        $data = $response->json();

        // Find our student in the data
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        // Assert that packet codes are shown, not titles
        $this->assertNotNull($studentData);
        $this->assertStringContainsString('MTK12', $studentData['packets']);
        $this->assertStringContainsString('FSK12', $studentData['packets']);
        $this->assertStringContainsString('MTK12, FSK12', $studentData['packets']);

        // Assert that titles are NOT shown
        $this->assertStringNotContainsString('Matematika Kelas 12', $studentData['packets']);
        $this->assertStringNotContainsString('Fisika Kelas 12', $studentData['packets']);
    }

    public function test_admin_data_siswa_shows_dash_for_students_without_packets()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create a student without any packets
        $student = User::factory()->create(['role' => 'student']);

        // Login as admin and access data-siswa endpoint
        $this->actingAs($admin);
        $response = $this->get(route('admin.data-siswa.data'));

        $response->assertStatus(200);

        // Decode the JSON response
        $data = $response->json();

        // Debug: Let's see what data we're getting
        $this->assertArrayHasKey('data', $data);
        $this->assertNotEmpty($data['data']);

        // Find our student in the data
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        // Assert that dash is shown for students without packets
        $this->assertNotNull($studentData, 'Student not found in response data');
        $this->assertEquals('-', $studentData['packets']);
    }

    public function test_admin_data_siswa_shows_single_packet_code()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create a packet
        $packet = Packet::factory()->create([
            'title' => 'Premium Matematika',
            'code' => 'PRM1'
        ]);

        // Create a student with one packet
        $student = User::factory()->create(['role' => 'student']);
        $student->packets()->attach($packet->id);

        // Login as admin and access data-siswa endpoint
        $this->actingAs($admin);
        $response = $this->get(route('admin.data-siswa.data'));

        $response->assertStatus(200);

        // Decode the JSON response
        $data = $response->json();

        // Find our student in the data
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        // Assert that single packet code is shown correctly
        $this->assertNotNull($studentData);
        $this->assertEquals('PRM1', $studentData['packets']);
        $this->assertStringNotContainsString('Premium Matematika', $studentData['packets']);
    }
}
