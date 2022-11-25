<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\LeaveRequest;
use App\Models\LeaveRequestFiles;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;
use App\Mail\LeaveRequestMail;
use Illuminate\Support\Facades\Mail;
use App\Models\ModuleApproval;
use App\Models\ModuleFormApprover;

class LeaveRequestController extends Controller
{
    public $ModuleId = 4;

    public function sendMail($Id) {
        $data = LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Id', $Id)
            ->first();
        Mail::to('apdiangzon@epldt.com')->send(new LeaveRequestMail($data));
    }

    public function index() {
        $data = [
            'title' => 'Leave Request',
            'data' => LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->get()
        ];
        return view('leaveRequest.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Leave Request",
            'data'  => User::find(Auth::id()),
            'event' => 'add'
        ];
        return view('leaveRequest.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'UserId'        => ['required'],
            'LeaveType'     => ['required'],
            'StartDate'     => ['required', 'date'],
            'EndDate'       => ['required', 'date'],
            'LeaveDuration' => ['required'],
            'LeaveBalance'  => ['required'],
            'Reason'        => ['required', 'string', 'max:500'],
        ]);

        $destinationPath = 'uploads/leaveRequest';
        
        $LeaveRequest = new LeaveRequest;
        $LeaveRequest->UserId        = $request->UserId;
        $LeaveRequest->LeaveType     = $request->LeaveType;
        $LeaveRequest->StartDate     = $request->StartDate;
        $LeaveRequest->EndDate       = $request->EndDate;
        $LeaveRequest->LeaveDuration = $request->LeaveDuration;
        $LeaveRequest->LeaveBalance  = $request->LeaveBalance;
        $LeaveRequest->Reason        = $request->Reason;

        if ($LeaveRequest->save()) {
            $Id = $LeaveRequest->Id;
            $DocumentNumber = generateDocumentNumber('LRF', LeaveRequest::find($Id)->DocumentNumber);

            $files = $request->file('File');
            if ($files && count($files)) {
                $leaveRequestFileData = [];
                foreach ($files as $index => $file) {
                    $filenameArr = explode('.', $file->getClientOriginalName());
                    $extension   = array_splice($filenameArr, count($filenameArr)-1, 1);
                    $filename    = $DocumentNumber.'['.$index.']'.time().'.'.$extension[0];
    
                    $file->move($destinationPath, $filename);

                    $leaveRequestFileData[] = [
                        'Id'             => Str::uuid(),
                        'LeaveRequestId' => $Id,
                        'File'           => $filename,
                        'Created_By_Id'  => Auth::id(),
                        'Updated_By_Id'  => Auth::id(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            // SET APPROVERS
            $DesignationId = Auth::user()->DesignationId;
            $approvers = ModuleApproval::where('ModuleId', $this->ModuleId)
                ->where('DesignationId', $DesignationId)
                ->get();

            $approverData = [];
            foreach ($approvers as $dt) {
                $approverData[] = [
                    'Id'            => Str::uuid(),
                    'ModuleId'      => $this->ModuleId,
                    'TableId'       => $Id,
                    'Level'         => $dt['Level'],
                    'ApproverId'    => $dt['ApproverId'],
                    'Status'        => 0,
                    'Date'          => null,
                    'Remarks'       => null,
                    'Created_By_Id' => Auth::id(),
                    'Updated_By_Id' => Auth::id(),
                ];
            }
            if ($approverData && count($approverData)) {
                ModuleFormApprover::insert($approverData);
            }

            return redirect()
                ->route('leaveRequest')
                ->with('success', "<b>{$DocumentNumber}</b> successfully saved!");
        } 
    }

    public function view($Id) {
        $data = [
            'title' => "View Leave Request",
            'data'  => LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->where('leave_requests.Id', $Id)
                ->first(),
            'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
            'approvers' => ModuleFormApprover::select('module_form_approvers.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'ApproverId')
                ->where('ModuleId', $this->ModuleId)->where('TableId', $Id)->get(),
            'event' => 'view'
        ];

        return view('leaveRequest.form', $data);
    }

    public function revise($Id) {
        $data = [
            'title' => "Revise Leave Request",
            'data'  => LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->where('leave_requests.Id', $Id)
                ->first(),
            'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
            'event' => 'edit'
        ];
        return view('leaveRequest.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'UserId'        => ['required'],
            'LeaveType'     => ['required'],
            'StartDate'     => ['required', 'date'],
            'EndDate'       => ['required', 'date'],
            'LeaveDuration' => ['required'],
            'LeaveBalance'  => ['required'],
            'Reason'        => ['required', 'string', 'max:500'],
        ]);

        $destinationPath = 'uploads/leaveRequest';
        
        $LeaveRequest = LeaveRequest::find($Id);
        $LeaveRequest->UserId        = $request->UserId;
        $LeaveRequest->LeaveType     = $request->LeaveType;
        $LeaveRequest->StartDate     = $request->StartDate;
        $LeaveRequest->EndDate       = $request->EndDate;
        $LeaveRequest->LeaveDuration = $request->LeaveDuration;
        $LeaveRequest->LeaveBalance  = $request->LeaveBalance;
        $LeaveRequest->Reason        = $request->Reason;

        if ($LeaveRequest->save()) {
            $Id = $LeaveRequest->Id;
            $DocumentNumber = generateDocumentNumber('LRF', $LeaveRequest->DocumentNumber);

            $files = $request->file('File');
            if ($files && count($files)) {
                $leaveRequestFileData = [];
                foreach ($files as $index => $file) {
                    $filenameArr = explode('.', $file->getClientOriginalName());
                    $extension   = array_splice($filenameArr, count($filenameArr)-1, 1);
                    $filename    = $DocumentNumber.'['.$index.']'.time().'.'.$extension[0];
    
                    $file->move($destinationPath, $filename);

                    $leaveRequestFileData[] = [
                        'Id'             => Str::uuid(),
                        'LeaveRequestId' => $Id,
                        'File'           => $filename,
                        'Created_By_Id'  => Auth::id(),
                        'Updated_By_Id'  => Auth::id(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            return redirect()
                ->route('leaveRequest')
                ->with('success', "<b>{$DocumentNumber}</b> successfully saved!");
        } 
    }

    public function approve($Id) {
        echo $Id;
    }

    public function reject($Id) {
        echo $Id;
    }

}
