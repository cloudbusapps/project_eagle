<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\UserStory;
use App\Models\Resource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{
    function view()
    {
        if (session()->has('LoggedUser')) {
            //
            return view('projects');
        } else {
            return view('/auth/login');
        }
    }

    function addTask($Id)
    {

        // $userList = User::all();
        $userList = Resource::select('users.FirstName', 'users.LastName', 'users.Title', 'users.Id')

            ->leftJoin('users', 'users.Id', '=', 'resources.UserId')
            ->leftJoin('projects', 'projects.Id', '=', 'resources.ProjectId')
            ->leftJoin('user_story', 'user_story.ProjectId', '=', 'resources.ProjectId')
            ->where('user_story.Id', $Id)
            ->get();

        $data = [
            'title'          => 'Add Task',
            'userStoryData'    => [],
            'type'           => 'insert',
            'UserStoryId'           => $Id,
            'userList'    => $userList,
            'taskData'    => [],
        ];

        return view('projects.addTask', $data);
    }
    function editTask($Id)
    {

        $taskData = Task::Where('Id', '=',  $Id)->first();

        $userList = Resource::select('users.FirstName', 'users.LastName', 'users.Title', 'users.Id')

            ->leftJoin('users', 'users.Id', '=', 'resources.UserId')
            ->leftJoin('projects', 'projects.Id', '=', 'resources.ProjectId')
            ->leftJoin('user_story', 'user_story.ProjectId', '=', 'resources.ProjectId')
            ->where('user_story.Id', $taskData->UserStoryId)
            ->get();

        $data = [
            'taskData'    => $taskData,

            'userList'    => $userList,
            'title'       => 'Edit Task',
            'type'           => 'edit',
            'Id'             => $Id,
            'UserStoryId' => $taskData->UserStoryId

        ];

        return view('projects.addTask', $data);
    }



    function saveTask(Request $request, $Id)
    {
        $userData = User::Where('Id', '=', session('LoggedUser'))->first();

        $durationInSeconds = (string) $request->TaskDuration * 60 * 60;

        $taskName         = $request->TaskName;
        $taskDescription  = $request->TaskDescription;
        $taskStartDate    = $request->TaskStartDate;
        $taskEndDate      = $request->TaskEndDate;
        $actualTaskStartDate    = $request->ActualTaskStartDate;
        $actualTaskEndDate      = $request->ActualTaskEndDate;
        $taskDuration      = $durationInSeconds;

        $taskUserAssigned = $request->TaskUserAssigned;
        $taskStatus       = $request->TaskStatus;

        $task = new Task();

        $task->Admin_Id    = $userData->Id;
        $task->Title       = $taskName;
        $task->Description = $taskDescription;
        $task->StartDate   = $taskStartDate;
        $task->EndDate     = $taskEndDate;
        $task->ActualStartDate   = $actualTaskStartDate;
        $task->ActualEndDate     = $actualTaskEndDate;
        $task->Duration     = $taskDuration;

        $task->UserId      = $taskUserAssigned;
        $task->Status      = $taskStatus;
        $task->UserStoryId   = $Id;

        // if (!empty($actualTaskStartDate)) {
        //     $interval = Carbon::parse($actualTaskEndDate)->diffInSeconds(Carbon::parse($actualTaskStartDate));
        //     $task->TimeCompleted = $interval;
        // }
        $timeCompleted = null;
        if (!empty($request->ActualTaskDuration)) {
            $timeCompletedInSeconds = (string) $request->ActualTaskDuration * 60 * 60;
            $timeCompleted = $timeCompletedInSeconds;
        }
        $task->TimeCompleted = $timeCompleted;


        $save = $userData->tasks()->save($task);

        if ($save) {
            // TRY TO  THINK OF A BETTER WAY TO UPDATE PERCENTCOMPLETE IN user_story TABLE
            $taskTotal = Task::select(DB::raw('COUNT("Status") as TotalTask'))
                ->where('UserStoryId', $Id)
                ->first();
            $taskDone = Task::select(DB::raw('COUNT("Status") as TotalDone'))
                ->where('UserStoryId', $Id)
                ->where('Status', 'Done')
                ->first();
            $percentComplete = 0;
            if ($taskTotal->totaltask != 0) {
                $percentComplete = round(($taskDone->totaldone / $taskTotal->totaltask) * 100);
            }

            $userStory = UserStory::Where('Id', '=',  $Id)->first();

            // COMPARE IF CURRENT START AND END DATE IS NEWER
            if ($taskStartDate < $userStory->StartDate) {
                $userStory->StartDate = $taskStartDate;
            }
            if ($taskEndDate > $userStory->StartDate) {
                $userStory->EndDate = $taskEndDate;
            }
            if ($actualTaskStartDate < $userStory->ActualStartDate || $userStory->ActualStartDate == null) {
                $userStory->ActualStartDate = $actualTaskStartDate;
            }
            if ($actualTaskEndDate > $userStory->ActualEndDate || $userStory->ActualEndDate == null) {
                $userStory->ActualEndDate = $actualTaskEndDate;
            }
            $userStory->PercentComplete   = $percentComplete;
            $update = $userStory->update();

            if ($update) {
                return redirect()
                    ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                    ->with('success', 'New Task has been added');
            } else {
                return redirect()
                    ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                    ->with('fail', 'Something went wrong, try again later');
            }
        } else {
            return redirect()
                ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                ->with('fail', 'Something went wrong, try again later');
        }
    }
    function updateTask(Request $request, $Id)
    {
        $task = Task::Where('Id', '=',  $Id)->first();
        $durationInSeconds = (string) $request->TaskDuration * 60 * 60;
        $taskName         = $request->TaskName;
        $taskDescription  = $request->TaskDescription;
        $taskStartDate    = $request->TaskStartDate;
        $taskEndDate      = $request->TaskEndDate;
        $actualTaskStartDate    = $request->ActualTaskStartDate;
        $actualTaskEndDate      = $request->ActualTaskEndDate;
        $taskDuration      = $durationInSeconds;
        $taskUserAssigned = $request->TaskUserAssigned;
        $taskStatus       = $request->TaskStatus;

        $task->Title       = $taskName;
        $task->Description = $taskDescription;
        $task->StartDate   = $taskStartDate;
        $task->EndDate     = $taskEndDate;
        $task->ActualStartDate   = $actualTaskStartDate;
        $task->ActualEndDate     = $actualTaskEndDate;
        $task->Duration     = $taskDuration;
        $task->UserId      = $taskUserAssigned;
        $task->Status      = $taskStatus;


        // if (!empty($actualTaskStartDate)) {
        //     $interval = Carbon::parse($actualTaskEndDate)->diffInSeconds(Carbon::parse($actualTaskStartDate));
        //     $task->TimeCompleted = $interval;
        // }
        $timeCompleted = null;
        if (!empty($request->ActualTaskDuration)) {
            $timeCompletedInSeconds = (string) $request->ActualTaskDuration * 60 * 60;
            $timeCompleted = $timeCompletedInSeconds;
        }
        $task->TimeCompleted = $timeCompleted;

        $save = $task->update();

        if ($save) {
            $userStoryId = $task->UserStoryId;
            // TRY TO  THINK OF A BETTER WAY TO UPDATE PERCENTCOMPLETE IN user_story TABLE
            $taskTotal = Task::select(DB::raw('COUNT("Status") as TotalTask'))
                ->where('UserStoryId', $userStoryId)
                ->first();
            $taskDone = Task::select(DB::raw('COUNT("Status") as TotalDone'))
                ->where('UserStoryId', $userStoryId)
                ->where('Status', 'Done')
                ->first();
            $percentComplete = 0;
            if ($taskTotal->totaltask != 0) {
                $percentComplete = round(($taskDone->totaldone / $taskTotal->totaltask) * 100);
            }

            $userStory = UserStory::Where('Id', '=',  $userStoryId)->first();
            $userStory->PercentComplete   = $percentComplete;
            if ($taskStartDate < $userStory->StartDate) {
                $userStory->StartDate = $taskStartDate;
            }
            if ($taskEndDate > $userStory->StartDate) {
                $userStory->EndDate = $taskEndDate;
            }
            if ($actualTaskStartDate < $userStory->ActualStartDate || $userStory->ActualStartDate == null) {
                $userStory->ActualStartDate = $actualTaskStartDate;
            }
            if ($actualTaskEndDate > $userStory->ActualEndDate || $userStory->ActualEndDate == null) {
                $userStory->ActualEndDate = $actualTaskEndDate;
            }
            $update = $userStory->update();

            if ($update) {
                return redirect()
                    ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                    ->with('success', 'Task has been updated');
            } else {
                return redirect()
                    ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                    ->with('fail', 'Something went wrong, try again later');
            }
        } else {
            return redirect()
                ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                ->with('fail', 'Something went wrong, try again later');
        }
    }

    function deleteTask($Id)
    {
        $task = Task::Where('Id', '=',  $Id)->first();
        $userStoryId = $task->UserStoryId;
        $delete = Task::where('Id', $Id)->delete();


        if ($delete) {
            $taskTotal = Task::select(DB::raw('COUNT("Status") as TotalTask'))
                ->where('UserStoryId', $userStoryId)
                ->first();
            $taskDone = Task::select(DB::raw('COUNT("Status") as TotalDone'))
                ->where('UserStoryId', $userStoryId)
                ->where('Status', 'Done')
                ->first();
            $userStory = UserStory::Where('Id', '=',  $userStoryId)->first();
            $percentComplete = 0;
            if ($taskTotal->totaltask != 0) {
                $percentComplete = round(($taskDone->totaldone / $taskTotal->totaltask) * 100);
            }
            $userStory->PercentComplete   = $percentComplete;
            $update = $userStory->update();

            if ($update) {
                return redirect()
                    ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                    ->with('success', 'Task has been deleted');
            } else {
                return redirect()
                    ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                    ->with('fail', 'Something went wrong, try again later');
            }
        } else {
            return redirect()
                ->route('projects.userStoryDetails', ['Id' => $task->UserStoryId])
                ->with('fail', 'Something went wrong, try again later');
        }
    }
}
