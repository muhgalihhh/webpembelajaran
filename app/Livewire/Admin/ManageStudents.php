<?php

namespace App\Livewire\Admin;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
#[Title('Manajemen Siswa')]
class ManageStudents extends Component
{
    use WithPagination;

    // Properti untuk state & filter
    #[Url(as: 'q', history: true)]
    public $search = '';
    #[Url(history: true)]
    public $status_filter = 'all';
    #[Url(history: true)]
    public $class_filter = '';
    #[Url(history: true)]
    public $sortBy = 'name';
    #[Url(history: true)]
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Properti Form
    public $name, $username, $email, $phone_number, $password, $password_confirmation, $status;
    public $class_id;

    public $isEditing = false;
    public ?User $editingUser = null;
    public ?User $viewingUser = null;
    public $itemToDeleteId = null;

    // Lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    public function updatingClassFilter()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        $userId = $this->editingUser?->id;
        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
            'password' => $this->isEditing ? 'nullable|min:8|same:password_confirmation' : 'required|min:8|same:password_confirmation',
            'status' => 'required|in:active,inactive',
            'class_id' => 'required|exists:classes,id',
        ];
    }

    #[Computed]
    public function students()
    {
        return User::role('siswa')
            ->with('class')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%'); // Tambahkan pencarian no. telp
            })
            ->when($this->status_filter !== 'all', fn($q) => $q->where('status', $this->status_filter))
            ->when($this->class_filter, fn($q) => $q->where('class_id', $this->class_filter))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function availableClasses()
    {
        return Classes::orderBy('class', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.admin.manage-students');
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingUser', 'name', 'username', 'email', 'phone_number', 'password', 'password_confirmation', 'status', 'class_id']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->status = 'active';
        $this->dispatch('open-modal', id: 'student-form-modal');
    }

    public function edit(User $user)
    {
        $this->isEditing = true;
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->status = $user->status;
        $this->class_id = $user->class_id;
        $this->dispatch('open-modal', id: 'student-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }
            $this->editingUser->update($validatedData);
            $message = 'Data siswa berhasil diperbarui.';
        } else {
            $validatedData['password'] = Hash::make($validatedData['password']);
            $user = User::create($validatedData);
            $user->assignRole('siswa');
            $message = 'Data siswa berhasil ditambahkan.';
        }

        $this->dispatch('flash-message', message: $message, type: 'success');
        $this->dispatch('close-modal');
    }

    public function confirmDelete($id)
    {
        $this->itemToDeleteId = $id;
        $this->dispatch('open-confirm-modal');
    }

    public function delete()
    {
        if ($this->itemToDeleteId) {
            User::findOrFail($this->itemToDeleteId)->delete();
            $this->dispatch('flash-message', message: 'Data siswa berhasil dihapus.', type: 'success');
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
        $this->resetPage();
    }

    public function view(User $user)
    {
        $this->viewingUser = $user;
        $this->dispatch('open-user-detail-modal');
    }
}
