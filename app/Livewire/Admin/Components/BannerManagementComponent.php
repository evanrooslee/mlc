<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageService;
use App\Models\Banner;

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
        // Get banner from database and set the URL
        $banner = Banner::first();
        if ($banner && $banner->image) {
            $this->currentBannerUrl = Storage::url('banners/' . $banner->image);
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
            // Get existing banner from database
            $bannerModel = Banner::first();

            // Delete existing banner file if it exists
            if ($bannerModel && $bannerModel->image) {
                // Handle both old format (full path) and new format (filename only)
                $existingPath = str_starts_with($bannerModel->image, '/storage/')
                    ? str_replace('/storage/', '', $bannerModel->image)
                    : 'banners/' . $bannerModel->image;

                if (Storage::disk('public')->exists($existingPath)) {
                    Storage::disk('public')->delete($existingPath);
                }
            }

            // Generate unique filename
            $extension = $this->banner->getClientOriginalExtension();
            $filename = 'banner_' . time() . '.' . $extension;

            // Use ImageService to resize and save the banner (with fallback)
            try {
                $imageService = new ImageService();
                $path = $imageService->resizeForType($this->banner, 'banner', 'banners', $filename);
            } catch (\Exception $e) {
                // Fallback: Save without resizing if GD is not available
                $path = $this->banner->storeAs('banners', $filename, 'public');
                Log::warning('Image resizing failed, saved without resizing: ' . $e->getMessage());
            }

            // Extract just the filename from the path
            $storedFilename = basename($path);

            // Update or create banner record in database
            if (!$bannerModel) {
                $bannerModel = new Banner();
            }
            $bannerModel->image = $storedFilename;
            $bannerModel->save();

            // Update the banner URL for display
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

    public function deleteBanner()
    {
        try {
            $bannerModel = Banner::first();

            if ($bannerModel) {
                // Delete the banner file from storage if it exists
                if ($bannerModel->image) {
                    // Handle both old format (full path) and new format (filename only)
                    $existingPath = str_starts_with($bannerModel->image, '/storage/')
                        ? str_replace('/storage/', '', $bannerModel->image)
                        : 'banners/' . $bannerModel->image;

                    if (Storage::disk('public')->exists($existingPath)) {
                        Storage::disk('public')->delete($existingPath);
                    }
                }

                // Delete the banner record from database
                $bannerModel->delete();

                // Clear the current banner URL
                $this->currentBannerUrl = null;

                session()->flash('message', 'Banner berhasil dihapus!');
            } else {
                session()->flash('error', 'Tidak ada banner untuk dihapus.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus banner: ' . $e->getMessage());
        }
    }

    public function saveBannerChanges()
    {
        try {
            // Get or create the banner record
            $banner = Banner::first();
            if (!$banner) {
                $banner = new Banner();
            }

            // The banner image is already saved in uploadBanner method
            // This method can be used for other banner properties if needed
            if ($banner->image) {
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
