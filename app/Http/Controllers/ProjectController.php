<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Resource;
use App\Models\UserStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    function view()
    {
        $projectData = Project::all();
        $data = [
            'title'       => 'List of Project',
            'projectData' => $projectData = Project::all()
        ];

        return view('projects.view', $data);
    }


    function viewProjectDetails($Id)
    {

        $userStory = UserStory::select('user_story.*', 'users.FirstName', 'users.LastName')
            ->where('ProjectId', $Id)
            ->leftJoin('users', 'users.Id', '=', 'user_story.UserId')
            ->get();

        $userList = Resource::select(
            'users.FirstName',
            'users.LastName',
            'users.Title',
            'users.Id',
            DB::raw('(
                SELECT SUM(
                CAST(tasks."Duration" AS INT)
                ) As durationinseconds
                FROM tasks
                LEFT JOIN user_story
                ON user_story."Id" = tasks."UserStoryId"
            WHERE users."Id" = tasks."UserId" AND 
             user_story."ProjectId" = \'' . $Id . '\'
            )'),
            DB::raw('(
                SELECT SUM(
                CAST(tasks."TimeCompleted" AS INT)
                ) As timecompleteinsec
                FROM tasks
                LEFT JOIN user_story
                ON user_story."Id" = tasks."UserStoryId"
            WHERE users."Id" = tasks."UserId" AND 
             user_story."ProjectId" = \'' . $Id . '\'
            )'),
        )
            ->where('resources.ProjectId', $Id)
            ->leftJoin('users', 'users.Id', '=', 'resources.UserId')

            ->get();

        $projectData = Project::select('projects.*', 'users.FirstName', 'users.LastName', 'users.Title')
            ->where('projects.Id', $Id)
            ->leftJoin('users', 'users.Id', '=', 'projects.ProjectManagerId')
            ->first();
        $data = [
            'projectData' => $projectData,
            'userStoryData' => $userStory,
            'userList'    => $userList,
            'title'       => 'Project Details',

        ];

        return view('projects.projectDetails', $data);
    }
    function add(Request $request)
    {

        $request->validate([
            'ProjectName' => 'required|min:3',
        ]);

        $userData = User::Where('Id', '=', session('LoggedUser'))->first();
        $projectName        = $request->ProjectName;
        $ProjectKickoffDate = $request->ProjectKickoffDate;
        $ProjectClosedDate  = $request->ProjectClosedDate;
        $ProjectDescription = $request->ProjectDescription;

        $project = new Project();
        $project->Name              = $projectName;
        $project->KickoffDate      = $ProjectKickoffDate;
        $project->ClosedDate       = $ProjectClosedDate;
        $project->Description      = $ProjectDescription;
        $save = $userData->projects()->save($project);
        if ($save) {
            return redirect()
                ->route('projects.view')
                ->with('success', "Project <b>{$projectName}</b> has been created");
        } else {
            return redirect()
                ->route('projects.view')->with('fail', 'Something went wrong, try again later');
        }
    }

    function update(Request $request, $Id)
    {
        $project = Project::Where('Id', '=',  $Id)->first();
        $projectName = $request->ProjectName;

        $project->Name             = $projectName;
        $project->Description      = $request->ProjectDescription;
        $project->KickoffDate      = $request->ProjectKickoffDate;
        $project->ClosedDate       = $request->ProjectClosedDate;
        $project->ProjectManagerId = $request->ProjectManagerId;
        $project->UpdatedById    = Auth::id();
        $update = $project->update();


        if ($update) {
            return redirect()
                ->route('projects.projectDetails', ['Id' => $Id])
                ->with('success', "User Story <b>{$projectName}</b> has been updated");
        } else {
            return redirect()
                ->route('projects.projectDetails', ['Id' => $Id])
                ->with('fail', 'Something went wrong, try again later');
        }
    }


    function delete($Id)
    {
        $projectData = Project::find($Id);
        $projectName = $projectData->Name;


        if ($projectData->delete()) {
            return redirect()
                ->route('projects.view')
                ->with('success', "Project <b>{$projectName}</b> has been deleted");
        } else {
            return redirect()
                ->route('projects.view')
                ->with('fail', 'Something went wrong, try again later');
        }
    }


    public function addProject()
    {
        $userList = User::select('FirstName', 'LastName', 'Id', 'Title', 'MiddleName')->get();
        $data = [
            'title'          => 'Add Project',
            'projectData'    => [],
            'type'           => 'insert',
            'userList'             => $userList,
        ];
        return view('projects.addProject', $data);
    }

    public function editProject($Id)
    {
        $projectData = Project::find($Id);
        $userList = Resource::select('users.FirstName', 'users.LastName', 'users.Title', 'users.Id')
            ->where('resources.ProjectId', $Id)
            ->leftJoin('users', 'users.Id', '=', 'resources.UserId')
            ->get();
        $data = [
            'title'          => 'Edit Project',
            'projectData'    => $projectData,
            'type'           => 'edit',
            'Id'             => $Id,
            'userList'             => $userList,
        ];
        return view('projects.addProject', $data);
    }

    //USER STORY
    public function addUserStory($Id)
    {
        $data = [
            'title'          => 'Add User Story',
            'projectData'    => [],
            'type'           => 'insert',
            'projectId'           => $Id
        ];
        return view('projects.userStory', $data);
    }

    public function editUserStory($Id)
    {
        $userStoryData = UserStory::find($Id);
        $userList = User::all();
        $data = [
            'title'          => 'Edit User Story',
            'userStoryData'    => $userStoryData,
            'type'           => 'edit',
            'Id'             => $Id,
            'userList'       => $userList,
            'projectId'           => $userStoryData->ProjectId
        ];
        return view('projects.userStory', $data);
    }

    public function saveUserStory(Request $request, $Id)
    {

        $request->validate([
            'UserStoryTitle' => 'required|min:3',
        ]);

        $sessionId = Auth::id();
        $UserStoryTitle        = $request->UserStoryTitle;
        $UserStoryStartDate = $request->UserStoryStartDate;
        $UserStoryEndDate  = $request->UserStoryEndDate;
        $UserStoryActualStartDate = $request->UserStoryActualStartDate;
        $UserStoryActualEndDate  = $request->UserStoryActualEndDate;
        $UserStoryDescription = $request->UserStoryDescription;

        $userStory = new UserStory();
        $userStory->Title              = $UserStoryTitle;
        $userStory->StartDate      = $UserStoryStartDate;
        $userStory->EndDate       = $UserStoryEndDate;
        $userStory->ActualStartDate      = $UserStoryActualStartDate;
        $userStory->ActualEndDate       = $UserStoryActualEndDate;
        $userStory->Description      = $UserStoryDescription;
        $userStory->Admin_Id      = $sessionId;


        $userStory->CreatedById      = $sessionId;
        $userStory->ProjectId      = $Id;


        if ($userStory->save()) {
            return redirect()
                ->route('projects.projectDetails', ['Id' => $Id])
                ->with('success', "User Story <b>{$UserStoryTitle}</b> has been created");
        } else {
            return redirect()
                ->route('projects.view')->with('fail', 'Something went wrong, try again later');
        }
    }
    function updateUserStory(Request $request, $Id)
    {
        $userStory = UserStory::Where('Id', '=',  $Id)->first();
        $UserStoryTitle = $request->UserStoryTitle;

        $userStory->Title           = $UserStoryTitle;
        $userStory->Description     = $request->UserStoryDescription;
        $userStory->StartDate       = $request->UserStoryStartDate;
        $userStory->EndDate         = $request->UserStoryEndDate;
        $userStory->ActualStartDate = $request->UserStoryActualStartDate;
        $userStory->ActualEndDate  = $request->UserStoryActualEndDate;
        $userStory->UpdatedById   = Auth::id();
        $update = $userStory->update();


        if ($update) {
            return redirect()
                ->route('projects.userStoryDetails', ['Id' => $Id])
                ->with('success', "User Story <b>{$UserStoryTitle}</b> has been updated");
        } else {
            return redirect()
                ->route('projects.userStoryDetails', ['Id' => $Id])
                ->with('fail', 'Something went wrong, try again later');
        }
    }

    function deleteUserStory($Id)
    {
        $userStoryData = UserStory::find($Id);
        $Title = $userStoryData->Title;
        $ProjectId = $userStoryData->ProjectId;


        if ($userStoryData->delete()) {
            return redirect()
                ->route('projects.projectDetails', ['Id' => $ProjectId])
                ->with('success', "User Story <b>{$Title}</b> has been deleted");
        } else {
            return redirect()
                ->route('projects.projectDetails', ['Id' => $ProjectId])
                ->with('fail', 'Something went wrong, try again later');
        }
    }


    // RESOURCE

    public function addResource($Id)
    {

        $userList = User::where('IsAdmin',false)
        ->get();
        $savedUser = Resource::select('users.FirstName', 'users.LastName', 'users.Title', 'users.Id')
            ->where('resources.ProjectId', $Id)
            ->leftJoin('users', 'users.Id', '=', 'resources.UserId')
            ->get();
        $data = [
            'title'          => 'Add Resource',
            'type'           => 'insert',
            'projectId'           => $Id,
            'projectData'        => [],
            'userList' => $userList,
            'savedUser' => $savedUser
        ];
        return view('projects.resource', $data);
    }
    public function editResource($Id)
    {

        $userList = User::all();
        $savedUser = Resource::select('users.FirstName', 'users.LastName', 'users.Title', 'users.Id')
            ->where('resources.ProjectId', $Id)
            ->leftJoin('users', 'users.Id', '=', 'resources.UserId')
            ->get();
        $data = [
            'title'          => 'Edit Resource',
            'type'           => 'edit',
            'projectId'           => $Id,
            'projectData'        => [],
            'userList' => $userList,
            'savedUser' => $savedUser
        ];
        return view('projects.resource', $data);
    }




    public function saveResource(Request $request, $Id)
    {

        $users = $request->usersId;
        $deleteUsers = Resource::where('ProjectId', $Id)->delete();


        if ($users && count($users)) {
            $data = [];

            foreach ($users as $user) {
                $data[] = [
                    'Id'            => Str::uuid(),
                    'ProjectId'        => $Id,
                    'UserId'        => $user,
                ];
            }

            $saveUser = Resource::insert($data);
            if ($saveUser) {
                $request->session()->flash('success', 'Users updated');
                return response()->json(['url' => url('projects/projectDetails/' . $Id)]);
            }
        }
        if ($deleteUsers) {
            $request->session()->flash('success', 'Users updated');
            return response()->json(['url' => url('projects/projectDetails/' . $Id)]);
        } else {
            return redirect()
                ->route('projects.view')->with('fail', 'Something went wrong, try again later');
        }
    }

    public function updateResource(Request $request, $Id)
    {

        $usersId = $request->usersId;
        $deleteUsers = Resource::where('ProjectId', $Id)->delete();

        $sessionId = Auth::id();
        if ($usersId && count($usersId)) {
            $data = [];

            foreach ($usersId as $user) {
                $data[] = [
                    'Id'            => Str::uuid(),
                    'ProjectId'        => $Id,
                    'UserId'        => $user,
                ];
            }

            $saveUser = Resource::insert($data);
        }
        if ($saveUser && $deleteUsers) {
            return redirect()
                ->route('projects.projectDetails', ['Id' => $Id])
                ->with('success', "Users has been updated in this project");
        } else {
            return redirect()
                ->route('projects.view')->with('fail', 'Something went wrong, try again later');
        }
    }








    // UNUSED

    public function getProjectForm($Id = "add")
    {
        $userList = User::all();
        $method = 'POST';
        $action = '/projects/add';
        $button = '<button type="submit" class="btn btn-primary">Submit</button>';
        $option = "";

        foreach ($userList as $index => $users) {
            $option .= '<option value="' . $users->Id . '">' . $users->FirstName . ' ' . $users->LastName . '</option>';
        }

        return '
                    <form class="row g-3" action="' . $action . '" method="POST">
                    ' . csrf_field() . '
                    ' . method_field($method) . '
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="ProjectName" id="ProjectName" placeholder="Name">
                                <label for="floatingName">Project Name <code>*</code></label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="ProjectDescription" placeholder="Description" id="ProjectDescription" style="height: 82px;"></textarea>
                                <label for="floatingTextarea">Project Description <code>*</code></label>
                            </div>
                        </div>
                       

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input required placeholder="Kickoff Date" name="ProjectKickoffDate" id="ProjectKickoffDate"
                                    type="date" class="form-control">
                                <label for="floatingEmail">Kickoff Date <code>*</code></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input required placeholder="Closed Date" name="ProjectClosedDate" id="ProjectClosedDate"
                                    type="date" class="form-control">
                                <label for="floatingEmail">Closed Date <code>*</code></label>
                            </div>
                        </div>
                        <div class="modal-footer pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-warning">Reset</button>
                        ' . $button . '
                    </div>
                       
                    </div>
                        <div class="text-end">
                     

                    </form>
        ';
    }

    // FIX LATER

    public function confirmMessage($type = "add")
    {

        $modalTitle = "Add Project";
        $modalBody = "Add this project?";
        $buttons = "
        <a class='btn btn-secondary' data-bs-dismiss='modal'>Close</a>
        <button type='submit' class='btn btn-primary'>Save</button>";

        if ($type === 'update') {
            $modalTitle = "Update project";
            $modalBody = "Update this project?";
            $buttons = '<a class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
        <button type="submit" class="btn btn-primary">Save</a>';
        }
        if ($type === 'delete') {
            $modalTitle = "Delete project";
            $modalBody = "Delete this project?";
            $buttons = '<a class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
            <button type="submit" class="btn btn-primary">Save</a>';
        }

        return '
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">' . $modalTitle . '</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
               ' . $modalBody . '
            </div>
            <div class="modal-footer">
               ' . $buttons . '
            </div>
        </div>
    </div>
        
        ';
    }
}
