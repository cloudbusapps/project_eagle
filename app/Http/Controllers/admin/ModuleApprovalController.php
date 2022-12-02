<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\admin\Module;
use App\Models\admin\ModuleApproval;
use App\Models\User;
use App\Models\admin\Designation;
use Auth;

class ModuleApprovalController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Approval',
            'modules' => Module::where('Status', 1)
                ->where('WithApproval', true)
                ->get(),
            'employees' => User::select('users.*', 'd.Name AS designation')
                ->leftJoin('designations AS d', 'DesignationId', 'd.Id')
                ->where('IsAdmin', false)->get(),
            'designations' => Designation::where('Status', 1)->get()
        ];
        return view('moduleApprovals.index', $data);
    }

    public function edit($id) {
        $data = [
            'title' => "Edit Approval",
            'data' => Module::find($id),
            'employees' => User::select('users.*', 'd.Name AS designation')
                ->leftJoin('designations AS d', 'DesignationId', 'd.Id')
                ->where('IsAdmin', false)->get(),
            'designations' => Designation::where('Status', 1)->get()
        ];
        return view('moduleApprovals.form', $data);
    }

    public function editDesignation($id, $designationId) {
        $data = ModuleApproval::select('module_approvals.*', 'u.FirstName', 'u.LastName')
            ->leftJoin('users AS u', 'u.Id', 'ApproverId')
            ->where('ModuleId', $id)
            ->where('module_approvals.DesignationId', $designationId)
            ->orderBy('Level', 'ASC')
            ->get();

        return response()->json($data, 200);
    }

    public function saveDesignation(Request $request, $id, $designationId) {
        $delete = ModuleApproval::where('ModuleId', $id)->where('DesignationId', $designationId)->delete();

        $data = [];
        if (!empty($request->Approver)) {
            foreach ($request->Approver as $index => $approver) {
                $data[] = [
                    'ModuleId'      => $id,
                    'DesignationId' => $designationId,
                    'Level'         => $index+1,
                    'ApproverId'    => $approver,
                    'CreatedById' => Auth::id(),
                    'UpdatedById' => Auth::id(),
                ];
            }
        }

        $ModuleApproval = ModuleApproval::insert($data);
        if ($ModuleApproval) {
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
