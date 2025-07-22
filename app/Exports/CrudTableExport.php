<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Opsional: Untuk mengatur lebar kolom otomatis

class CrudTableExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $modelName;
    protected $roleFilter;
    protected $search;
    protected $status_filter;
    protected $class_id_filter;
    protected $is_active_filter;
    protected $sortBy; // Jika Anda menambahkan fitur sorting di CrudTable
    protected $sortDirection; // Jika Anda menambahkan fitur sorting di CrudTable

    public function __construct(
        $modelName,
        $roleFilter,
        $search,
        $status_filter,
        $class_id_filter,
        $is_active_filter,
        $sortBy = 'id', // Default jika tidak dispesifikkan
        $sortDirection = 'asc' // Default jika tidak dispesifikkan
    ) {
        $this->modelName = $modelName;
        $this->roleFilter = $roleFilter;
        $this->search = $search;
        $this->status_filter = $status_filter;
        $this->class_id_filter = $class_id_filter;
        $this->is_active_filter = $is_active_filter;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }

    public function query()
    {
        $query = match ($this->modelName) {
            'User' => User::query()
                ->when($this->roleFilter === 'siswa', fn($q) => $q->with('class'))
                ->when($this->roleFilter, fn($q) => $q->role($this->roleFilter)),
            'Classes' => Classes::query(),
            'Subject' => Subject::query(),
            default => throw new \Exception("Invalid model specified for export: " . $this->modelName)
        };

        // Apply search logic (mirroring CrudTable.php)
        $query->when($this->search, function ($query) {
            if ($this->modelName === 'User') {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            } elseif ($this->modelName === 'Classes') {
                $query->where('name', 'like', '%' . $this->search . '%');
            } elseif ($this->modelName === 'Subject') {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            }
        });

        // Apply filters (mirroring CrudTable.php)
        $query->when($this->modelName === 'User' && $this->status_filter !== 'all', function ($query) {
            $query->where('status', $this->status_filter);
        })
            ->when($this->modelName === 'User' && $this->roleFilter === 'siswa' && $this->class_id_filter !== '', function ($query) {
                $query->where('class_id', $this->class_id_filter);
            })
            ->when($this->modelName === 'Subject' && $this->is_active_filter !== '', function ($query) {
                $query->where('is_active', (bool) $this->is_active_filter);
            });

        // Apply sorting (if CrudTable also implements this)
        // Default sorting is by 'id', adjust if your CrudTable uses different default sorting.
        if (in_array($this->sortBy, ['id', 'name', 'username', 'email', 'status', 'code', 'is_active'])) { // Sesuaikan kolom yang bisa disortir
            $query->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $query->orderBy('id', 'asc'); // Fallback default sorting
        }


        return $query;
    }

    public function headings(): array
    {
        return match ($this->modelName) {
            'User' => [
                'ID',
                'Nama Lengkap',
                'Username',
                'Email',
                'Peran',
                'Kelas', // Untuk Siswa
                'Status',
                'Tanggal Dibuat',
            ],
            'Classes' => [
                'ID',
                'Nama Kelas',
                'Deskripsi',
                'Tanggal Dibuat',
            ],
            'Subject' => [
                'ID',
                'Nama Mata Pelajaran',
                'Kode',
                'Aktif',
                'Tanggal Dibuat',
            ],
            default => [],
        };
    }

    public function map($record): array
    {
        return match ($this->modelName) {
            'User' => [
                $record->id,
                $record->name,
                $record->username,
                $record->email,
                implode(', ', $record->getRoleNames()->toArray()),
                $record->class ? $record->class->name : '-',
                ucfirst($record->status),
                $record->created_at->format('Y-m-d H:i:s'),
            ],
            'Classes' => [
                $record->id,
                $record->name,
                $record->description,
                $record->created_at->format('Y-m-d H:i:s'),
            ],
            'Subject' => [
                $record->id,
                $record->name,
                $record->code,
                $record->is_active ? 'Ya' : 'Tidak',
                $record->created_at->format('Y-m-d H:i:s'),
            ],
            default => [],
        };
    }
}
