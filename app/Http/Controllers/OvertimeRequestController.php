<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Overtime Request',
            'data' => OvertimeRequest::where('UserId', Auth::id())
                ->orderBy('Date', 'ASC')
                ->get()
        ];
        return view('overtimeRequest.index', $data);
    }

    public function addOvertimeRequest()
    {
        $data = [
            'title'          => 'Add Overtime Request',
            'data'    => [],
            'type'           => 'insert',
        ];
        return view('overtimeRequest.form', $data);
    }
    public function editOvertimeRequest($Id)
    {
        $OvertimeRequest = OvertimeRequest::where('Id', $Id)->first();
        $data = [
            'title'          => 'Edit Overtime Request',
            'OvertimeRequest'    => $OvertimeRequest,
            'type'           => 'edit',
            'Id'           => $Id,
        ];
        return view('overtimeRequest.form', $data);
    }
    public function saveOvertimeRequest(Request $request)
    {
        $validator = $request->validate([
            'Agenda'     => ['required', 'string', 'max:255'],
            'Date' => ['required'],
            'TimeIn'    => ['required'],
            'TimeOut'      => ['required'],
            'Reason'     => ['required', 'string', 'max:255'],
        ]);

        $Agenda = $request->Agenda;
        $userId = Auth::id();
        $OvertimeRequest = new OvertimeRequest();
        $OvertimeRequest->UserId  = $userId;
        $OvertimeRequest->Agenda  = $Agenda;
        $OvertimeRequest->Date  = $request->Date;
        $OvertimeRequest->TimeIn  = $request->TimeIn;
        $OvertimeRequest->TimeOut  = $request->TimeOut;
        $OvertimeRequest->Reason  = $request->Reason;
        $OvertimeRequest->Created_By_Id  = $userId;

        if ($OvertimeRequest->save()) {
            return redirect()
                ->route('overtimeRequest')
                ->with('success', "<b>{$Agenda}</b> successfully saved!");
        } else {
            return redirect()
                ->route('overtimeRequest')
                ->with('fail', "Something went wrong, please try again");
        }
    }
    public function updateOvertimeRequest(Request $request, $Id)
    {
        $OvertimeRequest = OvertimeRequest::where('Id', $Id)->first();
        $validator = $request->validate([
            'Agenda'     => ['required', 'string', 'max:255'],
            'Date' => ['required'],
            'TimeIn'    => ['required'],
            'TimeOut'      => ['required'],
            'Reason'     => ['required', 'string', 'max:255'],
        ]);

        $Agenda = $request->Agenda;
        $userId = Auth::id();
        $OvertimeRequest->UserId  = $userId;
        $OvertimeRequest->Agenda  = $Agenda;
        $OvertimeRequest->Date  = $request->Date;
        $OvertimeRequest->TimeIn  = $request->TimeIn;
        $OvertimeRequest->TimeOut  = $request->TimeOut;
        $OvertimeRequest->Reason  = $request->Reason;
        $OvertimeRequest->Updated_By_Id  = $userId;

        if ($OvertimeRequest->update()) {
            return redirect()
                ->route('overtimeRequest')
                ->with('success', "<b>{$Agenda}</b> successfully updated!");
        } else {
            return redirect()
                ->route('overtimeRequest')
                ->with('fail', "Something went wrong, please try again");
        }
    }
    public function deleteOvertimeRequest($Id)
    {
        $OvertimeRequest = OvertimeRequest::find($Id);
        $Agenda = $OvertimeRequest->Agenda;

        if ($OvertimeRequest->delete()) {
            return redirect()
                ->route('overtimeRequest')
                ->with('success', "<b>{$Agenda}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('overtimeRequest')
                ->with('fail', "<b>{$Agenda}</b> failed to delete!");
        }
    }
}
