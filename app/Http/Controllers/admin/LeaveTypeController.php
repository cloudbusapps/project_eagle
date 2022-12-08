<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\admin\LeaveType;
use App\Models\UserLeaveBalance;

class LeaveTypeController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Leave Type',
            'data' => LeaveType::orderBy('Name', 'ASC')->get()
        ];
        return view('admin.leaveTypes.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Leave Type",
        ];
        return view('admin.leaveTypes.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'Name' => ['required', 'string', 'max:255', 'unique:leave_types'],
        ]);
        
        $Name = $request->Name;
        $LeaveType = new LeaveType;
        $LeaveType->Name   = $request->Name;
        $LeaveType->Status = $request->Status;

        if ($LeaveType->save()) {
            return redirect()
                ->route('leaveType')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Leave Type",
            'data'  => LeaveType::find($Id)
        ];
        return view('admin.leaveTypes.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'Name' => [
                'required', 'string', 'max:255',
                Rule::unique('leave_types')->ignore($Id, 'Id')
            ],
        ]);
        
        $Name = $request->Name;
        $LeaveType = LeaveType::find($Id);
        $LeaveType->Name   = $request->Name;
        $LeaveType->Status = $request->Status;

        if ($request->Status == 0 && $this->isActive($Id)) {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        } else {
            if ($LeaveType->save()) {
                return redirect()
                    ->route('department')
                    ->with('success', "<b>{$Name}</b> successfully updated!");
            } 
        }
    }

    public function delete($Id) {
        $LeaveType = LeaveType::find($Id);
        $Name = $LeaveType->Name;

        if (!$this->isActive($Id) && $LeaveType->delete()) {
            return redirect()
                ->route('leaveType')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        }
    }

    public function isActive($Id) {
        return UserLeaveBalance::where('LeaveTypeId', $Id)
            ->where('Balance', '>', 0)
            ->where('Accumulated', '>', 0)
            ->count() ? true : false;
    }

}
