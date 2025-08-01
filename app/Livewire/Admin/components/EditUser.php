<?php

namespace App\Livewire\Admin\components;

use LivewireUI\Modal\ModalComponent;
use App\Models\User;

class EditUser extends ModalComponent
{
    public $user_id;
    public $name;
    public $email;
    public $phone_number;
    public $parents_phone_number;
    public $school;
    public $grade;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone_number' => 'required|string|max:20',
        'parents_phone_number' => 'nullable|string|max:20',
        'school' => 'required|string|max:255',
        'grade' => 'required|integer',
    ];
    
    protected $messages = [
        'name.required' => 'Nama siswa wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'phone_number.required' => 'Nomor HP siswa wajib diisi.',
        'school.required' => 'Sekolah wajib diisi.',
        'grade.required' => 'Kelas wajib diisi.',
        'grade.integer' => 'Kelas harus berupa angka.',
    ];
    
    public function mount($user_id)
    {
        $user = User::findOrFail($user_id);
        
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->parents_phone_number = $user->parents_phone_number;
        $this->school = $user->school;
        $this->grade = $user->grade;
    }
    
    public function updateStudent()
    {
        $this->validate();
        
        $user = User::findOrFail($this->user_id);
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'parents_phone_number' => $this->parents_phone_number,
            'school' => $this->school,
            'grade' => $this->grade,
        ]);
        
        // Dispatch success event to the parent component
        $this->dispatch('student_updated', [
            'message' => 'Data siswa "' . $this->name . '" berhasil diperbarui!',
        ]);
        
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.components.edit-user');
    }
}
