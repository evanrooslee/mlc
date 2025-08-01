<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\User;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Support\Facades\DB;

class TabelDataSiswa extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    #[Url(except: 'name')]
    public $sortBy = 'name';

    #[Url(except: 'asc')]
    public $sortDirection = 'asc';

    public $flash_message = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    // Listeners for modal events
    protected $listeners = [
        'student_updated' => 'handleStudentUpdated',
        'student_deleted' => 'handleStudentDeleted',
    ];

    // Handle student updated event
    public function handleStudentUpdated($event_data = [])
    {
        $this->flash_message = $event_data['message'] ?? 'Data siswa berhasil diperbarui!';
    }

    // Handle student deleted event
    public function handleStudentDeleted($event_data = [])
    {
        $this->flash_message = $event_data['message'] ?? 'Data siswa berhasil dihapus!';
        $this->resetPage();
    }

    // Clear flash message
    public function clearFlashMessage()
    {
        $this->flash_message = '';
    }

    public function updatingSearch()
    {
        $this->clearFlashMessage();
        $this->resetPage();
    }

    public function sortByColumn($field)
    {
        $this->clearFlashMessage();

        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $students = User::where('role', 'student')
            ->with('packets')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('parents_phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('school', 'like', '%' . $this->search . '%')
                        ->orWhere('grade', 'like', '%' . $this->search . '%')
                        ->orWhereHas('packets', function ($packetQuery) {
                            $packetQuery->where('code', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->sortBy === 'packets', function ($query) {
                // Sort by packet codes (concatenated) - SQLite compatible
                $query->leftJoin('packet_user', 'users.id', '=', 'packet_user.user_id')
                    ->leftJoin('packets', 'packet_user.packet_id', '=', 'packets.id')
                    ->select('users.*')
                    ->groupBy('users.id', 'users.name', 'users.email', 'users.phone_number', 'users.parents_phone_number', 'users.school', 'users.grade', 'users.role', 'users.password', 'users.remember_token', 'users.created_at', 'users.updated_at')
                    ->orderBy(DB::raw('GROUP_CONCAT(packets.code, ", ")'), $this->sortDirection);
            }, function ($query) {
                // Default sorting for other columns
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.tabel-data-siswa', compact('students'));
    }
}
