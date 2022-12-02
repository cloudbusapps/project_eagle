<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\admin\Permission;
use App\Models\User;

class PermissionController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Permission',
            'data' => Permission::select('permissions.*', 'FirstName', 'LastName')
                ->leftJoin('users AS u', 'permissions.UserId', 'u.Id')
                ->orderBy('Name', 'ASC')
                ->get()
        ];
        return view('permissions.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Permission",
            'employees' => User::where('IsAdmin', false)->where('Status', 1)->get()
        ];
        return view('permissions.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'Name' => ['required', 'string', 'max:255', 'unique:permissions'],
        ]);
        
        $Name = $request->Name;
        $Permission = new Permission;
        $Permission->Name   = $request->Name;
        $Permission->UserId = $request->UserId;
        $Permission->Status = $request->Status;

        if ($Permission->save()) {
            return redirect()
                ->route('permission')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Permission",
            'data'  => Permission::find($Id),
            'employees' => User::where('IsAdmin', false)->where('Status', 1)->get()
        ];
        return view('permissions.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'Name' => [
                'required', 'string', 'max:255',
                Rule::unique('permissions')->ignore($Id, 'Id')
            ]
        ]);
        
        $Name = $request->Name;
        $Permission = Permission::find($Id);
        $Permission->Name   = $request->Name;
        $Permission->UserId = $request->UserId;
        $Permission->Status = $request->Status;

        if ($request->Status == 0 && $this->isActive($Id)) {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        } else {
            if ($Permission->save()) {
                return redirect()
                    ->route('permission')
                    ->with('success', "<b>{$Name}</b> successfully updated!");
            } 
        }

    }

    public function delete($Id) {
        $Permission = Permission::find($Id);
        $Name = $Permission->Name;

        if (!$this->isActive($Id) && $Permission->delete()) {
            return redirect()
                ->route('permission')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        }
    }

    public function isActive($Id) {
        return User::where('PermissionId', $Id)->count() ? true : false;
    }

}
