<?php

namespace App\Livewire\Admin\components;

use Livewire\Component;
use App\Models\Packet;

class PackageGridComponent extends Component
{
    public $packages = [];

    public function mount()
    {
        $this->loadPackages();
    }

    public function loadPackages()
    {
        $this->packages = Packet::with('discount')->orderBy('created_at', 'desc')->get()->toArray();
    }

    protected function getListeners()
    {
        return [
            'packageAdded' => 'handlePackageAdded',
            'packageUpdated' => 'handlePackageUpdated',
            'packageDeleted' => 'handlePackageDeleted',
        ];
    }

    public function handlePackageAdded($data)
    {
        // Reload packages from database to show the newly added package
        $this->loadPackages();
        
        // Show success message if provided
        if (isset($data['message'])) {
            session()->flash('success', $data['message']);
        }
    }

    public function handlePackageUpdated($data)
    {
        // Reload packages from database to show updated data
        $this->loadPackages();
        
        // Show success message if provided
        if (isset($data['message'])) {
            session()->flash('success', $data['message']);
        }
    }

    public function handlePackageDeleted($data)
    {
        // Reload packages from database to reflect deletion
        $this->loadPackages();
        
        // Show success message if provided
        if (isset($data['message'])) {
            session()->flash('success', $data['message']);
        }
    }

    public function render()
    {
        return view('livewire.admin.components.package-grid-component');
    }
}