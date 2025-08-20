<?php

namespace App\Livewire\Admin\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\User;

class DeleteUser extends ModalComponent
{
    public $user_id;
    public $name;
    public $email;
    public $school;
    public $grade;

    public function mount($id, $name, $email, $school, $grade)
    {
        $this->user_id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->school = $school;
        $this->grade = $grade;
    }

    public function deleteStudent()
    {
        $user = User::find($this->user_id);

        if ($user) {
            // Detach any related packets
            $user->packets()->detach();
            $user->delete();

            // Dispatch success event to the parent component
            $this->dispatch('student_deleted', [
                'message' => 'Data siswa "' . $this->name . '" berhasil dihapus!',
            ]);
        }

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.components.delete-user');
    }
}
