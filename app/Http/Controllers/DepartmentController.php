<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Department',
            'data' => Department::orderBy('Name', 'ASC')->get()
        ];
        return view('departments.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Department",
        ];
        return view('departments.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'Name' => ['required', 'string', 'max:255'],
        ]);
        
        $Name = $request->Name;
        $Department = new Department;
        $Department->Name   = $request->Name;
        $Department->Status = $request->Status;

        if ($Department->save()) {
            return redirect()
                ->route('department')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Department",
            'data'  => Department::find($Id)
        ];
        return view('departments.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'Name' => ['required', 'string', 'max:255'],
        ]);
        
        $Name = $request->Name;
        $Department = Department::find($Id);
        $Department->Name   = $request->Name;
        $Department->Status = $request->Status;

        if ($Department->save()) {
            return redirect()
                ->route('department')
                ->with('success', "<b>{$Name}</b> successfully updated!");
        } 
    }

    public function delete($Id) {
        $Department = Department::find($Id);
        $Name = $Department->Name;

        if ($Department->delete()) {
            return redirect()
                ->route('department')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('department')
                ->with('fail', "<b>{$Name}</b> failed to delete!");
        }
    }

}
