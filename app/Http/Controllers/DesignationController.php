<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Designation;

use App\Models\Department;

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
        return view('designations.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Designation",
            'departments' => Department::where('Status', 'Active')->get()
        ];
        return view('designations.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'DepartmentId' => ['required'],
            'Name'         => ['required', 'string', 'max:255'],
        ]);
        
        $Name = $request->Name;
        $Designation = new Designation;
        $Designation->DepartmentId = $request->DepartmentId;
        $Designation->Name         = $request->Name;
        $Designation->Status       = $request->Status;

        if ($Designation->save()) {
            return redirect()
                ->route('designation')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Designation",
            'data'  => Designation::find($Id)
        ];
        return view('designations.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'DepartmentId' => ['required'],
            'Name'         => ['required', 'string', 'max:255'],
        ]);
        
        $Name = $request->Name;
        $Designation = Designation::find($Id);
        $Designation->DepartmentId = $request->DepartmentId;
        $Designation->Name         = $request->Name;
        $Designation->Status       = $request->Status;

        if ($Designation->save()) {
            return redirect()
                ->route('designation')
                ->with('success', "<b>{$Name}</b> successfully updated!");
        } 
    }

    public function delete($Id) {
        $Designation = Designation::find($Id);
        $Name = $Designation->Name;

        if ($Designation->delete()) {
            return redirect()
                ->route('designation')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('designation')
                ->with('fail', "<b>{$Name}</b> failed to delete!");
        }
    }

}
