<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\LeaveRequest;
use App\Models\LeaveRequestFiles;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\admin\ModuleApproval;
use App\Models\admin\ModuleFormApprover;
use App\Models\admin\LeaveType;
use App\Mail\LeaveRequestMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;


class LeaveRequestController extends Controller
{
    private $MODULE_ID = 4;

    public function index() {
        isReadAllowed($this->MODULE_ID, true);

        $myData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName', 'u2.FirstName AS aFirstName', 'u2.LastName AS aLastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'leave_requests.UserId')
            ->leftJoin('module_form_approvers AS mfa', function($join) {
                $join->on('mfa.TableId', 'leave_requests.Id')
                    ->where('mfa.ModuleId', $this->MODULE_ID)
                    ->where('mfa.Status', 1)
                    ->where('leave_requests.Status', '=', 1);
            })
            ->leftJoin('users AS u2', 'u2.Id', 'mfa.ApproverId')
            ->where('UserId', Auth::id())
            ->get();

        $forApprovalData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName', 'u2.FirstName AS aFirstName', 'u2.LastName AS aLastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'leave_requests.UserId')
            ->leftJoin('module_form_approvers AS mfa', function($join) {
                $join->on('mfa.TableId', 'leave_requests.Id')
                    ->where('mfa.ModuleId', $this->MODULE_ID)
                    ->where('mfa.Status', 1)
                    ->where('leave_requests.Status', '=', 1);
            })
            ->leftJoin('users AS u2', 'u2.Id', 'mfa.ApproverId')
            ->where('mfa.ApproverId', Auth::id())
            ->get();

        $approvedData = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Status', 2)
            ->get();
        $calendarData = [];
        foreach ($approvedData as $dt) {
            $calendarData[] = [
                'id'        => $dt['Id'],
                'title'     => $dt['FirstName'].' '.$dt['LastName'],
                'start'     => $dt['StartDate'],
                'end'       => date('Y-m-d', strtotime($dt['EndDate'].' +1 day')),
                'className' => $dt['LeaveTypeId'] == config('constant.ID.LEAVE_TYPES.VACATION_LEAVE') ? 'bg-success' : ($dt['LeaveTypeId'] == config('constant.ID.LEAVE_TYPES.SICK_LEAVE') ? 'bg-danger' : 'bg-info'),
                'leaveType' => $dt['LeaveTypeId'],
                'color'     => 'black',
                'allDay'    => true
            ];
        }

        $data = [
            'title'           => 'Leave',
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
            'title' => "New Leave",
            'data'  => User::find(Auth::id()),
            'leaveTypes' => LeaveType::select('leave_types.*',
                DB::raw('(SELECT "Balance" FROM user_leave_balances WHERE "UserId" = \''.Auth::id().'\' AND "LeaveTypeId" = "leave_types"."Id") AS "Balance"'))
                ->where('Status', 1)->get(),
            'event' => 'add'
        ];
        return view('leaveRequest.form', $data);
    }

    public function sendMail($Id, $nextLevelApprover = 0, $Status = 0) {
        $data = LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Id', $Id)
            ->first();

        if ($data) {
            if ($Status == 1) {
                if ($nextLevelApprover) {
                    $nextApprover = ModuleFormApprover::where('ModuleId', $this->MODULE_ID)
                        ->where('TableId', $Id)
                        ->where('Level', $nextLevelApprover)
                        ->first();
                    if ($nextApprover) {
                        $update = ModuleFormApprover::where('Id', $nextApprover['Id'])->update(['Status' => 1]);
                        $approver = User::find($nextApprover['ApproverId']);
                    }
                } else {
                    $approver = User::select('users.*')
                        ->leftJoin('module_form_approvers AS mfa', 'mfa.ApproverId', 'users.Id')
                        ->where('ModuleId', $this->MODULE_ID)
                        ->where('TableId', $Id)
                        ->where('mfa.Status', 1)
                        ->orderBy('mfa.Level', 'ASC')
                        ->first();
                }

                if ($approver) {
                    $email = $approver->email;

                    $Title       = "Leave";
                    $Description = "<b>".$data->DocumentNumber."</b> - ".$data->FirstName.' '.$data->LastName." is asking for your approval.";
                    $Link        = route('leaveRequest.view', ['Id' => $Id]);
                    $Icon        = '/assets/img/icons/for-approval.png';

                    Mail::to($email)->send(new LeaveRequestMail($data, $approver));
                    Notification::sendNow($approver, new SystemNotification($Id, $Title, $Description, $Link, $Icon));
                }
            } else {
                $user = User::find($data->UserId);
                $email = $user->email;

                $Title       = "Leave";
                $Link        = route('leaveRequest.view', ['Id' => $Id]);

                if ($Status == 2) { // APPPROVED
                    $Description = "<b>".$data->DocumentNumber."</b> - Your leave has been approved.";
                    $Icon        = '/assets/img/icons/approved.png';
                } else if ($Status == 3) {
                    $Description = "<b>".$data->DocumentNumber."</b> - Your leave has been rejected.";
                    $Icon        = '/assets/img/icons/rejected.png';
                }

                Mail::to($email)->send(new LeaveRequestMail($data, $user));
                Notification::sendNow($user, new SystemNotification($Id, $Title, $Description, $Link, $Icon));
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
        $LeaveRequest->LeaveTypeId    = $request->LeaveTypeId;
        $LeaveRequest->StartDate      = $request->StartDate;
        $LeaveRequest->EndDate        = $request->EndDate;
        $LeaveRequest->LeaveDuration  = $request->LeaveDuration;
        $LeaveRequest->LeaveBalance   = $request->LeaveBalance;
        $LeaveRequest->Reason         = $request->Reason;
        $LeaveRequest->Status         = 1;

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
            $this->sendMail($Id, 0, 1); // ID, APPROVER LEVEL: 0 | FIRST, STATUS: 1 - FOR APPROVAL

            return redirect()
                ->route('leaveRequest')
                ->with('tab', 'My Forms')
                ->with('success', "<b>{$DocumentNumber}</b> successfully saved!");
        } 
    }

    public function view($Id) {
        $leaveRequestData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName', 'ApproverId', 'u2.FirstName AS aFirstName', 'u2.LastName AS aLastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'leave_requests.UserId')
            ->leftJoin('module_form_approvers AS mfa', function($join) {
                $join->on('mfa.TableId', 'leave_requests.Id')
                    ->where('mfa.ModuleId', $this->MODULE_ID)
                    ->where('mfa.Status', 1)
                    ->where('leave_requests.Status', '=', 1);
            })
            ->leftJoin('users AS u2', 'u2.Id', 'mfa.ApproverId')
            ->where('leave_requests.Id', $Id)
            ->first();

        $data = [
            'title' => "View Leave",
            'data' => $leaveRequestData,
            'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
            'approvers' => ModuleFormApprover::select('module_form_approvers.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'ApproverId')
                ->where('ModuleId', $this->MODULE_ID)
                ->where('TableId', $Id)
                ->orderBy('Level', 'ASC')
                ->get(),
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

        if (isFormPending($this->MODULE_ID, $Id) || $data->Status == 3) {
            $data = [
                'title' => "Revise Leave",
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
        $LeaveRequest->Status        = 1; // FOR APPROVAL

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
            $this->sendMail($Id, 0, 1); // ID, APPROVER: 0 - FIRST, STATUS: 1 - FOR APPROVAL

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

        $nextLevelApprover = $ModuleFormApprover->first()->Level + 1;

        $data = [
            'Date'    => date('Y-m-d H:i:s'),
            'Status'  => 2,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data)) {
            $newStatus = getFormStatus($this->MODULE_ID, $Id);
            $this->sendMail($Id, $nextLevelApprover, $newStatus);
            $LeaveRequest->Status = $newStatus;
            if ($LeaveRequest->save()) {
                return redirect()
                    ->route('leaveRequest')
                    ->with('tab', 'My Forms')
                    ->with('success', "<b>{$LeaveRequest->DocumentNumber}</b> successfully approved!");
            }
        }
        
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
            'Status'  => 3,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data) && $LeaveRequest->save()) {
            $this->sendMail($Id);
            $LeaveRequest->Status = getFormStatus($this->MODULE_ID, $Id);
            if ($LeaveRequest->save()) {
                return redirect()
                    ->route('leaveRequest')
                    ->with('tab', 'My Forms')
                    ->with('success', "<b>{$LeaveRequest->DocumentNumber}</b> successfully rejected!");
            }
        }
        
    }
    

    

    

    

    

    

    public function externalApprove($Id) {
        echo $Id;
    }

    public function externalReject($Id) {
        echo $Id;
    }

}
