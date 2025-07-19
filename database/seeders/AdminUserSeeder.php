<?php

namespace Database\Seeders;

use App\Models\User; // Import model User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Tidak selalu diperlukan jika password di-cast ke 'hashed' di model, tapi bagus untuk eksplisit
use Spatie\Permission\Models\Role; // Import model Role

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan peran 'admin' sudah ada sebelum membuat user
        $adminRole = Role::where('name', 'admin')->first();

        // Jika peran admin belum ada, buat terlebih dahulu (opsional, karena RolesSeeder sudah ada)
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
            $this->command->info('Role admin created.');
        }

        // Buat user admin jika belum ada
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'], // Kriteria untuk mencari user
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'), // Hash password secara manual, meskipun model akan menghash ulang
                'class_id' => null, // Admin tidak terkait dengan kelas
                'status' => 'active',
                'profile_picture' => null,
                'email_verified_at' => now(), // Verifikasi email secara default
            ]
        );

        // Berikan peran 'admin' kepada user
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
            $this->command->info('User admin assigned to admin role.');
        } else {
            $this->command->info('User admin already exists and has admin role.');
        }
    }
}
