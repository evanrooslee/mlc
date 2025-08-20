<?php

namespace App\Livewire\Admin\Components;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use App\Services\ImageService;

class EditArticleModal extends ModalComponent
{
    use WithFileUploads;

    public $articleId;
    public $title;
    public $source;
    public $url;
    public $image;
    public $current_image;
    public $is_starred;

    // Error handling properties
    public $networkError = '';
    public $isSubmitting = false;

    protected $rules = [
        'title' => 'required|string|min:3|max:255',
        'source' => 'required|string|min:2|max:255',
        'url' => 'required|url|max:500',
        'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048|dimensions:min_width=100,min_height=100',
        'is_starred' => 'boolean',
    ];

    protected $messages = [
        'title.required' => 'Judul artikel harus diisi',
        'title.string' => 'Judul artikel harus berupa teks',
        'title.max' => 'Judul artikel maksimal 255 karakter',
        'title.min' => 'Judul artikel minimal 3 karakter',
        'source.required' => 'Sumber artikel harus diisi',
        'source.string' => 'Sumber artikel harus berupa teks',
        'source.max' => 'Sumber artikel maksimal 255 karakter',
        'source.min' => 'Sumber artikel minimal 2 karakter',
        'url.required' => 'URL artikel harus diisi',
        'url.url' => 'Format URL artikel tidak valid. Contoh: https://example.com',
        'url.max' => 'URL artikel maksimal 500 karakter',
        'url.active_url' => 'URL artikel tidak dapat diakses atau tidak valid',
        'image.image' => 'File harus berupa gambar (JPG, PNG, GIF, atau WebP)',
        'image.max' => 'Ukuran gambar maksimal 2MB (2048 KB)',
        'image.mimes' => 'Format gambar harus JPG, PNG, GIF, atau WebP',
        'image.dimensions' => 'Dimensi gambar minimal 100x100 pixel',
    ];

    public function mount($articleId)
    {
        $this->articleId = $articleId;

        // Fetch article data from database
        $article = Article::find($articleId);

        if ($article) {
            $this->title = $article->title;
            $this->source = $article->source;
            $this->url = $article->url;
            $this->current_image = $article->image;
            $this->is_starred = $article->is_starred;
        }
    }

    // Real-time validation methods
    public function updated($propertyName)
    {
        // Clear network error when user starts typing
        $this->networkError = '';

        // Validate specific field on update
        try {
            $this->validateOnly($propertyName);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
        }
    }

    public function update()
    {
        // Clear previous errors
        $this->networkError = '';
        $this->isSubmitting = true;

        try {
            // Validate all fields
            $this->validate();

            // Find the article
            $article = Article::find($this->articleId);
            if (!$article) {
                throw new \Exception('Artikel tidak ditemukan.');
            }

            // Handle image upload with resizing
            $imageName = $this->current_image; // Keep current image by default
            if ($this->image) {
                try {
                    // Validate image file before processing
                    if (!$this->image->isValid()) {
                        throw new \Exception('File gambar tidak valid atau rusak');
                    }

                    // Check file size (additional check beyond validation)
                    if ($this->image->getSize() > 2048 * 1024) {
                        throw new \Exception('Ukuran file gambar terlalu besar (maksimal 2MB)');
                    }

                    // Check if article type exists in config, otherwise use medium size
                    $imageType = config('image.dimensions.article') ? 'article' : 'medium';

                    // Use ImageService to resize and save the image
                    $imageService = new ImageService();
                    $path = $imageService->resizeForType($this->image, $imageType, 'articles');

                    // Extract just the filename from the path for database storage
                    $imageName = basename($path);

                    Log::info('Image successfully processed and saved', [
                        'original_name' => $this->image->getClientOriginalName(),
                        'saved_name' => $imageName,
                        'type' => $imageType
                    ]);
                } catch (\Exception $e) {
                    Log::error('Image processing failed', [
                        'error' => $e->getMessage(),
                        'file_name' => $this->image->getClientOriginalName() ?? 'unknown',
                        'file_size' => $this->image->getSize() ?? 0,
                        'article_id' => $this->articleId
                    ]);

                    // Try fallback: Save without resizing
                    try {
                        $imageName = time() . '_' . uniqid() . '.' . $this->image->getClientOriginalExtension();
                        $path = $this->image->storePubliclyAs('articles', $imageName, 'public');

                        Log::warning('Image saved without resizing as fallback', [
                            'saved_name' => $imageName
                        ]);
                    } catch (\Exception $fallbackError) {
                        Log::error('Image fallback save also failed', [
                            'original_error' => $e->getMessage(),
                            'fallback_error' => $fallbackError->getMessage()
                        ]);

                        throw new \Exception('Gagal menyimpan gambar. Silakan coba dengan gambar lain atau hubungi administrator.');
                    }
                }
            }

            // Handle star status
            $wasStarred = $article->is_starred;
            $shouldBeStar = $this->is_starred;

            // Update the article
            $article->update([
                'title' => $this->title,
                'source' => $this->source,
                'url' => $this->url,
                'image' => $imageName,
            ]);

            // Handle starring logic separately to ensure constraint is maintained
            if ($wasStarred !== $shouldBeStar) {
                if ($shouldBeStar) {
                    Article::setStarred($this->articleId);
                } else {
                    $article->is_starred = false;
                    $article->save();
                }
            }

            // Dispatch success event
            $this->dispatch('articleUpdated', [
                'message' => 'Artikel berhasil diperbarui',
                'article' => $article->fresh()->toArray()
            ]);

            $this->closeModal();
            $this->isSubmitting = false;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            // Handle network/server errors
            $this->networkError = $e->getMessage() ?: 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.';
            $this->isSubmitting = false;
        }
    }

    public function cancel()
    {
        $this->resetErrorStates();
        $this->closeModal();
    }

    // Handle browser back button or ESC key
    public function dehydrate()
    {
        // Ensure form is clean when component is dehydrated
        if ($this->networkError) {
            $this->networkError = '';
        }
    }

    // Handle modal close event (when user clicks outside or presses ESC)
    public function closeModalAction()
    {
        $this->resetErrorStates();
        $this->closeModal();
    }

    // Reset error states and cleanup
    private function resetErrorStates()
    {
        $this->networkError = '';
        $this->isSubmitting = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Specify the modal size.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function render()
    {
        return view('livewire.admin.components.edit-article-modal');
    }
}
