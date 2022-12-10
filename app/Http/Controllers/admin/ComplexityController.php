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
            'Name' => ['required', 'string', 'max:500'],
        ]);
        
        $Name = $request->Name;
        $Complexity = new Complexity;
        $Complexity->Name   = $request->Name;
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
                        'Details'      => $detail,
                        'Status'       => $SubStatus[$i],
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }
                ComplexityDetails::insert($subData);
            }

            return redirect()
                ->route('complexity')
                ->with('success', "<b>{$Name}</b> successfully saved!");
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
            'Name' => ['required', 'string', 'max:255']
        ]);
        
        $Name = $request->Name;
        $Complexity = Complexity::find($Id);
        $Complexity->Name   = $request->Name;
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
                        'Details'      => $detail,
                        'Status'       => $SubStatus[$i],
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }
                ComplexityDetails::insert($subData);
            }

            return redirect()
                ->route('complexity')
                ->with('success', "<b>{$Name}</b> successfully updated!");
        } 

    }

    public function delete($Id) {
        $Complexity = Complexity::find($Id);
        $Name = $Complexity->Name;

        if ($Complexity->delete()) {
            return redirect()
                ->route('complexity')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } else {
            return redirect()
                ->back()
                ->withErrors(["{$Name} is currently in used!"])
                ->withInput();
        }
    }

}
