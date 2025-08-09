<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use App\Notifications\NotificationStudent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendQuizReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat ke siswa untuk kuis yang mendekati tenggat waktu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa kuis dengan tenggat waktu yang mendekat...');

        // Ambil kuis yang akan berakhir dalam 24 jam dari sekarang
        $quizzes = Quiz::where('status', 'publish')
            ->where('end_time', '>', now())
            ->where('end_time', '<=', now()->addHours(24))
            ->get();

        if ($quizzes->isEmpty()) {
            $this->info('Tidak ada kuis yang mendekati tenggat waktu.');
            return;
        }

        foreach ($quizzes as $quiz) {
            // Dapatkan semua siswa yang terdaftar di kelas target kuis
            $students = $quiz->targetClass->users()->whereHas('roles', function ($query) {
                $query->where('name', 'siswa');
            })->get();

            if ($students->isNotEmpty()) {
                // Kirim notifikasi ke siswa
                Notification::send($students, new NotificationStudent($quiz, 'quiz_reminder'));
                $this->line("Pengingat terkirim untuk kuis '{$quiz->title}' ke {$students->count()} siswa.");
            }
        }

        $this->info("Berhasil mengirim pengingat untuk {$quizzes->count()} kuis.");
        Log::info("Pengirim Pengingat Kuis: Berhasil mengirim pengingat untuk {$quizzes->count()} kuis.");
    }
}