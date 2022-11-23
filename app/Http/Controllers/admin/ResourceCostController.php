<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\admin\ResourceCost;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResourceCostController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Resource Cost',
            'data'  => User::select('users.Id', 'm.Id AS ResourceCostId', 'Level', 'BasicSalary', 'DailyRate', 'HourlyRate', 'FirstName', 'LastName')
                ->leftJoin('resource_costs AS m', 'users.Id', 'm.UserId')
                ->get()
        ];

        return view('admin.resourceCost.index', $data);
    }

    public function edit($Id) {
        $data = [
            'title' => 'Edit Resource Cost',
            'data'  => User::where('users.Id', $Id)
                ->select('users.Id', 'm.Id AS ResourceCostId', 'Level', 'BasicSalary', 'DailyRate', 'HourlyRate', 'FirstName', 'LastName')
                ->leftJoin('resource_costs AS m', 'users.Id', 'm.UserId')
                ->first()
        ];

        return view('admin.resourceCost.form', $data);
    }

    public function update(Request $request, $Id, $ResourceCostId = null) {
        $validator = $request->validate([
            'Level'       => ['required'],
            'BasicSalary' => ['required', 'regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/'],
        ]);

        $BasicSalary = $request->BasicSalary;
        $DailyRate   = $BasicSalary / 22;
        $HourlyRate  = $DailyRate / 8;

        if ($ResourceCostId) {
            $ResourceCost = ResourceCost::findOrFail($ResourceCostId);
        } else {
            $ResourceCost = new ResourceCost;
        }

        $ResourceCost->UserId      = $Id;
        $ResourceCost->Level       = $request->Level;
        $ResourceCost->BasicSalary = $BasicSalary;
        $ResourceCost->DailyRate   = $DailyRate;
        $ResourceCost->HourlyRate  = $HourlyRate;

        if ($ResourceCost->save()) {
            $FullName = User::find($Id)->full_name;
            return redirect()->route('resourceCost')
                ->with('success', "{$FullName} successfully updated!");
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }
}
