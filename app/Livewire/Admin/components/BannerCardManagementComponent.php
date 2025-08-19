<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageService;
use App\Models\BannerCard;
use Illuminate\Validation\ValidationException;


class BannerCardManagementComponent extends Component
{
    use WithFileUploads;

    public $bannerCards = [];
    public $editingCard = null;
    public $showCardModal = false;
    public $isEditing = false;

    // Form fields
    public $title = '';
    public $description = '';
    public $background_image = null;
    public $display_order = 1;
    public $is_active = true;

    // UI state
    public $isUploading = false;
    public $uploadSuccess = false;

    protected $listeners = [
        'refreshCards' => 'loadBannerCards',
        'cardDeleted' => 'loadBannerCards'
    ];

    public function mount()
    {
        $this->loadBannerCards();
    }

    public function loadBannerCards()
    {
        $this->bannerCards = BannerCard::ordered()->get()->toArray();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showCardModal = true;
        $this->display_order = $this->getNextDisplayOrder();
        $this->dispatch('open-modal', id: 'banner-card-modal');
    }

    public function editCard($cardId)
    {
        $card = BannerCard::findOrFail($cardId);
        $this->editingCard = $card;
        $this->isEditing = true;

        $this->title = $card->title;
        $this->description = $card->description;
        $this->display_order = $card->display_order;
        $this->is_active = $card->is_active;

        $this->showCardModal = true;
        $this->dispatch('open-modal', id: 'banner-card-modal');
    }

    public function deleteCard($cardId)
    {
        try {
            $card = BannerCard::findOrFail($cardId);

            // Delete the background image file if it exists
            if ($card->background_image && Storage::disk('public')->exists($card->background_image)) {
                Storage::disk('public')->delete($card->background_image);
            }

            $card->delete();

            $this->loadBannerCards();
            session()->flash('message', 'Banner card berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus banner card: ' . $e->getMessage());
        }
    }

    public function toggleCardStatus($cardId)
    {
        try {
            $card = BannerCard::findOrFail($cardId);
            $card->is_active = !$card->is_active;
            $card->save();

            $this->loadBannerCards();
            $status = $card->is_active ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('message', "Banner card berhasil {$status}!");
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah status banner card: ' . $e->getMessage());
        }
    }

    public function reorderCards($newOrder)
    {
        try {
            foreach ($newOrder as $index => $cardId) {
                BannerCard::where('id', $cardId)->update(['display_order' => $index + 1]);
            }

            $this->loadBannerCards();
            session()->flash('message', 'Urutan banner card berhasil diperbarui!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengubah urutan banner card: ' . $e->getMessage());
        }
    }

    public function saveCard()
    {
        $this->isUploading = true;

        try {
            // Validate form data
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'display_order' => 'required|integer|between:1,6',
                'is_active' => 'boolean'
            ];

            // Add image validation for new cards or when image is uploaded
            if (!$this->isEditing || $this->background_image) {
                $rules['background_image'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            }

            $this->validate($rules);

            // Add unique display_order validation for active cards
            if ($this->is_active) {
                $query = BannerCard::where('display_order', $this->display_order)
                    ->where('is_active', true);

                if ($this->isEditing) {
                    $query->where('id', '!=', $this->editingCard->id);
                }

                if ($query->exists()) {
                    $this->addError('display_order', 'Display order sudah digunakan oleh banner card aktif lainnya.');
                    $this->isUploading = false;
                    return;
                }
            }

            // Check maximum active cards limit
            if ($this->is_active) {
                $activeCount = BannerCard::where('is_active', true);
                if ($this->isEditing) {
                    $activeCount->where('id', '!=', $this->editingCard->id);
                }

                if ($activeCount->count() >= 6) {
                    $this->addError('is_active', 'Maksimal 6 banner card yang dapat aktif bersamaan.');
                    $this->isUploading = false;
                    return;
                }
            }

            $imagePath = null;

            // Handle image upload
            if ($this->background_image) {
                try {
                    $imageService = new ImageService();
                    $imagePath = $imageService->resizeForType(
                        $this->background_image,
                        'banner',
                        'banner-cards'
                    );
                } catch (\Exception $e) {
                    // Fallback: Save without resizing if image service fails
                    $extension = $this->background_image->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $imagePath = $this->background_image->storeAs('banner-cards', $filename, 'public');

                    Log::warning('Image resizing failed, saved without resizing: ' . $e->getMessage());
                }
            }

            // Create or update banner card
            if ($this->isEditing) {
                $card = $this->editingCard;

                // Delete old image if new one is uploaded
                if ($imagePath && $card->background_image && Storage::disk('public')->exists($card->background_image)) {
                    Storage::disk('public')->delete($card->background_image);
                }

                $card->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'background_image' => $imagePath ?: $card->background_image,
                    'display_order' => $this->display_order,
                    'is_active' => $this->is_active
                ]);

                session()->flash('message', 'Banner card berhasil diperbarui!');
            } else {
                BannerCard::create([
                    'title' => $this->title,
                    'description' => $this->description,
                    'background_image' => $imagePath,
                    'display_order' => $this->display_order,
                    'is_active' => $this->is_active
                ]);

                session()->flash('message', 'Banner card berhasil dibuat!');
            }

            $this->closeModal();
            $this->loadBannerCards();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan banner card: ' . $e->getMessage());
        }

        $this->isUploading = false;
    }

    public function closeModal()
    {
        $this->showCardModal = false;
        $this->resetForm();
        $this->dispatch('close-modal', id: 'banner-card-modal');
    }

    private function resetForm()
    {
        $this->reset([
            'title',
            'description',
            'background_image',
            'display_order',
            'is_active',
            'editingCard',
            'isEditing'
        ]);
        $this->is_active = true;
    }

    private function getNextDisplayOrder()
    {
        $maxOrder = BannerCard::max('display_order') ?? 0;
        return min($maxOrder + 1, 6);
    }

    public function render()
    {
        return view('livewire.admin.components.banner-card-management-component');
    }
}
