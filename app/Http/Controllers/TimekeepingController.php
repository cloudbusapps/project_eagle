<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\timekeeping\Timekeeping;
use App\Models\timekeeping\TimekeepingDetails;
use App\Models\Project;
use Auth;
use Illuminate\Support\Str;

class TimekeepingController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Timekeeping',
            'data'  => Timekeeping::where('UserId', Auth::id()) 
                ->orderBy('Date', 'DESC')
                ->get()
        ];

        return view('timekeeping.index', $data);
    }

    public function form() {
        $data = [
            'title'    => "New Timekeeping",
            'projects' => Project::all(),
        ];
        return view('timekeeping.form', $data);
    }

    public function save(Request $request) {
        $Date = date('Y-m-d', strtotime($request->Date));

        $Timekeeping = new Timekeeping;
        $Timekeeping->UserId      = Auth::id();
        $Timekeeping->Date        = $Date;
        $Timekeeping->TotalHours  = $request->TotalHours;
        $Timekeeping->CreatedById = Auth::id();
        $Timekeeping->UpdatedById = Auth::id();
        $Timekeeping->created_at  = now();
        $Timekeeping->updated_at  = now();

        if ($Timekeeping->save()) {
            $TimekeepingId = $Timekeeping->Id;

            $ProjectId   = $request->ProjectId ?? [];
            $StartTime   = $request->StartTime ?? [];
            $EndTime     = $request->EndTime ?? [];
            $Description = $request->Description ?? [];
            $Hours       = $request->Hours ?? [];

            if (isset($ProjectId) && count($ProjectId)) {
                $details = [];
                foreach ($ProjectId as $i => $id) {
                    $details[] = [
                        'Id'            => Str::uuid(),
                        'TimekeepingId' => $TimekeepingId,
                        'StartTime'     => $StartTime[$i],
                        'EndTime'       => $EndTime[$i],
                        'ProjectId'     => $id,
                        'ProjectName'   => null,
                        'Description'   => $Description[$i],
                        'Hours'         => $Hours[$i],
                    ];
                }
                TimekeepingDetails::insert($details);
            }

            $Date = date('F d, Y', strtotime($Date));
            return redirect()
                ->route('timekeeping')
                ->with('success', "<b>{$Date}</b> successfully saved!");
        } 

        return redirect()
            ->back()
            ->with('fail', "There's an error occured. Please try again later...")
            ->withInput();
    }

    public function edit($Id) {
        $data = [
            'title' => "Edit Timekeeping",
            'data'  => Timekeeping::find($Id),
            'details' => TimekeepingDetails::where('TimekeepingId', $Id)->get(),
            'projects' => Project::all(),
        ];
        return view('timekeeping.form', $data);
    }

    public function update(Request $request, $Id) {
        $Date = date('Y-m-d', strtotime($request->Date));

        $Timekeeping = Timekeeping::find($Id);
        $Timekeeping->Date        = $Date;
        $Timekeeping->TotalHours  = $request->TotalHours;
        $Timekeeping->UpdatedById = Auth::id();
        $Timekeeping->updated_at  = now();

        if ($Timekeeping->save()) {
            TimekeepingDetails::where('TimekeepingId', $Id)->delete();

            $ProjectId   = $request->ProjectId ?? [];
            $StartTime   = $request->StartTime ?? [];
            $EndTime     = $request->EndTime ?? [];
            $Description = $request->Description ?? [];
            $Hours       = $request->Hours ?? [];

            if (isset($ProjectId) && count($ProjectId)) {
                $details = [];
                foreach ($ProjectId as $i => $id) {
                    $details[] = [
                        'Id'            => Str::uuid(),
                        'TimekeepingId' => $Id,
                        'StartTime'     => $StartTime[$i],
                        'EndTime'       => $EndTime[$i],
                        'ProjectId'     => $id,
                        'ProjectName'   => null,
                        'Description'   => $Description[$i],
                        'Hours'         => $Hours[$i],
                    ];
                }
                TimekeepingDetails::insert($details);
            }

            $Date = date('F d, Y', strtotime($Date));
            return redirect()
                ->route('timekeeping')
                ->with('success', "<b>{$Date}</b> successfully updated!");
        } 

        return redirect()
            ->back()
            ->with('fail', "There's an error occured. Please try again later...")
            ->withInput();
    }

    public function delete($Id) {
        $Timekeeping = Timekeeping::find($Id);
        $Date = date('F d, Y', strtotime($Timekeeping->Date));

        if ($Timekeeping->delete()) {
            return redirect()
                ->route('timekeeping')
                ->with('success', "<b>{$Date}</b> successfully deleted!");
        } 

        return redirect()
            ->back()
            ->with('fail', "There's an error occured. Please try again later...")
            ->withInput();
    }

}
