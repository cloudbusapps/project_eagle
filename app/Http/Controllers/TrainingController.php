<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Training;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Training',
            'data'  => Training::leftJoin('users', 'users.Id', '=', 'trainings.UserId')
                ->select('users.FirstName', 'users.LastName', 'trainings.*')
                ->orderBy('trainings.UserId', 'DESC')
                ->get(),
            'MODULE_ID' => config('constant.ID.MODULES.MODULE_ONE.TRAININGS')
        ];

        return view('trainings.index', $data);
    }

    public function form() {
        isCreateAllowed(config('constant.ID.MODULES.MODULE_ONE.TRAININGS'), true);
        $data = [
            'title' => "New Training",
            'users' => User::where('IsAdmin', false)
                ->where('Status', 1)
                ->get(),
            'todo' => 'create'
        ];
        return view('trainings.form', $data);
    }

    public function save(Request $request) {
        $Name = $request->TrainingName;

        $filename = '';
        $file = $request->file('Attachments');
        if ($file) {
            $destination_path = 'uploads/trainings/';
            $filename_arr = explode('.', $file->getClientOriginalName());
            $extension = array_splice($filename_arr, count($filename_arr)-1, 1);
            $filename = str_replace(' ', '-', strtolower($Name)) . time() . '.' . $extension[0];
            $file->move($destination_path, $filename);
        }

        $Training = new Training;
        $Training->UserId            = $request->UserId;
        $Training->Status            = $request->TrainingStatus;
        $Training->Type              = $request->TrainingType;
        $Training->Name              = $request->TrainingName;
        $Training->StartDate         = date('Y-m-d', strtotime($request->StartDate));
        $Training->EndDate           = date('Y-m-d', strtotime($request->EndDate));
        $Training->Facilitator       = $request->Facilitator;
        $Training->Purpose           = $request->Purpose;
        $Training->Attachments       = $filename;
        $Training->WithCertification = isset($request->WithCertification) ? 1 : 0;
        $Training->CreatedById       = Auth::id();
        $Training->UpdatedById       = Auth::id();
        $Training->created_at        = now();
        $Training->updated_at        = now();

        if ($Training->save()) {
            return redirect()
                ->route('training')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 

        return redirect()
            ->back()
            ->with('fail', "There's an error occured. Please try again later...")
            ->withInput();
    }

    public function view($Id) {
        $data = [
            'title' => "View Training",
            'data'  => Training::leftJoin('users', 'users.Id', '=', 'trainings.UserId')
                ->select('users.FirstName', 'users.LastName', 'trainings.*')
                ->where('trainings.Id', $Id)
                ->first(),
            'todo' => 'read'
        ];
        return view('trainings.form', $data);
    }

    public function edit($Id) {
        isEditAllowed(config('constant.ID.MODULES.MODULE_ONE.TRAININGS'), true);
        $data = [
            'title' => "Edit Training",
            'users' => User::where('IsAdmin', false)
                ->where('Status', 1)
                ->get(),
            'data'  => Training::find($Id),
            'todo' => 'update'
        ];
        return view('trainings.form', $data);
    }

    public function update(Request $request, $Id) {
        $Name = $request->TrainingName;

        $filename = '';
        $file = $request->file('Attachments');
        if ($file) {
            $destination_path = 'uploads/trainings/';
            $filename_arr = explode('.', $file->getClientOriginalName());
            $extension = array_splice($filename_arr, count($filename_arr)-1, 1);
            $filename = str_replace(' ', '-', strtolower($Name)) . time() . '.' . $extension[0];
            $file->move($destination_path, $filename);
        }

        $Training = Training::find($Id);
        $Training->UserId            = $request->UserId;
        $Training->Status            = $request->TrainingStatus;
        $Training->Type              = $request->TrainingType;
        $Training->Name              = $request->TrainingName;
        $Training->StartDate         = date('Y-m-d', strtotime($request->StartDate));
        $Training->EndDate           = date('Y-m-d', strtotime($request->EndDate));
        $Training->Facilitator       = $request->Facilitator;
        $Training->Purpose           = $request->Purpose;
        $Training->Attachments       = $filename;
        $Training->WithCertification = isset($request->WithCertification) ? 1 : 0;
        $Training->UpdatedById       = Auth::id();
        $Training->updated_at        = now();

        if ($Training->save()) {
            return redirect()
                ->route('training')
                ->with('success', "<b>{$Name}</b> successfully saved!");
        } 

        return redirect()
            ->back()
            ->with('fail', "There's an error occured. Please try again later...")
            ->withInput();
    }

    public function delete($Id) {
        $Training = Training::find($Id);
        $Name = $Training->Name;

        if ($Training->delete()) {
            return redirect()
                ->route('training')
                ->with('success', "<b>{$Name}</b> successfully deleted!");
        } 

        return redirect()
            ->back()
            ->with('fail', "There's an error occured. Please try again later...")
            ->withInput();
    }

}
