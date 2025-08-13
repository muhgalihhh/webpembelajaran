<?php

namespace App\Livewire\Admin;

use App\Models\Classes;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
#[Title('Manajemen Kelas')]
class ManageClasses extends Component
{
    use WithPagination;

    // Properti filter dan state
    #[Url(as: 'q')]
    public string $search = '';
    public bool $isEditing = false;
    public ?Classes $editingClass = null;
    public ?int $itemToDeleteId = null;

    // --- PERBAIKAN DI SINI ---
    // Properti Form dibuat nullable agar tidak error saat mengedit data kosong
    #[Rule('required|string|max:255', as: 'Nama Kelas')]
    public string $class = '';

    #[Rule('nullable|string|max:255', as: 'ID Grup WhatsApp')]
    public ?string $whatsapp_group_id = null;

    #[Rule('nullable|url', as: 'Link Grup WhatsApp')]
    public ?string $whatsapp_group_link = null;

    #[Rule('nullable|string', as: 'Deskripsi')]
    public ?string $description = null;
    // --- AKHIR PERBAIKAN ---

    // Lifecycle hooks
    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Computed]
    public function classes()
    {
        return Classes::where('class', 'like', '%' . $this->search . '%')
            ->orWhere('whatsapp_group_id', 'like', '%' . $this->search . '%')
            ->orderBy('class')
            ->paginate(10);
    }

    private function resetForm()
    {
        $this->reset(['isEditing', 'editingClass', 'class', 'whatsapp_group_id', 'whatsapp_group_link', 'description']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->isEditing = false;
        $this->resetForm();
        $this->dispatch('open-modal', id: 'class-form-modal');
    }

    public function edit(Classes $class)
    {
        $this->isEditing = true;
        $this->editingClass = $class;
        $this->class = $class->class;
        // Sekarang aman untuk mengisi dengan nilai null dari database
        $this->whatsapp_group_id = $class->whatsapp_group_id;
        $this->whatsapp_group_link = $class->whatsapp_group_link;
        $this->description = $class->description;
        $this->dispatch('open-modal', id: 'class-form-modal');
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->editingClass->update($validatedData);
            $message = 'Kelas berhasil diperbarui.';
        } else {
            Classes::create($validatedData);
            $message = 'Kelas berhasil ditambahkan.';
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
            $class = Classes::find($this->itemToDeleteId);
            // Tambahkan pengecekan relasi sebelum menghapus
            if ($class && $class->users()->count() > 0) {
                $this->dispatch('flash-message', message: 'Kelas tidak dapat dihapus karena masih memiliki siswa.', type: 'error');
            } else {
                $class->delete();
                $this->dispatch('flash-message', message: 'Kelas berhasil dihapus.', type: 'success');
            }
        }
        $this->dispatch('close-confirm-modal');
        $this->itemToDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.manage-classes');
    }
}
