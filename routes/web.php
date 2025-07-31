<?php

use App\Livewire\Admin\ManageClasses;
use App\Livewire\Admin\ManageStudents;
use App\Livewire\Admin\ManageSubjects;
use App\Livewire\Admin\ManageTeachers;
use App\Livewire\Admin\ProfileAdmin;
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


    // Rute untuk Admin
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('', \App\Livewire\Admin\Index::class)->name('index');
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/manage-teachers', ManageTeachers::class)->name('manage-teachers');
        Route::get('/manage-students', ManageStudents::class)->name('manage-students');
        Route::get('/manage-classes', ManageClasses::class)->name('manage-classes');
        Route::get('/manage-subjects', ManageSubjects::class)->name('manage-subjects');
        Route::get('/profile', ProfileAdmin::class)->name('profile');
    });

    // Rute untuk Guru
    Route::middleware(['auth', 'role:guru'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/', \App\Livewire\Teacher\Index::class)->name('index');
        Route::get('/dashboard', \App\Livewire\Teacher\Dashboard::class)->name('dashboard');
        Route::get('/materials', \App\Livewire\Teacher\ManageMaterials::class)->name('materials');
        Route::get('/materials/create', \App\Livewire\Teacher\MaterialForm::class)->name('materials.create');
        Route::get('/materials/{material}/edit', \App\Livewire\Teacher\MaterialForm::class)->name('materials.edit');
        Route::get('/quizzes', \App\Livewire\Teacher\ManageQuizzes::class)->name('quizzes');
        Route::get('/quizzes/{quiz}/questions', \App\Livewire\Teacher\QuizQuestions::class)->name('quizzes.questions');
        Route::get('/task', \App\Livewire\Teacher\ManageTasks::class)->name('tasks');
        Route::get('/scores/tasks', \App\Livewire\Teacher\ScoreTaskList::class)->name('scores.tasks');
        Route::get('/scores/tasks/{task}/submissions', \App\Livewire\Teacher\ScoreTaskSubmissions::class)->name('scores.submissions');
        Route::get('/games', \App\Livewire\Teacher\ManageEducationalGames::class)->name('games');
        Route::get('/profile', \App\Livewire\Teacher\ProfileGuru::class)->name('profile');
    });

    // Rute untuk Siswa (contoh)
    Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {

        Route::get('/', \App\Livewire\Student\Index::class)->name('index');
        Route::get('/dashboard', \App\Livewire\Student\Dashboard::class)->name('dashboard');
        Route::get('/subjects', action: \App\Livewire\Student\SubjectList::class)->name('subjects');
    });


    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

});
