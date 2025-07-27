<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateTaskStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of tasks that have passed their due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks...');

        $overdueTasks = Task::where('status', 'published')
            ->where('due_date', '<', now())
            ->get();

        if ($overdueTasks->isEmpty()) {
            $this->info('No overdue tasks found.');
            return;
        }

        foreach ($overdueTasks as $task) {
            $task->update(['status' => 'closed']);
            $this->line("Task '{$task->title}' has been closed.");
        }

        $this->info("Successfully updated {$overdueTasks->count()} tasks.");
        Log::info("Task Status Updater: Successfully updated {$overdueTasks->count()} tasks.");
    }
}