<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;
use App\Models\Material;

class VideoManagementComponent extends Component
{
    public $featuredVideos = [];
    public $isLoading = false;

    public function mount()
    {
        $this->refreshVideos();
    }

    public function refreshVideos()
    {
        $this->isLoading = true;

        // Get first 4 videos for display on landing page
        $videos = Material::orderBy('id')->take(4)->get();

        // Create array with exactly 4 slots
        $this->featuredVideos = [];
        for ($i = 0; $i < 4; $i++) {
            $this->featuredVideos[] = $videos->get($i) ?? null;
        }

        $this->isLoading = false;
    }


    protected function getListeners()
    {
        return [
            'videoUpdated' => 'handleVideoUpdated',
        ];
    }

    public function handleVideoUpdated($data)
    {
        // Reload videos from database to show updated data
        $this->refreshVideos();

        // Show success message if provided
        if (isset($data['message'])) {
            session()->flash('success', $data['message']);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.video-management-component');
    }
}
