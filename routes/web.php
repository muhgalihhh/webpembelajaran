<?php

use App\Livewire\Admin\ManageClasses;
use App\Livewire\Admin\ManageStudents;
use App\Livewire\Admin\ManageSubjects;
use App\Livewire\Admin\ManageTeachers;
use App\Livewire\Admin\ProfileAdmin;
use App\Models\Classes;
use App\Models\Material;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest.custom')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/admin/login', \App\Livewire\Auth\LoginAdmin::class)->name('admin.login');
    Route::get('/lupa-sandi', \App\Livewire\Auth\ForgotPassword::class)->name('password.request');
    Route::get('/reset-sandi/{token}', \App\Livewire\Auth\ResetPassword::class)->name('password.reset');
});


// Route Auth Group Spatie
Route::middleware(['auth'])->group(function () {

    Route::get('api/user', function () {
        return response()->json(['user' => Auth::user()]);
    })->name('api.user');

    Route::get('/materials/{material}/download', [\App\Http\Controllers\FileController::class, 'downloadMaterial'])
        ->name('materials.download');

    Route::get('/view/materi/{material}', function (Material $material) {

        if (!Storage::disk('local')->exists($material->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        return response()->file(Storage::disk('local')->path($material->file_path));
    })->name('materials.view');

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
        Route::get('/rankings', \App\Livewire\Teacher\StudentRanking::class)->name('rankings');
        Route::get('/about-us', \App\Livewire\Teacher\AboutUs::class)->name('about-us');
    });

    // Rute untuk Siswa
    Route::middleware(['auth', 'role:siswa'])->prefix('student')->name('student.')->group(function () {
        Route::get('/', \App\Livewire\Student\Index::class)->name('index');
        Route::get('/dashboard', \App\Livewire\Student\Dashboard::class)->name('dashboard');
        Route::get('/subjects', action: \App\Livewire\Student\SubjectList::class)->name('subjects');
        Route::get('/subjects/{subject}/materials', \App\Livewire\Student\MaterialList::class)->name('materials.index');
        Route::get('/materials/{material}', \App\Livewire\Student\MaterialDetail::class)->name('materials.show');
        Route::get('/quizzes', \App\Livewire\Student\QuizList::class)->name('quizzes');
        Route::get('/quizzes/{quiz}/attempt', \App\Livewire\Student\QuizAttempt::class)->name('quizzes.attempt');
        Route::get('/quizzes/{attempt}/result', \App\Livewire\Student\QuizResult::class)->name('quizzes.result');
        Route::get('/ranking', \App\Livewire\Student\StudentRanking::class)->name('ranking');
        Route::get('/tasks', \App\Livewire\Student\TaskList::class)->name('tasks');
        Route::get('/games', \App\Livewire\Student\GameList::class)->name('games');
    });


    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

});



Route::get('/update_gruplist', function () {
    $token = config('services.fonnte.token');
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/fetch-group',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
})->middleware(['auth', 'role:admin']);




Route::get('/cek_idgrup', function () {

    $token = config('services.fonnte.token');

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/get-whatsapp-group',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token",
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
})->middleware(['auth', 'role:admin']);
