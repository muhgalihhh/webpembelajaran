<?php

use App\Livewire\Admin\CrudTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest.custom')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/admin/login', \App\Livewire\Auth\LoginAdmin::class)->name('admin.login');
});



// Route Auth Group Spatie
Route::middleware(['auth'])->group(function () {



    // Rute untuk Siswa (hanya bisa diakses oleh user dengan role 'siswa')
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa', \App\Livewire\Student\Index::class)->name('student.index');
        Route::get('/siswa/dashboard', \App\Livewire\Student\Dashboard::class)->name('student.dashboard');
    });

    // Rute untuk Guru (hanya bisa diakses oleh user dengan role 'guru')
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/guru', \App\Livewire\Teacher\Index::class)->name('teacher.index');
        Route::get('/guru/dashboard', \App\Livewire\Teacher\Dashboard::class)->name('teacher.dashboard');
    });

    // Rute untuk Admin (hanya bisa diakses oleh user dengan role 'admin')
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', \App\Livewire\Admin\Index::class)->name('admin.index');
        Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/admin/teachers', CrudTable::class)->name('admin.manage-teachers');
        Route::get('/admin/students', CrudTable::class)->name('admin.manage-students');
        Route::get('/admin/classes', CrudTable::class)->name('admin.manage-classes');
        Route::get('/admin/subjects', CrudTable::class)->name('admin.manage-subjects');
    });

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

});
