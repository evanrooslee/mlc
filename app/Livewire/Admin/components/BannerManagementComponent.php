<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageService;

class BannerManagementComponent extends Component
{
    use WithFileUploads;

    public $banner;
    public $showUploadModal = false;
    public $currentBannerUrl = null;
    public $isUploading = false;
    public $uploadSuccess = false;

    public function mount()
    {
        // Check if banner exists in storage and set the URL
        if (Storage::disk('public')->exists('banners/main-banner.jpg')) {
            $this->currentBannerUrl = Storage::url('banners/main-banner.jpg');
        } elseif (Storage::disk('public')->exists('banners/main-banner.png')) {
            $this->currentBannerUrl = Storage::url('banners/main-banner.png');
        }
    }

    public function openUploadModal()
    {
        $this->showUploadModal = true;
        $this->dispatch('open-modal', id: 'banner-upload-modal');
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->reset(['banner']);
        $this->dispatch('close-modal', id: 'banner-upload-modal');
    }

    public function uploadBanner()
    {
        $this->validate([
            'banner' => 'required|image|max:2048', // 2MB Max
        ]);

        $this->isUploading = true;

        try {
            // Delete existing banner if it exists
            if (Storage::disk('public')->exists('banners/main-banner.jpg')) {
                Storage::disk('public')->delete('banners/main-banner.jpg');
            }
            if (Storage::disk('public')->exists('banners/main-banner.png')) {
                Storage::disk('public')->delete('banners/main-banner.png');
            }

            // Use ImageService to resize and save the banner (with fallback)
            try {
                $imageService = new ImageService();
                $path = $imageService->resizeForType($this->banner, 'banner', 'banners');
            } catch (\Exception $e) {
                // Fallback: Save without resizing if GD is not available
                $extension = $this->banner->getClientOriginalExtension();
                $path = $this->banner->storeAs('banners', "main-banner.{$extension}", 'public');

                // Log the error for debugging
                Log::warning('Image resizing failed, saved without resizing: ' . $e->getMessage());
            }

            // Update the banner URL
            $this->currentBannerUrl = Storage::url($path);

            $this->uploadSuccess = true;
            $this->closeUploadModal();

            // Show success message
            session()->flash('message', 'Banner berhasil diunggah dan diresize!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengunggah banner: ' . $e->getMessage());
        }

        $this->isUploading = false;
    }

    public function saveBannerChanges()
    {
        try {
            // Get or create the banner record
            $banner = \App\Models\Banner::first();
            if (!$banner) {
                $banner = new \App\Models\Banner();
            }

            // Update the banner image URL
            if ($this->currentBannerUrl) {
                $banner->image = $this->currentBannerUrl;
                $banner->save();
                session()->flash('message', 'Perubahan banner berhasil disimpan!');
            } else {
                session()->flash('error', 'Tidak ada banner untuk disimpan. Silakan unggah banner terlebih dahulu.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan perubahan banner: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.components.banner-management-component');
    }
}
