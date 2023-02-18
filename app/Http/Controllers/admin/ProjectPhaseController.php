<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\admin\ProjectPhase;
use App\Models\admin\ProjectPhaseDetails;
use App\Models\admin\ProjectPhaseResources;
use App\Models\admin\Designation;
use App\Models\User;
use Illuminate\Support\Str;
use Auth;

class ProjectPhaseController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Project Phases',
            'data'  => ProjectPhase::all()
        ];
        return view('admin.projectPhases.index', $data);
    }

    public function form() {
        $data = [
            'title'        => "New Project Phase",
            'designations' => Designation::where('Status', 1)->get(),
        ];
        return view('admin.projectPhases.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'Title' => ['required', 'string', 'max:500'],
            'Percentage' => ['required', 'numeric', 'between:0,100']
        ]);
        
        $Title = $request->Title;
        $ProjectPhase = new ProjectPhase;
        $ProjectPhase->Title      = $request->Title;
        $ProjectPhase->Percentage = $request->Percentage;
        $ProjectPhase->Required   = isset($request->Required) && $request->Required == 'on' ? 1 : 0;
        $ProjectPhase->Status     = $request->Status;

        if ($ProjectPhase->save()) {
            $Id = $ProjectPhase->Id;

            $SubResources  = $request->SubResources;
            $SubPercentage = $request->SubPercentage;
            if ($SubResources && count($SubResources)) {
                $subData = [];
                foreach ($SubResources as $i => $dt) {
                    $subData[] = [
                        'Id'             => Str::uuid(),
                        'ProjectPhaseId' => $Id,
                        'DesignationId'  => $dt,
                        'Percentage'     => $SubPercentage[$i],
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                    ];
                }
                ProjectPhaseResources::insert($subData);
            }

            $SubDetail   = $request->SubDetail;
            $SubRequired = $request->SubRequired;
            $SubStatus   = $request->SubStatus;
            if ($SubDetail && count($SubDetail)) {
                $subData = [];
                foreach ($SubDetail as $i => $dt) {
                    $subData[] = [
                        'Id'             => Str::uuid(),
                        'ProjectPhaseId' => $Id,
                        'Title'          => $dt,
                        'Required'       => isset($SubRequired[$i]) && $SubRequired[$i] == 'on' ? 1 : 0,
                        'Status'         => $SubStatus[$i],
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                    ];
                }
                ProjectPhaseDetails::insert($subData);
            }

            return redirect()
                ->route('projectPhase')
                ->with('success', "<b>{$Title}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title'        => "Edit Project Phase",
            'data'         => ProjectPhase::find($Id),
            'details'      => ProjectPhaseDetails::where('ProjectPhaseId', $Id)->get(),
            'resources'    => ProjectPhaseResources::where('ProjectPhaseId', $Id)->get(),
            'designations' => Designation::where('Status', 1)->get(),
        ];
        return view('admin.projectPhases.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'Title' => ['required', 'string', 'max:255'],
            'Percentage' => ['required', 'numeric', 'between:0,100']
        ]);
        
        $Title = $request->Title;
        $ProjectPhase = ProjectPhase::find($Id);
        $ProjectPhase->Title      = $request->Title;
        $ProjectPhase->Percentage = $request->Percentage;
        $ProjectPhase->Required   = isset($request->Required) && $request->Required == 'on' ? 1 : 0;
        $ProjectPhase->Status     = $request->Status;

        if ($ProjectPhase->save()) {
            $delete = ProjectPhaseResources::where('ProjectPhaseId', $Id)->delete();
            $delete = ProjectPhaseDetails::where('ProjectPhaseId', $Id)->delete();

            $SubResources  = $request->SubResources;
            $SubPercentage = $request->SubPercentage;
            if ($SubResources && count($SubResources)) {
                $subData = [];
                foreach ($SubResources as $i => $dt) {
                    $subData[] = [
                        'Id'             => Str::uuid(),
                        'ProjectPhaseId' => $Id,
                        'DesignationId'  => $dt,
                        'Percentage'     => $SubPercentage[$i],
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                    ];
                }
                ProjectPhaseResources::insert($subData);
            }

            $SubDetail   = $request->SubDetail;
            $SubRequired = $request->SubRequired;
            $SubStatus   = $request->SubStatus;
            if ($SubDetail && count($SubDetail)) {
                $subData = [];
                foreach ($SubDetail as $i => $dt) {
                    $subData[] = [
                        'Id'             => Str::uuid(),
                        'ProjectPhaseId' => $Id,
                        'Title'          => $dt,
                        'Required'       => isset($SubRequired[$i]) && $SubRequired[$i] == 'on' ? 1 : 0,
                        'Status'         => $SubStatus[$i],
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                    ];
                }
                ProjectPhaseDetails::insert($subData);
            }

            return redirect()
                ->route('projectPhase')
                ->with('success', "<b>{$Title}</b> successfully updated!");
        } 
    }

    public function delete($Id) {
        $ProjectPhase = ProjectPhase::find($Id);
        $Title = $ProjectPhase->Title;

        if ($ProjectPhase->delete()) {
            $delete = ProjectPhaseResources::where('ProjectPhaseId', $Id)->delete();
            $delete = ProjectPhaseDetails::where('ProjectPhaseId', $Id)->delete();
            
            return redirect()
                ->route('projectPhase')
                ->with('success', "<b>{$Title}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Title} is currently in used!"])
                ->withInput();
        }
    }

}
