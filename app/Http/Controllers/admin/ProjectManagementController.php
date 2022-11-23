<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\admin\ResourceCost;
use App\Models\admin\ProjectCost;

class ProjectManagementController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Project Management',
            'projectCost' => Project::select('projects.*', 'u.FirstName', 'u.LastName', 'pc.Budget', 'pc.Id AS ProjectCostId')
                ->leftJoin('users AS u', 'u.Id', 'projects.ProjectManagerId')
                ->leftJoin('project_costs AS pc', 'projects.Id', 'pc.ProjectId')
                ->get(),
            'resourceCost' => User::select('users.Id', 'm.Id AS ResourceCostId', 'Level', 'BasicSalary', 'DailyRate', 'HourlyRate', 'FirstName', 'LastName')
                ->leftJoin('resource_costs AS m', 'users.Id', 'm.UserId')
                ->get()
        ];

        return view('admin.projectManagement.index', $data);
    }


    // ----- PROJECT -----
    public function edit($Id) {
        $data = [
            'title' => 'Edit Project',
            'data' => Project::select('projects.*', 'u.FirstName', 'u.LastName', 'pc.Budget', 'pc.Id AS ProjectCostId')
                ->leftJoin('users AS u', 'u.Id', 'projects.ProjectManagerId')
                ->leftJoin('project_costs AS pc', 'projects.Id', 'pc.ProjectId')
                ->where('projects.Id', $Id)
                ->first(),
        ];
        return view('admin.projectManagement.formProject', $data);
    }

    public function update(Request $request, $Id, $ProjectCostId = null) {
        $validator = $request->validate([
            'Budget' => ['required', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
        ]);

        if ($ProjectCostId) {
            $ProjectCost = ProjectCost::findOrFail($ProjectCostId);
        } else {
            $ProjectCost = new ProjectCost;
        }

        $ProjectCost->ProjectId = $Id;
        $ProjectCost->Budget    = $request->Budget;

        if ($ProjectCost->save()) {
            $ProjectName = Project::find($Id)->first()->Name;
            return redirect()->route('projectManagement')
                ->with('tab', 'Project')
                ->with('success', "<b>{$ProjectName}</b> successfully updated!");
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }
    // ----- END PROJECT -----


    // ----- RESOURCE COST -----
    public function resourceCostEdit($Id) {
        $data = [
            'title' => 'Edit Resource Cost',
            'data'  => User::where('users.Id', $Id)
                ->select('users.Id', 'm.Id AS ResourceCostId', 'Level', 'BasicSalary', 'DailyRate', 'HourlyRate', 'FirstName', 'LastName')
                ->leftJoin('resource_costs AS m', 'users.Id', 'm.UserId')
                ->first()
        ];

        return view('admin.projectManagement.formResourceCost', $data);
    }

    public function resourceCostUpdate(Request $request, $Id, $ResourceCostId = null) {
        $validator = $request->validate([
            'Level'       => ['required'],
            'BasicSalary' => ['required', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
        ]);

        $BasicSalary = $request->BasicSalary;
        $DailyRate   = $BasicSalary / 22;
        $HourlyRate  = $DailyRate / 8;

        if ($ResourceCostId) {
            $ResourceCost = ResourceCost::findOrFail($ResourceCostId);
        } else {
            $ResourceCost = new ResourceCost;
        }

        $ResourceCost->UserId      = $Id;
        $ResourceCost->Level       = $request->Level;
        $ResourceCost->BasicSalary = $BasicSalary;
        $ResourceCost->DailyRate   = $DailyRate;
        $ResourceCost->HourlyRate  = $HourlyRate;

        if ($ResourceCost->save()) {
            $FullName = User::find($Id)->full_name;
            return redirect()->route('projectManagement')
                ->with('tab', 'Resource Cost')
                ->with('success', "<b>{$FullName}</b> successfully updated!");
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }
    // ----- END RESOURCE COST -----
}
