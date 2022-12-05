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
use App\Models\admin\ModuleFormApprover;
use App\Models\admin\LeaveType;

class LeaveRequestController extends Controller
{
    private $MODULE_ID = 4;

    public function index() {
        isReadAllowed($this->MODULE_ID, true);

        $myData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('UserId', Auth::id())
            ->get();

        $forApprovalData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->leftJoin('module_form_approvers AS mfa', 'leave_requests.Id', 'TableId')
            ->where('ApproverId', Auth::id())
            ->get();

        $approvedData = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Status', 1)
            ->get();
        $calendarData = [];
        foreach ($approvedData as $dt) {
            $calendarData[] = [
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
            'title'           => 'Leave Request',
            'myData'          => $myData,
            'forApprovalData' => $forApprovalData,
            'calendarData'    => $calendarData,
            'leaveTypes'      => LeaveType::where('Status', 1)->get(),
            'MODULE_ID'       => $this->MODULE_ID,
        ];

        return view('leaveRequest.index', $data);
    }

    public function form() {
        isCreateAllowed($this->MODULE_ID, true);

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

    public function sendMail($Id) {
        $data = LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Id', $Id)
            ->first();

        $approvers = ModuleFormApprover::select('module_form_approvers.*', 'FirstName', 'LastName', 'email')
            ->leftJoin('users AS u', 'ApproverId', 'u.Id')
            ->where('ModuleId', $this->MODULE_ID)
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

    public function save(Request $request) {
        $validator = $request->validate([
            'UserId'        => ['required'],
            'LeaveTypeId'   => ['required'],
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
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            setFormApprovers($this->MODULE_ID, $Id); // SET APPROVERS
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
                ->where('ModuleId', $this->MODULE_ID)
                ->where('TableId', $Id)
                ->orderBy('Level', 'ASC')
                ->get(),
            'currentApprover' => ModuleFormApprover::select('module_form_approvers.*')
                ->leftJoin('leave_requests AS lr', 'lr.Id', 'TableId')
                ->where('ModuleId', $this->MODULE_ID)
                ->where('TableId', $Id)
                ->where('module_form_approvers.Status', 0)
                ->where('lr.Status', '!=', 2)
                ->orderBy('Level', 'ASC')
                ->first()->ApproverId ?? null,
            'pending' => isFormPending($this->MODULE_ID, $Id),
            'event' => 'view',
            'leaveTypes' => LeaveType::where('Status', 1)->get(),
            'MODULE_ID' => $this->MODULE_ID,
        ];

        return view('leaveRequest.form', $data);
    }

    public function revise($Id) {
        isEditAllowed($this->MODULE_ID, true);

        $data = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Id', $Id)
            ->first();

        if (isFormPending($this->MODULE_ID, $Id) || $data->Status == 2) {
            $data = [
                'title' => "Revise Leave Request",
                'data'  => $data,
                'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
                'currentApprover' => null,
                'event'      => 'edit',
                'leaveTypes' => LeaveType::where('Status', 1)->get(),
            ];
            return view('leaveRequest.form', $data);
        } else {
            return redirect()->back()->withErrors(['Cannot revise the form that has been approved or ongoing for approval']);
        }
    }

    public function update(Request $request, $Id) {
        $validator = $request->validate([
            'UserId'        => ['required'],
            'LeaveTypeId'   => ['required'],
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
                        'CreatedById'    => Auth::id(),
                        'UpdatedById'    => Auth::id(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            setFormApprovers($this->MODULE_ID, $Id); // SET APPROVERS
            $this->sendMail($Id);

            return redirect()
                ->route('leaveRequest')
                ->with('tab', 'My Forms')
                ->with('success', "<b>{$DocumentNumber}</b> successfully updated!");
        } 
    }
    

    

    

    

    

    

    public function approve(Request $request, $Id, $UserId) {
        $LeaveRequest = LeaveRequest::find($Id);
        $ModuleFormApprover = ModuleFormApprover::where('ModuleId', $this->MODULE_ID)
            ->where('TableId', $Id)
            ->where('ApproverId', $UserId)
            ->limit(1);

        $data = [
            'Date'    => date('Y-m-d H:i:s'),
            'Status'  => 1,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data)) {
            $LeaveRequest->Status = getFormStatus($this->MODULE_ID, $Id);
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
        $ModuleFormApprover = ModuleFormApprover::where('ModuleId', $this->MODULE_ID)
            ->where('TableId', $Id)
            ->where('ApproverId', $UserId)
            ->limit(1);

        $data = [
            'Date'    => date('Y-m-d H:i:s'),
            'Status'  => 2,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data) && $LeaveRequest->save()) {
            $LeaveRequest->Status = getFormStatus($this->MODULE_ID, $Id);
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
