<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\DeadlineNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deadline:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Task Deadline';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // START
        $today = Carbon::now();

        // GET TASK WHERE USERID IS NOT NULL AND ENDDATE IS LESS THAN TODAY
        $tasks = Task::select(
            'users.Id as Id',
            'tasks.Title',
            'tasks.Id as taskId',
            )
        ->whereDate('tasks.EndDate', '<', $today->toDateString())
            ->where('tasks.Status', '!=', 'Done')
            ->where('tasks.UserId', '!=', null)
            ->leftJoin('users', 'users.Id', '=', 'tasks.UserId')
            ->get();


        foreach ($tasks as $index => $task) {
            Notification::send($task, new DeadlineNotification($task));
        }

        // END
    }
}
