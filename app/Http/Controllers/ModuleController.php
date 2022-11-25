<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Module;

class ModuleController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Modules',
            'data' => DB::table('modules AS m')
                ->select('m.*', 'm2.Title AS ParentTitle')
                ->leftJoin('modules AS m2', 'm2.id', 'm.ParentId')
                ->get()
        ];
        return view('modules.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Module",
            'modules' => Module::where('ParentId', null)->get()
        ];
        return view('modules.form', $data);
    }

    public function save(Request $request) {
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
        
        $Module = new Module;
        $Module->ParentId  = $request->ParentId;
        $Module->Title     = $request->Title;
        $Module->WithApproval = $WithApproval;
        $Module->SortOrder = $request->SortOrder;
        $Module->Icon      = $filename;
        $Module->Status    = $request->Status;
        $Module->RouteName = $request->RouteName;
        $Module->Prefix    = $request->Prefix;

        if ($Module->save()) {
            return redirect()
                ->route('module')
                ->with('success', "<b>{$Title}</b> successfully saved!");
        } 
    }

    public function edit($id) {
        $data = [
            'title' => "Edit Module",
            'modules' => Module::where('ParentId', null)->get(),
            'data' => Module::find($id)
        ];
        return view('modules.form', $data);
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
        
        $Module = Module::find($id);
        $Module->ParentId  = $request->ParentId;
        $Module->Title     = $request->Title;
        $Module->WithApproval = $WithApproval;
        $Module->SortOrder = $request->SortOrder;
        $Module->Icon      = $filename;
        $Module->Status    = $request->Status;
        $Module->RouteName = $request->RouteName;
        $Module->Prefix    = $request->Prefix;

        if ($Module->save()) {
            return redirect()
                ->route('module')
                ->with('success', "<b>{$Title}</b> successfully updated!");
        } 
    }

    public function delete($id) {
        $Module = Module::find($id);
        $Title = $Module->Title;

        if ($Module->delete()) {
            return redirect()
                ->route('module')
                ->with('success', "<b>{$Title}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('module')
                ->with('fail', "<b>{$Title}</b> failed to delete!");
        }
    }

}
