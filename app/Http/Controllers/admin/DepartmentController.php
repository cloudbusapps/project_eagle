<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Department;
use App\Models\User;

class DepartmentController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Department',
            'data' => Department::select('departments.*', 'FirstName', 'LastName')
                ->leftJoin('users AS u', 'departments.UserId', 'u.Id')
                ->orderBy('Name', 'ASC')
                ->get()
        ];
        return view('departments.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Department",
            'employees' => User::where('IsAdmin', false)->where('Status', 1)->get()
        ];
        return view('departments.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'Name' => ['required', 'string', 'max:255', 'unique:departments'],
        ]);
        
        $Name = $request->Name;
        $Department = new Department;
        $Department->Name   = $request->Name;
        $Department->UserId = $request->UserId;
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
            'data'  => Department::find($Id),
            'employees' => User::where('IsAdmin', false)->where('Status', 1)->get()
        ];
        return view('departments.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'Name' => [
                'required', 'string', 'max:255',
                Rule::unique('departments')->ignore($Id, 'Id')
            ]
        ]);
        
        $Name = $request->Name;
        $Department = Department::find($Id);
        $Department->Name   = $request->Name;
        $Department->UserId = $request->UserId;
        $Department->Status = $request->Status;

        if ($request->Status == 0 && $this->isActive($Id)) {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        } else {
            if ($Department->save()) {
                return redirect()
                    ->route('department')
                    ->with('success', "<b>{$Name}</b> successfully updated!");
            } 
        }

    }

    public function delete($Id) {
        $Department = Department::find($Id);
        $Name = $Department->Name;

        if (!$this->isActive($Id) && $Department->delete()) {
            return redirect()
                ->route('department')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        }
    }

    public function isActive($Id) {
        return User::where('DepartmentId', $Id)->count() ? true : false;
    }

}
