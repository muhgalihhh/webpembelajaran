<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Rule;

use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule as ValidationRule;
use Maatwebsite\Excel\Facades\Excel; // Tambahkan ini
use App\Exports\CrudTableExport; // Tambahkan ini

#[Layout('layouts.dashboard')]
// Kondisis title sesuai route
#[Title('Admin | Manage ')]
class CrudTable extends Component
{
    use WithPagination;

    #[Url]
    public $modelName;

    #[Url]
    public $roleFilter = null;

    #[Url(as: 'q')]
    public $search = '';

    #[Url]
    public $perPage = 10;

    // Filter properties for table
    #[Url]
    public $status_filter = 'all'; // Menggunakan nama berbeda agar tidak konflik dengan $this->status form
    #[Url]
    public $class_id_filter = ''; // Menggunakan nama berbeda
    #[Url]
    public $is_active_filter = ''; // Menggunakan nama berbeda

    // Sorting properties (Jika Anda ingin menambahkan fitur sorting di tabel)
    #[Url]
    public $sortBy = 'id';
    #[Url]
    public $sortDirection = 'asc';

    public $confirmingRecordId = null;
    public $confirmingAction = null;


    public $showFormModal = false; // Directly controls modal visibility via Alpine.js
    public $isEditing = false;
    public $recordId = null;

    // Form fields - ini akan divalidasi secara dinamis
    #[Rule('required|string|max:255')]
    public $name = ''; // Untuk nama User

    public $username = '';
    public $email = '';

    #[Rule('nullable|string|min:8|same:password_confirmation')]
    public $password = '';
    public $password_confirmation = '';

    public $class_id = null; // Untuk class_id form User (siswa)
    #[Rule('required|in:active,inactive')]
    public $status = 'active'; // Untuk status form User

    public $item_name = ''; // Untuk nama Classes dan Subject
    #[Rule('nullable|string')]
    public $description = ''; // Untuk description Classes

    public $code = ''; // Untuk code Subject
    #[Rule('boolean')]
    public $is_active = true; // Untuk is_active Subject


    public function mount()
    {
        $routeName = request()->route()->getName();

        switch ($routeName) {
            case 'admin.manage-teachers':
                $this->modelName = 'User';
                $this->roleFilter = 'guru';
                // Reset filter spesifik jika berpindah halaman
                $this->class_id_filter = '';
                $this->is_active_filter = '';
                // Default sorting for teachers
                $this->sortBy = 'name';
                $this->sortDirection = 'asc';
                break;
            case 'admin.manage-students':
                $this->modelName = 'User';
                $this->roleFilter = 'siswa';
                $this->is_active_filter = '';
                // Default sorting for students
                $this->sortBy = 'name';
                $this->sortDirection = 'asc';
                break;
            case 'admin.manage-classes':
                $this->modelName = 'Classes';
                $this->roleFilter = null;
                $this->status_filter = 'all';
                $this->class_id_filter = '';
                $this->is_active_filter = '';
                // Default sorting for classes
                $this->sortBy = 'name';
                $this->sortDirection = 'asc';
                break;
            case 'admin.manage-subjects':
                $this->modelName = 'Subject';
                $this->roleFilter = null;
                $this->status_filter = 'all';
                $this->class_id_filter = '';
                // Default sorting for subjects
                $this->sortBy = 'name';
                $this->sortDirection = 'asc';
                break;
            default:
                throw new \Exception("CrudTable: Unknown route name encountered.");
        }
        $this->resetForm();
    }

    public function getModel()
    {
        if (empty($this->modelName)) {
            throw new \Exception("Model name is not set when calling getModel(). This should not happen if mount() runs correctly.");
        }
        return match ($this->modelName) {
            'User' => User::query()
                ->when($this->roleFilter === 'siswa', fn($query) => $query->with('class')) // Eager load class for students
                ->when($this->roleFilter, fn($query) => $query->role($this->roleFilter)),
            'Classes' => Classes::query(),
            'Subject' => Subject::query(),
            default => throw new \Exception("Invalid model specified: " . $this->modelName)
        };
    }

    public function render()
    {
        if (empty($this->modelName)) {
            return view('livewire.error-display', ['message' => 'Model configuration missing. Please check route setup.']);
        }

        $query = $this->getModel(); // Query sudah mengandung filter role jika modelnya User

        $query->when($this->search, function ($query) {
            if ($this->modelName === 'User') {
                // PENTING: KONDISI OR HARUS DI DALAM NESTED WHERE UNTUK PENGELOMPOKAN BENAR
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            } elseif ($this->modelName === 'Classes') {
                $query->where('name', 'like', '%' . $this->search . '%');
            } elseif ($this->modelName === 'Subject') {
                // PENTING: KONDISI OR UNTUK SUBJECT JUGA HARUS DI DALAM NESTED WHERE
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            }
        });

        // Apply filters based on modelName using filter properties
        $query->when($this->modelName === 'User' && $this->status_filter !== 'all', function ($query) {
            $query->where('status', $this->status_filter);
        })
            ->when($this->modelName === 'User' && $this->roleFilter === 'siswa' && $this->class_id_filter !== '', function ($query) {
                $query->where('class_id', $this->class_id_filter);
            })
            ->when($this->modelName === 'Subject' && $this->is_active_filter !== '', function ($query) {
                $query->where('is_active', (bool) $this->is_active_filter);
            });

        // Apply sorting (Assuming sortBy and sortDirection properties are defined)
        $query->orderBy($this->sortBy, $this->sortDirection);


        $records = $query->paginate($this->perPage);

        $classes = ($this->modelName === 'User' && $this->roleFilter === 'siswa') ? Classes::all() : collect();
        $roles = ($this->modelName === 'User') ? Role::all()->pluck('name') : collect();

        return view('livewire.admin.crud-table', [
            'records' => $records,
            'availableClasses' => $classes,
            'availableRoles' => $roles,
        ]);
    }

    // Update methods for filters to reset pagination
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    public function updatingClassIdFilter()
    {
        $this->resetPage();
    }
    public function updatingIsActiveFilter()
    {
        $this->resetPage();
    }

    // Method for sorting
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }


    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        // Set default status for User creation if applicable
        if ($this->modelName === 'User') {
            $this->status = 'active';
            $this->class_id = null; // Ensure class_id is reset for new user
        }
        // Set default is_active for Subject creation
        if ($this->modelName === 'Subject') {
            $this->is_active = true;
        }

        $this->showFormModal = true;
    }

    public function store()
    {
        $rules = $this->getDynamicValidationRulesForStore();
        $this->validate($rules);

        if ($this->modelName === 'User') {
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'class_id' => ($this->roleFilter === 'siswa') ? $this->class_id : null,
                'status' => $this->status,
            ]);
            $user->assignRole($this->roleFilter);
            session()->flash('success', ucfirst($this->roleFilter) . ' account created successfully!');
        } elseif ($this->modelName === 'Classes') {
            Classes::create([
                'name' => $this->item_name,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Class created successfully!');
        } elseif ($this->modelName === 'Subject') {
            Subject::create([
                'name' => $this->item_name,
                'code' => $this->code,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Subject created successfully!');
        }

        $this->resetForm();
        $this->showFormModal = false;
    }

    public function edit($id)
    {
        $this->recordId = $id;
        $this->isEditing = true;
        $record = $this->getModel()->findOrFail($id);

        if ($this->modelName === 'User') {
            $this->name = $record->name;
            $this->username = $record->username;
            $this->email = $record->email;
            $this->class_id = $record->class_id;
            $this->status = $record->status;
            $this->password = ''; // Clear password field for editing
            $this->password_confirmation = '';
        } elseif ($this->modelName === 'Classes') {
            $this->item_name = $record->name;
            $this->description = $record->description;
        } elseif ($this->modelName === 'Subject') {
            $this->item_name = $record->name;
            $this->code = $record->code;
            $this->is_active = $record->is_active;
        }

        $this->showFormModal = true;
    }

    public function update()
    {
        $rules = $this->getDynamicValidationRulesForUpdate();
        $this->validate($rules);

        $record = $this->getModel()->findOrFail($this->recordId);

        if ($this->modelName === 'User') {
            $record->update([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $record->password,
                'class_id' => ($this->roleFilter === 'siswa') ? $this->class_id : null, // Ensure null if not student
                'status' => $this->status,
            ]);
            session()->flash('success', ucfirst($this->roleFilter) . ' account updated successfully!');
        } elseif ($this->modelName === 'Classes') {
            $record->update([
                'name' => $this->item_name,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Class updated successfully!');
        } elseif ($this->modelName === 'Subject') {
            $record->update([
                'name' => $this->item_name,
                'code' => $this->code,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Subject updated successfully!');
        }

        $this->resetForm();
        $this->showFormModal = false;
    }

    public function confirmDelete($id)
    {
        $this->confirmingRecordId = $id;
        $this->confirmingAction = 'delete';
        $this->dispatch(
            'open-confirm-dialog',
            title: 'Konfirmasi Penghapusan',
            message: 'Apakah Anda yakin ingin menghapus data ini? Aksi ini tidak dapat dibatalkan.',
            confirmEvent: 'performDeleteRecord', // Event yang akan dipicu jika "Yakin"
            denyEvent: 'close-confirm-dialog' // Opsional, event jika "Batal"
        );
    }

    // Listener untuk event performDeleteRecord
    #[On('performDeleteRecord')]
    public function delete()
    {
        if ($this->confirmingRecordId) {
            $record = $this->getModel()->findOrFail($this->confirmingRecordId);
            $record->delete();
            session()->flash('success', 'Data berhasil dihapus!');
            $this->confirmingRecordId = null;
            $this->confirmingAction = null;
            $this->resetPage(); // Refresh tabel
        }
    }


    // Ganti metode resetPasswordConfirmation agar meminta konfirmasi
    public function confirmResetPassword($userId)
    {
        $this->confirmingRecordId = $userId;
        $this->confirmingAction = 'reset_password';
        $user = User::findOrFail($userId); // Dapatkan nama untuk pesan
        $this->dispatch(
            'open-confirm-dialog',
            title: 'Konfirmasi Reset Password',
            message: "Apakah Anda yakin ingin mereset password untuk {$user->name} menjadi 'password'?",
            confirmEvent: 'performResetPassword', // Event yang akan dipicu jika "Yakin"
            denyEvent: 'close-confirm-dialog' // Opsional
        );
    }

    // Listener untuk event performResetPassword
    #[On('performResetPassword')]
    public function resetPassword()
    {
        if ($this->confirmingRecordId) {
            $user = User::findOrFail($this->confirmingRecordId);
            if ($user->hasRole($this->roleFilter)) {
                $user->update(['password' => Hash::make('password')]);
                session()->flash('success', 'Password untuk ' . $user->name . ' berhasil direset menjadi "password".');
            } else {
                session()->flash('error', 'Gagal mereset password: pengguna tidak ditemukan atau tidak memiliki peran yang sesuai.');
            }
            $this->confirmingRecordId = null;
            $this->confirmingAction = null;
        }
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->class_id = null;
        $this->status = 'active'; // Default for user forms
        $this->item_name = '';
        $this->description = '';
        $this->code = '';
        $this->is_active = true; // Default for subject forms
        $this->recordId = null;
        $this->isEditing = false;
        $this->confirmingRecordId = null; // Reset properti konfirmasi
        $this->confirmingAction = null; // Reset properti konfirmasi
    }

    protected function getDynamicValidationRulesForStore(): array
    {
        $rules = [
            'password' => 'required|string|min:8|same:password_confirmation',
        ];

        if ($this->modelName === 'User') {
            $rules['username'] = 'required|string|max:255|unique:users,username';
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
            $rules['class_id'] = $this->roleFilter === 'siswa' ? 'nullable|exists:classes,id' : 'nullable';
            $rules['name'] = 'required|string|max:255';
            $rules['status'] = 'required|in:active,inactive';
        } elseif ($this->modelName === 'Classes') {
            $rules['item_name'] = 'required|string|max:255|unique:classes,name';
            $rules['description'] = 'nullable|string';
        } elseif ($this->modelName === 'Subject') {
            $rules['item_name'] = 'required|string|max:255|unique:subjects,name';
            $rules['code'] = 'required|string|max:50|unique:subjects,code';
            $rules['is_active'] = 'boolean';
        }

        return $rules;
    }

    protected function getDynamicValidationRulesForUpdate(): array
    {
        $rules = [
            'password' => 'nullable|string|min:8|same:password_confirmation',
        ];
        if ($this->modelName === 'User') {
            $rules['username'] = ['required', 'string', 'max:255', ValidationRule::unique('users', 'username')->ignore($this->recordId)];
            $rules['email'] = ['required', 'string', 'email', 'max:255', ValidationRule::unique('users', 'email')->ignore($this->recordId)];
            $rules['class_id'] = $this->roleFilter === 'siswa' ? 'nullable|exists:classes,id' : 'nullable';
            $rules['name'] = 'required|string|max:255';
            $rules['status'] = 'required|in:active,inactive';
        } elseif ($this->modelName === 'Classes') {
            $rules['item_name'] = ['required', 'string', 'max:255', ValidationRule::unique('classes', 'name')->ignore($this->recordId)];
            $rules['description'] = 'nullable|string';
        } elseif ($this->modelName === 'Subject') {
            $rules['item_name'] = ['required', 'string', 'max:255', ValidationRule::unique('subjects', 'name')->ignore($this->recordId)];
            $rules['code'] = ['required', 'string', 'max:50', ValidationRule::unique('subjects', 'code')->ignore($this->recordId)];
            $rules['is_active'] = 'boolean';
        }

        return $rules;
    }

    // Method to export records
    public function exportRecords()
    {
        $fileName = $this->modelName . '_' . ($this->roleFilter ? $this->roleFilter . '_' : '') . date('Ymd_His') . '.xlsx';

        return Excel::download(
            new CrudTableExport(
                $this->modelName,
                $this->roleFilter,
                $this->search,
                $this->status_filter,
                $this->class_id_filter,
                $this->is_active_filter,
                $this->sortBy,
                $this->sortDirection
            ),
            $fileName
        );
    }

    public function resetPasswordConfirmation($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->hasRole($this->roleFilter)) {
            $user->update(['password' => Hash::make('password')]);
            session()->flash('success', 'Password untuk ' . $user->name . ' berhasil direset menjadi "password".');
        } else {
            session()->flash('error', 'Gagal mereset password: pengguna tidak ditemukan atau tidak memiliki peran yang sesuai.');
        }
        $this->dispatch('close-logout-modal');
    }
}
