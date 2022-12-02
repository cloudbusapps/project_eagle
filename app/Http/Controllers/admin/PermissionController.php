<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\admin\Permission;
use App\Models\admin\Designation;
use App\Models\admin\Module;
use App\Models\User;
use Illuminate\Support\Str;
use Auth;

class PermissionController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Permission',
            'designations' => Designation::where('Status', 1)->get(),
        ];
        return view('admin.permissions.index', $data);
    }

    public function edit($Id) {
        $data = Permission::where('DesignationId', $Id)->get();
        return response()->json($data, 200);
    }

    public function save(Request $request, $Id) {
        $data = $request->data;
        
        foreach ($data as $index => $dt) {
            $data[$index]['Id']          = Str::uuid();
            $data[$index]['CreatedById'] = Auth::id();
            $data[$index]['UpdatedById'] = Auth::id();
        }

        $delete = Permission::where('DesignationId', $Id)->delete();
        $Permission = Permission::insert($data);
        if ($Permission) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    }





    public function form() {
        $data = [
            'title' => "New Permission",
            'employees' => User::where('IsAdmin', false)->where('Status', 1)->get()
        ];
        return view('admin.permissions.form', $data);
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
