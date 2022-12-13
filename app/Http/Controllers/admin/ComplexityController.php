<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\admin\Complexity;
use App\Models\admin\ComplexityDetails;
use App\Models\User;
use Illuminate\Support\Str;
use Auth;

class ComplexityController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Complexity',
            'data'  => Complexity::all()
        ];
        return view('admin.complexity.index', $data);
    }

    public function form() {
        $data = [
            'title'      => "New Complexity",
            'complexity' => Complexity::where('Status', 1)->get()
        ];
        return view('admin.complexity.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'title' => ['required', 'string', 'max:500'],
        ]);
        
        $Title = $request->Title;
        $Complexity = new Complexity;
        $Complexity->Title   = $request->Title;
        $Complexity->Status = $request->Status;

        if ($Complexity->save()) {
            $Id = $Complexity->Id;

            $SubDetail = $request->SubDetail;
            $SubStatus = $request->SubStatus;
            if ($SubDetail && count($SubDetail)) {
                $subData = [];
                foreach ($SubDetail as $i => $detail) {
                    $subData[] = [
                        'Id'           => Str::uuid(),
                        'ComplexityId' => $Id,
                        'Title'        => $detail,
                        'Status'       => $SubStatus[$i],
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }
                ComplexityDetails::insert($subData);
            }

            return redirect()
                ->route('complexity')
                ->with('success', "<b>{$Title}</b> successfully saved!");
        } 
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Complexity",
            'data'  => Complexity::find($Id),
            'details' => ComplexityDetails::where('ComplexityId', $Id)->get()
        ];
        return view('admin.complexity.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'Title' => ['required', 'string', 'max:255']
        ]);
        
        $Title = $request->Title;
        $Complexity = Complexity::find($Id);
        $Complexity->Title   = $request->Title;
        $Complexity->Status = $request->Status;

        if ($Complexity->save()) {
            $delete = ComplexityDetails::where('ComplexityId', $Id)->delete();

            $SubDetail = $request->SubDetail;
            $SubStatus = $request->SubStatus;
            if ($SubDetail && count($SubDetail)) {
                $subData = [];
                foreach ($SubDetail as $i => $detail) {
                    $subData[] = [
                        'Id'           => Str::uuid(),
                        'ComplexityId' => $Id,
                        'Title'        => $detail,
                        'Status'       => $SubStatus[$i],
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }
                ComplexityDetails::insert($subData);
            }

            return redirect()
                ->route('complexity')
                ->with('success', "<b>{$Title}</b> successfully updated!");
        } 

    }

    public function delete($Id) {
        $Complexity = Complexity::find($Id);
        $Title = $Complexity->Title;

        if ($Complexity->delete()) {
            $delete = ComplexityDetails::where('ComplexityId', $Id)->delete();
            
            return redirect()
                ->route('complexity')
                ->with('success', "<b>{$Title}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Title} is currently in used!"])
                ->withInput();
        }
    }

}
