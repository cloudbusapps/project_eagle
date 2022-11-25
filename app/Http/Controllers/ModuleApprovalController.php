<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\ModuleApproval;
use App\Models\User;
use App\Models\Designation;

class ModuleApprovalController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Module Approval',
            'modules' => Module::where('Status', 'Active')
                ->where('WithApproval', true)
                ->get()
        ];
        return view('moduleApprovals.index', $data);
    }

    public function edit($id) {
        $data = [
            'title' => "Edit Module Approval",
            'data' => Module::find($id),
            'employees' => User::select('users.*', 'd.Name AS designation')
                ->leftJoin('designations AS d', 'DesignationId', 'd.Id')
                ->where('IsAdmin', false)->get(),
            'designations' => Designation::where('Status', 'Active')->get()
        ];
        return view('moduleApprovals.form', $data);
    }

    public function editDesignation($id, $designationId) {
        $data = ModuleApproval::where('ModuleId', $id)->where('DesignationId', $designationId)->first();
        if (!empty($data)) {
            $approverList = [];
            $Approver = $data->Approver ? json_decode($data->Approver) : null;
            if ($Approver && count($Approver)) {
                foreach ($Approver as $index => $dt) {
                    $approverList[] = [
                        'Level'    => $dt->Level,
                        'UserId'   => $dt->UserId,
                        'FullName' => User::find($dt->UserId)->full_name
                    ];
                }
            }
            $data['Approver'] = $approverList;
        }

        return response()->json($data, 200);
    }

    public function saveDesignation(Request $request, $id, $designationId) {
        $delete = ModuleApproval::where('ModuleId', $id)->where('DesignationId', $designationId)->delete();

        $approvers = [];
        if (!empty($request->Approver)) {
            foreach ($request->Approver as $index => $approver) {
                $approvers[] = [
                    'Level'  => $index+1,
                    'UserId' => $approver,
                ];
            }
        }

        $ModuleApproval = new ModuleApproval;
        $ModuleApproval->ModuleId      = $id;
        $ModuleApproval->DesignationId = $designationId;
        $ModuleApproval->Approver      = json_encode($approvers);
        if ($ModuleApproval->save()) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    }



    public function update(Request $request, $id) {
        $validator = $request->validate([
            'Title'     => ['required', 'string', 'max:255'],
            'SortOrder' => ['required', 'integer'],
            'Prefix'    => ['required', 'string'],
            'Icon'      => ['mimes:png,jpeg,jpg,svg,ico', 'max:2048'],
        ]);

        $destinationPath = 'uploads/icons';
        $Icon  = $request->file('Icon');
        $Title = $request->Title;
        $WithApproval = $request->WithApproval == 'on' ? true : false;

        $IconStore = $request->IconStore;
        $filename = $IconStore ?? "default.png";
        if ($Icon) {
            $filenameArr = explode('.', $Icon->getClientOriginalName());
            $extension   = array_splice($filenameArr, count($filenameArr)-1, 1);
            $filename    = $Title.time().'.'.$extension[0];

            $Icon->move($destinationPath, $filename);
        }
        
        $ModuleApproval = ModuleApproval::find($id);
        $ModuleApproval->ParentId  = $request->ParentId;
        $ModuleApproval->Title     = $request->Title;
        $ModuleApproval->WithApproval = $WithApproval;
        $ModuleApproval->SortOrder = $request->SortOrder;
        $ModuleApproval->Icon      = $filename;
        $ModuleApproval->Status    = $request->Status;
        $ModuleApproval->RouteName = $request->RouteName;
        $ModuleApproval->Prefix    = $request->Prefix;

        if ($ModuleApproval->save()) {
            return redirect()
                ->route('modules')
                ->with('success', "<b>{$Title}</b> successfully updated!");
        } 
    }

    public function delete($id) {
        $ModuleApproval = ModuleApproval::find($id);
        $Title = $ModuleApproval->Title;

        if ($ModuleApproval->delete()) {
            return redirect()
                ->route('modules')
                ->with('success', "<b>{$Title}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('modules')
                ->with('fail', "<b>{$Title}</b> failed to delete!");
        }
    }

}
