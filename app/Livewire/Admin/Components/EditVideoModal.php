<?php

namespace App\Livewire\Admin\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EditVideoModal extends ModalComponent
{
    use WithFileUploads;

    public $video_id;
    public $title;
    public $subject;
    public $publisher;
    public $video;
    public $existing_video;
    public $is_editing = false;
    
    public $networkError = '';
    public $isSubmitting = false;
    
    protected $messages = [
        'title.required' => 'Judul video harus diisi',
        'title.string' => 'Judul video harus berupa teks',
        'title.max' => 'Judul video maksimal 255 karakter',
        'subject.required' => 'Mata pelajaran harus diisi',
        'subject.string' => 'Mata pelajaran harus berupa teks',
        'subject.max' => 'Mata pelajaran maksimal 100 karakter',
        'publisher.required' => 'Penerbit harus diisi',
        'publisher.string' => 'Penerbit harus berupa teks',
        'publisher.max' => 'Penerbit maksimal 100 karakter',
        'video.required' => 'File video harus diupload',
        'video.file' => 'Video harus berupa file',
        'video.mimes' => 'Video harus berformat mp4, avi, mov, wmv, flv, webm',
        'video.max' => 'Ukuran video maksimal 150MB',
    ];
    
    public function mount($videoId = null)
    {
        $this->video_id = $videoId;
        
        if ($videoId) {
            $this->is_editing = true;
            $video = Material::find($videoId);
            if ($video) {
                $this->title = $video->title;
                $this->subject = $video->subject;
                $this->publisher = $video->publisher;
                $this->existing_video = $video->video;
            }
        }
    }
    
    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:100',
            'publisher' => 'required|string|max:100',
        ];
        
        if (!$this->is_editing || $this->video) {
            $rules['video'] = [
                'required',
                'file',
                'mimes:mp4,avi,mov,wmv,flv,webm',
                'max:153600', // 150MB in KB
            ];
        }
        
        return $rules;
    }
    
    public function updated($propertyName)
    {
        $this->networkError = '';
        
        // Special handling for video file uploads
        if ($propertyName === 'video' && $this->video) {
            try {
                // Pre-validate the file
                $this->validateOnly($propertyName);
                
                // Log successful file selection for debugging
                Log::info('Video file selected', [
                    'original_name' => $this->video->getClientOriginalName(),
                    'size' => $this->video->getSize(),
                    'mime_type' => $this->video->getMimeType(),
                    'user_id' => Auth::id()
                ]);
                
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Log validation errors for debugging
                Log::warning('Video file validation failed on selection', [
                    'errors' => $e->errors(),
                    'user_id' => Auth::id()
                ]);
                // Validation errors are automatically handled by Livewire
            }
        } else {
            try {
                $this->validateOnly($propertyName);
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Validation errors are automatically handled by Livewire
            }
        }
    }
    
    public function save()
    {
        $this->networkError = '';
        $this->isSubmitting = true;
        
        try {
            $this->validate();
            
            $data = [
                'title' => $this->title,
                'subject' => $this->subject,
                'publisher' => $this->publisher,
            ];
            
            // Handle video file upload
            if ($this->video) {
                // Verify the file is actually uploaded and valid
                if (!$this->video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    throw new \Exception('Invalid file upload object');
                }
                
                // Check if temporary file exists
                if (!$this->video->exists()) {
                    throw new \Exception('Temporary file not found. Please try uploading again.');
                }
                
                // Get file information for logging
                $originalName = $this->video->getClientOriginalName();
                $fileSize = $this->video->getSize();
                $mimeType = $this->video->getMimeType();
                
                Log::info('Processing video upload', [
                    'original_name' => $originalName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'user_id' => Auth::id()
                ]);
                
                // Store the file with a unique name
                $videoPath = $this->video->store('videos', 'public');
                
                if (!$videoPath) {
                    throw new \Exception('Failed to store video file');
                }
                
                $data['video'] = $videoPath;
                
                Log::info('Video stored successfully', [
                    'stored_path' => $videoPath,
                    'user_id' => Auth::id()
                ]);
            }
            
            if ($this->is_editing) {
                $material = Material::find($this->video_id);
                if ($material) {
                    // Delete old video file if new one is uploaded
                    if ($this->video && $material->video) {
                        $oldPath = $material->video;
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                            Log::info('Deleted old video file', ['path' => $oldPath]);
                        }
                    }
                    $material->update($data);
                    $message = 'Video "' . $this->title . '" berhasil diperbarui!';
                } else {
                    throw new \Exception('Video not found for editing');
                }
            } else {
                Material::create($data);
                $message = 'Video "' . $this->title . '" berhasil ditambahkan!';
            }
            
            $this->dispatch('videoUpdated', [
                'message' => $message,
            ]);
            
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            Log::warning('Video upload validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Video upload error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'video_title' => $this->title,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            // Provide more specific error messages
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'size') || str_contains($errorMessage, 'file is too large')) {
                $this->networkError = 'File video terlalu besar. Pastikan ukuran file tidak melebihi 150MB.';
            } elseif (str_contains($errorMessage, 'Temporary file not found')) {
                $this->networkError = 'File temporary tidak ditemukan. Silakan upload ulang video Anda.';
            } elseif (str_contains($errorMessage, 'Invalid file upload')) {
                $this->networkError = 'Format file tidak valid. Pastikan Anda mengupload file video yang didukung.';
            } elseif (str_contains($errorMessage, 'Failed to store')) {
                $this->networkError = 'Gagal menyimpan video. Periksa kapasitas penyimpanan dan coba lagi.';
            } elseif (str_contains($errorMessage, 'upload') || str_contains($errorMessage, 'failed')) {
                $this->networkError = 'Video gagal diupload. Periksa koneksi internet dan coba lagi.';
            } elseif (str_contains($errorMessage, 'storage') || str_contains($errorMessage, 'disk')) {
                $this->networkError = 'Terjadi kesalahan pada penyimpanan file. Silakan hubungi administrator.';
            } else {
                $this->networkError = 'Terjadi kesalahan saat menyimpan data: ' . $errorMessage;
            }
            
            $this->isSubmitting = false;
        }
    }
    
    public function cancel()
    {
        $this->closeModal();
    }
    
    public static function modalMaxWidth(): string
    {
        return 'lg';
    }
    
    public function render()
    {
        return view('livewire.admin.components.edit-video-modal');
    }
}
