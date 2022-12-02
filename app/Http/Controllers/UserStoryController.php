<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\UserStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserStoryController extends Controller
{
    //

    public function userStoryDetails($Id)
    {

        $taskData = Task::select(array('tasks.*', 'users.FirstName', 'users.LastName'))
            ->where('UserStoryId', $Id)
            ->leftJoin('users', 'users.Id', '=', 'tasks.UserId')
            ->get();

        $userStory = UserStory::select('user_story.*', 'users.FirstName', 'users.LastName', 'projects.ProjectManagerId')
            ->where('user_story.Id', $Id)
            ->leftJoin('users', 'users.Id', '=', 'user_story.Created_By_Id')
            ->leftJoin('projects', 'projects.Id', '=', 'user_story.ProjectId')
            ->first();
        $data = [
            'taskData' => $taskData,
            'userStoryData' => $userStory,
            'title'       => 'User Story Details',


        ];

        return view('projects.userStoryDetails', $data);
    }
}



//  SELECT COUNT("Status") FROM tasks WHERE "Status" NOT LIKE 'Done'