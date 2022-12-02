<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\admin\Designation;

use App\Models\admin\Department;
use App\Models\User;

class DesignationController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Designation',
            'data' => Designation::select('designations.*', 'd.Name AS DepartmentName')
                ->leftJoin('departments AS d', 'd.Id', 'designations.DepartmentId')
                ->orderBy('Name', 'ASC')
                ->get()
        ];
        return view('admin.designations.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Designation",
            'departments' => Department::where('Status', 1)->get()
        ];
        return view('admin.designations.form', $data);
    }

    public function save(Request $request) {
        $DepartmentId = $request->DepartmentId;
        $Name         = $request->Name;
        $Status       = $request->Status;

        $validator = $request->validate([
            'DepartmentId' => ['required'],
            'Name' => [
                'required', 'string', 'max:255',
                Rule::unique('designations')->where(function($query) use($Name, $DepartmentId) {
                    return $query->where('Name', $Name)->where('DepartmentId', $DepartmentId);
                }),
            ],
        ]);
        
        $Designation = new Designation;
        $Designation->DepartmentId = $DepartmentId;
        $Designation->Name         = $Name;
        $Designation->Status       = $Status;

        if ($Designation->save()) {
            return redirect()
                ->route('designation')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Designation",
            'departments' => Department::where('Status', 1)->get(),
            'data'  => Designation::find($Id)
        ];
        return view('admin.designations.form', $data);
    }

    public function update(Request $request, $Id) {
        $DepartmentId = $request->DepartmentId;
        $Name         = $request->Name;
        $Status       = $request->Status;

        $validator = $request->validate([
            'DepartmentId' => ['required'],
            'Name' => [
                'required', 'string', 'max:255',
                Rule::unique('designations')->where(function($query) use($Name, $DepartmentId, $Id) {
                    return $query->where('Name', $Name)->where('DepartmentId', $DepartmentId)->where('Id', '<>', $Id);
                }),
            ],
        ]);
        
        $Designation = Designation::find($Id);
        $Designation->DepartmentId = $DepartmentId;
        $Designation->Name         = $Name;
        $Designation->Status       = $Status;

        if ($Status == 0 && $this->isActive($Id)) {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        } else {
            if ($Designation->save()) {
                return redirect()
                    ->route('designation')
                    ->with('success', "<b>{$Name}</b> successfully updated!");
            } 
        }

    }

    public function delete($Id) {
        $Designation = Designation::find($Id);
        $Name = $Designation->Name;

        if (!$this->isActive($Id) && $Designation->delete()) {
            return redirect()
                ->route('designation')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        }
    }

    public function isActive($Id) {
        return User::where('DesignationId', $Id)->count() ? true : false;
    }

}
