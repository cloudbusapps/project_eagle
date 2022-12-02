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
use App\Models\admin\ModuleApproval;
use App\Models\ModuleFormApprover;
use App\Models\admin\LeaveType;

class LeaveRequestController extends Controller
{
    public $ModuleId = 4;

    public function sendMail($Id) {
        $data = LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Id', $Id)
            ->first();

        $approvers = ModuleFormApprover::select('module_form_approvers.*', 'FirstName', 'LastName', 'email')
            ->leftJoin('users AS u', 'ApproverId', 'u.Id')
            ->where('ModuleId', $this->ModuleId)
            ->where('TableId', $Id)
            ->orderBy('Level', 'ASC')
            ->get();

        $flag = false;
        if ($approvers && count($approvers)) {
            foreach ($approvers as $approver) {
                $Status = $approver['Status'];
                $email  = $approver['email'];

                if ($Status == 2) { // REJECTED
                    $flag = true;
                }

                if (!$flag && $Status == 0) {
                    Mail::to($email)->send(new LeaveRequestMail($data, $approver));
                    $flag = true;
                }
            }
        }
    }

    public function getStatus($Id) {
        $approvers = ModuleFormApprover::where('ModuleId', $this->ModuleId)
            ->where('TableId', $Id)
            ->get(['Status']);
        $status = 0; $approvedCount = 0;
        if ($approvers && count($approvers)) {
            foreach ($approvers as $dt) {
                if ($dt['Status'] == 2) $status = 2; // REJECTED
                if ($dt['Status'] == 1) {
                    $approvedCount++;
                }
            }
            $status = count($approvers) == $approvedCount ? 1 : $status; // APPROVED
        }
        return $status;
    }

    public function setApprovers($Id) {
        $DesignationId = Auth::user()->DesignationId;
        $delete = ModuleFormApprover::where('ModuleId', $this->ModuleId)->where('TableId', $Id)->delete();

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
                'CreatedById' => Auth::id(),
                'UpdatedById' => Auth::id(),
            ];
        }
        if ($approverData && count($approverData)) {
            ModuleFormApprover::insert($approverData);
        }
        return true;
    }

    public function isPending($Id) {
        $count = 0;
        $approvers = ModuleFormApprover::where('ModuleId', $this->ModuleId)->where('TableId', $Id)->get();
        foreach ($approvers as $approver) {
            if ($approver['Status'] == 0) $count++;
        }
        return count($approvers) > 0 && count($approvers) == $count;
    }

    public function index() {
        $approveData = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Status', 1)
            ->get();
        $calendar = [];
        foreach ($approveData as $dt) {
            $calendar[] = [
                'id'        => $dt['Id'],
                'title'     => $dt['FirstName'].' '.$dt['LastName'],
                'start'     => $dt['StartDate'],
                'end'       => date('Y-m-d', strtotime($dt['EndDate'].' +1 day')),
                'className' => $dt['LeaveType'] == 'Vacation Leave' ? 'bg-success' : ($dt['LeaveType'] == 'Sick Leave' ? 'bg-danger' : 'bg-info'),
                'leaveType' => $dt['LeaveType'],
                'color'     => 'black',
                'allDay'    => true
            ];
        }

        $data = [
            'title' => 'Leave Request',
            'data' => LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName')
                ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->where('UserId', Auth::id())
                ->get(),
            'forApproval' => LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->leftJoin('module_form_approvers AS mfa', 'leave_requests.Id', 'TableId')
                ->where('ApproverId', Auth::id())
                ->get(),
            'calendar' => $calendar,
            'leaveTypes' => LeaveType::where('Status', 1)->get(),
        ];
        return view('leaveRequest.index', $data);
    }

    public function form() {
        $data = [
            'title' => "New Leave Request",
            'data'  => User::find(Auth::id()),
            'leaveTypes' => LeaveType::select('leave_types.*',
                DB::raw('(SELECT "Balance" FROM user_leave_balances WHERE "UserId" = \''.Auth::id().'\' AND "LeaveTypeId" = "leave_types"."Id") AS "Balance"'))
                ->where('Status', 1)->get(),
            'event' => 'add'
        ];
        return view('leaveRequest.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'UserId'        => ['required'],
            'LeaveTypeId'     => ['required'],
            'StartDate'     => ['required', 'date'],
            'EndDate'       => ['required', 'date'],
            'LeaveDuration' => ['required'],
            'LeaveBalance'  => ['required'],
            'Reason'        => ['required', 'string', 'max:500'],
        ]);

        $destinationPath = 'uploads/leaveRequest';

        $number = getLastDocumentNumber(LeaveRequest::orderBy('DocumentNumber', 'DESC')->first()->DocumentNumber ?? null);
        $DocumentNumber = generateDocumentNumber('LRF', $number);
        
        $LeaveRequest = new LeaveRequest;
        $LeaveRequest->DocumentNumber = $DocumentNumber;
        $LeaveRequest->UserId         = $request->UserId;
        $LeaveRequest->LeaveTypeId      = $request->LeaveTypeId;
        $LeaveRequest->StartDate      = $request->StartDate;
        $LeaveRequest->EndDate        = $request->EndDate;
        $LeaveRequest->LeaveDuration  = $request->LeaveDuration;
        $LeaveRequest->LeaveBalance   = $request->LeaveBalance;
        $LeaveRequest->Reason         = $request->Reason;

        if ($LeaveRequest->save()) {
            $Id = $LeaveRequest->Id;
            
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
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            $this->setApprovers($Id); // SET APPROVERS
            $this->sendMail($Id);

            return redirect()
                ->route('leaveRequest')
                ->with('tab', 'My Forms')
                ->with('success', "<b>{$DocumentNumber}</b> successfully saved!");
        } 
    }

    public function view($Id) {
        $data = [
            'title' => "View Leave Request",
            'data' => LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName')
                ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->where('leave_requests.Id', $Id)
                ->first(),
            'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
            'approvers' => ModuleFormApprover::select('module_form_approvers.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'ApproverId')
                ->where('ModuleId', $this->ModuleId)
                ->where('TableId', $Id)
                ->orderBy('Level', 'ASC')
                ->get(),
            'currentApprover' => ModuleFormApprover::select('module_form_approvers.*')
                ->leftJoin('leave_requests AS lr', 'lr.Id', 'TableId')
                ->where('ModuleId', $this->ModuleId)
                ->where('TableId', $Id)
                ->where('module_form_approvers.Status', 0)
                ->where('lr.Status', '!=', 2)
                ->orderBy('Level', 'ASC')
                ->first()->ApproverId ?? null,
            'pending' => $this->isPending($Id),
            'event' => 'view',
            'leaveTypes' => LeaveType::where('Status', 1)->get(),
        ];

        return view('leaveRequest.form', $data);
    }

    public function revise($Id) {
        $data = [
            'title' => "Revise Leave Request",
            'data'  => LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'u.FirstName', 'u.LastName')
                ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
                ->leftJoin('users AS u', 'u.Id', 'UserId')
                ->where('leave_requests.Id', $Id)
                ->first(),
            'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
            'currentApprover' => null,
            'event' => 'edit',
            'leaveTypes' => LeaveType::where('Status', 1)->get(),
        ];
        return view('leaveRequest.form', $data);
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'UserId'        => ['required'],
            'LeaveTypeId'     => ['required'],
            'StartDate'     => ['required', 'date'],
            'EndDate'       => ['required', 'date'],
            'LeaveDuration' => ['required'],
            'LeaveBalance'  => ['required'],
            'Reason'        => ['required', 'string', 'max:500'],
        ]);

        $destinationPath = 'uploads/leaveRequest';
        
        $LeaveRequest = LeaveRequest::find($Id);
        $LeaveRequest->UserId        = $request->UserId;
        $LeaveRequest->LeaveTypeId     = $request->LeaveTypeId;
        $LeaveRequest->StartDate     = $request->StartDate;
        $LeaveRequest->EndDate       = $request->EndDate;
        $LeaveRequest->LeaveDuration = $request->LeaveDuration;
        $LeaveRequest->LeaveBalance  = $request->LeaveBalance;
        $LeaveRequest->Reason        = $request->Reason;
        $LeaveRequest->Status        = 0; // FOR APPROVAL

        if ($LeaveRequest->save()) {
            $Id = $LeaveRequest->Id;
            $DocumentNumber = $LeaveRequest->DocumentNumber;

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
                        'CreatedById'  => Auth::id(),
                        'UpdatedById'  => Auth::id(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            $this->setApprovers($Id);
            $this->sendMail($Id);

            return redirect()
                ->route('leaveRequest')
                ->with('tab', 'My Forms')
                ->with('success', "<b>{$DocumentNumber}</b> successfully updated!");
        } 
    }

    public function approve(Request $request, $Id, $UserId) {
        $LeaveRequest = LeaveRequest::find($Id);
        $ModuleFormApprover = ModuleFormApprover::where('ModuleId', $this->ModuleId)
            ->where('TableId', $Id)
            ->where('ApproverId', $UserId)
            ->limit(1);

        $data = [
            'Date'    => date('Y-m-d H:i:s'),
            'Status'  => 1,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data)) {
            $LeaveRequest->Status = $this->getStatus($Id);
            if ($LeaveRequest->save()) {
                $this->sendMail($Id);

                return redirect()
                    ->route('leaveRequest')
                    ->with('tab', 'For Approval')
                    ->with('success', "<b>{$LeaveRequest->DocumentNumber}</b> successfully approved!");
            }
        }
        
    }

    public function externalApprove($Id) {
        echo $Id;
    }

    public function reject(Request $request, $Id, $UserId) {
        $validator = $request->validate([
            'Remarks' => ['required'],
        ]);

        $LeaveRequest = LeaveRequest::find($Id);
        $ModuleFormApprover = ModuleFormApprover::where('ModuleId', $this->ModuleId)
            ->where('TableId', $Id)
            ->where('ApproverId', $UserId)
            ->limit(1);

        $data = [
            'Date'    => date('Y-m-d H:i:s'),
            'Status'  => 2,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data) && $LeaveRequest->save()) {
            $LeaveRequest->Status = $this->getStatus($Id);
            if ($LeaveRequest->save()) {
                return redirect()
                    ->route('leaveRequest')
                    ->with('tab', 'For Approval')
                    ->with('success', "<b>{$LeaveRequest->DocumentNumber}</b> successfully rejected!");
            }
        }
        
    }

    public function externalReject($Id) {
        echo $Id;
    }

}
