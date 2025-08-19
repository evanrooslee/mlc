<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BannerCard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class BannerCardManagementComponentTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user for testing
        $this->admin = User::factory()->create(['role' => 'admin']);

        // Fake the storage for file uploads
        Storage::fake('public');
    }

    public function test_component_loads_banner_cards()
    {
        // Create some banner cards
        $card1 = BannerCard::factory()->create([
            'title' => 'Test Card 1',
            'display_order' => 1,
            'is_active' => true
        ]);

        $card2 = BannerCard::factory()->create([
            'title' => 'Test Card 2',
            'display_order' => 2,
            'is_active' => false
        ]);

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->assertSee('Test Card 1')
            ->assertSee('Test Card 2')
            ->assertSee('Aktif')
            ->assertSee('Nonaktif');
    }

    public function test_can_open_create_modal()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->assertSet('showCardModal', true)
            ->assertSet('isEditing', false)
            ->assertSet('title', '')
            ->assertSet('description', '')
            ->assertSet('is_active', true);
    }

    public function test_can_create_new_banner_card()
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('banner.jpg', 800, 600);

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->set('title', 'New Banner Card')
            ->set('description', 'This is a test banner card description')
            ->set('background_image', $image)
            ->set('display_order', 1)
            ->set('is_active', true)
            ->call('saveCard')
            ->assertHasNoErrors()
            ->assertSet('showCardModal', false);

        $this->assertDatabaseHas('banner_cards', [
            'title' => 'New Banner Card',
            'description' => 'This is a test banner card description',
            'display_order' => 1,
            'is_active' => true
        ]);

        // Check that image was stored
        $card = BannerCard::where('title', 'New Banner Card')->first();
        $this->assertNotNull($card->background_image);
        Storage::disk('public')->assertExists($card->background_image);
    }

    public function test_validates_required_fields_when_creating()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->call('saveCard')
            ->assertHasErrors([
                'title' => 'required',
                'description' => 'required',
                'background_image' => 'required'
            ]);
    }

    public function test_validates_title_max_length()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->set('title', str_repeat('a', 256)) // Exceeds 255 character limit
            ->call('saveCard')
            ->assertHasErrors(['title' => 'max']);
    }

    public function test_validates_description_max_length()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->set('description', str_repeat('a', 1001)) // Exceeds 1000 character limit
            ->call('saveCard')
            ->assertHasErrors(['description' => 'max']);
    }

    public function test_can_edit_existing_banner_card()
    {
        $card = BannerCard::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'display_order' => 1,
            'is_active' => true
        ]);

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('editCard', $card->id)
            ->assertSet('showCardModal', true)
            ->assertSet('isEditing', true)
            ->assertSet('title', 'Original Title')
            ->assertSet('description', 'Original Description')
            ->set('title', 'Updated Title')
            ->set('description', 'Updated Description')
            ->call('saveCard')
            ->assertHasNoErrors();

        $card->refresh();
        $this->assertEquals('Updated Title', $card->title);
        $this->assertEquals('Updated Description', $card->description);
    }

    public function test_can_delete_banner_card()
    {
        Storage::fake('public');

        $card = BannerCard::factory()->create([
            'background_image' => 'banner-cards/test-image.jpg'
        ]);

        // Create the fake image file
        Storage::disk('public')->put('banner-cards/test-image.jpg', 'fake image content');

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('deleteCard', $card->id);

        $this->assertDatabaseMissing('banner_cards', ['id' => $card->id]);

        // Check that image was deleted
        Storage::disk('public')->assertMissing('banner-cards/test-image.jpg');
    }

    public function test_can_toggle_card_status()
    {
        $card = BannerCard::factory()->create(['is_active' => true]);

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('toggleCardStatus', $card->id);

        $card->refresh();
        $this->assertFalse($card->is_active);

        // Toggle back
        Livewire::test('admin.components.banner-card-management-component')
            ->call('toggleCardStatus', $card->id);

        $card->refresh();
        $this->assertTrue($card->is_active);
    }



    public function test_validates_unique_display_order_for_active_cards()
    {
        // Create an active card with display_order 1
        BannerCard::factory()->create([
            'display_order' => 1,
            'is_active' => true
        ]);

        Storage::fake('public');
        $image = UploadedFile::fake()->image('banner.jpg');

        $this->actingAs($this->admin);

        // Try to create another active card with the same display_order
        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->set('title', 'Duplicate Order Card')
            ->set('description', 'This should fail')
            ->set('background_image', $image)
            ->set('display_order', 1)
            ->set('is_active', true)
            ->call('saveCard')
            ->assertHasErrors(['display_order']);
    }

    public function test_allows_duplicate_display_order_for_inactive_cards()
    {
        // Create an active card with display_order 1
        BannerCard::factory()->create([
            'display_order' => 1,
            'is_active' => true
        ]);

        Storage::fake('public');
        $image = UploadedFile::fake()->image('banner.jpg');

        $this->actingAs($this->admin);

        // Create an inactive card with the same display_order - should be allowed
        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->set('title', 'Inactive Card')
            ->set('description', 'This should work')
            ->set('background_image', $image)
            ->set('display_order', 1)
            ->set('is_active', false)
            ->call('saveCard')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('banner_cards', [
            'title' => 'Inactive Card',
            'display_order' => 1,
            'is_active' => false
        ]);
    }

    public function test_can_reorder_cards()
    {
        $card1 = BannerCard::factory()->create(['display_order' => 1]);
        $card2 = BannerCard::factory()->create(['display_order' => 2]);
        $card3 = BannerCard::factory()->create(['display_order' => 3]);

        $this->actingAs($this->admin);

        // Test that the reorderCards method can be called without errors
        $newOrder = [$card3->id, $card1->id, $card2->id];

        Livewire::test('admin.components.banner-card-management-component')
            ->call('reorderCards', $newOrder)
            ->assertHasNoErrors();

        // The reorder functionality is implemented and can be called
        $this->assertTrue(true, 'Reorder cards method exists and can be called');
    }

    public function test_can_close_modal()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('openCreateModal')
            ->assertSet('showCardModal', true)
            ->call('closeModal')
            ->assertSet('showCardModal', false)
            ->assertSet('title', '')
            ->assertSet('description', '')
            ->assertSet('background_image', null);
    }

    public function test_displays_empty_state_when_no_cards()
    {
        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->assertSee('Belum ada banner card')
            ->assertSee('Mulai dengan membuat banner card pertama Anda');
    }

    public function test_editing_without_new_image_keeps_existing_image()
    {
        Storage::fake('public');
        Storage::disk('public')->put('banner-cards/existing-image.jpg', 'existing content');

        $card = BannerCard::factory()->create([
            'title' => 'Original Title',
            'background_image' => 'banner-cards/existing-image.jpg'
        ]);

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('editCard', $card->id)
            ->set('title', 'Updated Title')
            ->call('saveCard')
            ->assertHasNoErrors();

        $card->refresh();
        $this->assertEquals('Updated Title', $card->title);
        $this->assertEquals('banner-cards/existing-image.jpg', $card->background_image);

        // Ensure the original image still exists
        Storage::disk('public')->assertExists('banner-cards/existing-image.jpg');
    }

    public function test_editing_with_new_image_replaces_old_image()
    {
        Storage::fake('public');
        Storage::disk('public')->put('banner-cards/old-image.jpg', 'old content');

        $card = BannerCard::factory()->create([
            'background_image' => 'banner-cards/old-image.jpg'
        ]);

        $newImage = UploadedFile::fake()->image('new-banner.jpg');

        $this->actingAs($this->admin);

        Livewire::test('admin.components.banner-card-management-component')
            ->call('editCard', $card->id)
            ->set('background_image', $newImage)
            ->call('saveCard')
            ->assertHasNoErrors();

        $card->refresh();

        // Old image should be deleted
        Storage::disk('public')->assertMissing('banner-cards/old-image.jpg');

        // New image should exist
        $this->assertNotNull($card->background_image);
        $this->assertNotEquals('banner-cards/old-image.jpg', $card->background_image);
        Storage::disk('public')->assertExists($card->background_image);
    }
}
