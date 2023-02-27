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
use App\Models\UserLeaveBalance;
use App\Mail\LeaveRequestMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SystemNotification;
use Spatie\Activitylog\Models\Activity;


class LeaveRequestController extends Controller
{
    public function index() {
        isReadAllowed(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), true);

        $myData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName', 'u2.FirstName AS aFirstName', 'u2.LastName AS aLastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'leave_requests.UserId')
            ->leftJoin('module_form_approvers AS mfa', function($join) {
                $join->on('mfa.TableId', 'leave_requests.Id')
                    ->where('mfa.ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
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
                    ->where('mfa.ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
                    ->where('mfa.Status', 1)
                    ->where('leave_requests.Status', '=', 1);
            })
            ->leftJoin('users AS u2', 'u2.Id', 'mfa.ApproverId')
            ->where('mfa.ApproverId', Auth::id())
            ->get();

        $approvedData = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'lt.Acronym', 'u.FirstName', 'u.LastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Status', 2)
            ->get();
        $calendarData = [];
        foreach ($approvedData as $dt) {
            $calendarData[] = [
                'id'        => $dt['Id'],
                'title'     => $dt['Acronym'].' - '.$dt['FirstName'].' '.$dt['LastName'],
                'start'     => $dt['StartDate'],
                'end'       => date('Y-m-d', strtotime($dt['EndDate'].' +1 day')),
                'className' => $dt['LeaveTypeId'] == config('constant.ID.LEAVE_TYPES.VACATION_LEAVE') ? 'bg-success' : ($dt['LeaveTypeId'] == config('constant.ID.LEAVE_TYPES.SICK_LEAVE') ? 'bg-danger' : 'bg-dark'),
                'leaveType' => $dt['LeaveTypeId'],
                'color'     => 'black',
                'allDay'    => true,
                'url'       => route('leaveRequest.view', ['Id' => $dt['Id']]),
            ];
        }
        if(isAdminOrHead()){
            $leaveHistory = Activity::select('activity_log.*','leave_types.Name AS LeaveName','leave_requests.DocumentNumber','users.FirstName','users.LastName')
            ->where('subject_type','App\Models\LeaveRequest')
            ->leftJoin('leave_requests','leave_requests.Id','activity_log.subject_id')
            ->leftJoin('leave_types','leave_types.Id','leave_requests.LeaveTypeId')
            ->leftJoin('users','users.Id','=','leave_requests.UserId')
            ->orWhere(function($query){
                $query->where('leave_requests.Id',DB::raw('activity_log.subject_id'))
                ->where('leave_requests.UserId',Auth::id());
            })
            ->orderBy('created_at','DESC')
            ->get();
        } else{
            $leaveHistory = Activity::select('activity_log.*','leave_types.Name AS LeaveName','leave_requests.DocumentNumber','users.FirstName','users.LastName')
            ->where('causer_id',Auth::id())
            ->where('subject_type','App\Models\LeaveRequest')
            ->leftJoin('leave_requests','leave_requests.Id','activity_log.subject_id')
            ->leftJoin('leave_types','leave_types.Id','leave_requests.LeaveTypeId')
            ->leftJoin('users','users.Id','=','leave_requests.UserId')
            ->orWhere(function($query){
                $query->where('leave_requests.Id',DB::raw('activity_log.subject_id'))
                ->where('leave_requests.UserId',Auth::id());
            })
            ->orderBy('created_at','DESC')
            ->get();
        }
        

        $data = [
            'title'           => 'Leave',
            'myData'          => $myData,
            'forApprovalData' => $forApprovalData,
            'calendarData'    => $calendarData,
            'leaveTypes'      => LeaveType::where('Status', 1)->get(),
            'MODULE_ID'       => config('constant.ID.MODULES.MODULE_ONE.LEAVE'),
            'leavesHistory'   => $leaveHistory,
        ];

        return view('leaveRequest.index', $data);
    }

    public function form() {
        isCreateAllowed(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), true);

        $data = [
            'title' => "New Leave",
            'data'  => User::find(Auth::id()),
            'leaveTypes' => LeaveType::where('Status', 1)->get(),
            'event' => 'add'
        ];
        return view('leaveRequest.form', $data);
    }

    public function sendMail($Id, $nextLevelApprover = 0, $Status = 0) {
        $data = LeaveRequest::select('leave_requests.*', 'u.FirstName', 'u.LastName','lt.Name as LeaveType')
            ->leftJoin('leave_types AS lt', 'lt.Id', '=','leave_requests.LeaveTypeId')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->where('leave_requests.Id', $Id)
            ->first();

        if ($data) { 
            if ($Status == 1) {
                if ($nextLevelApprover) {
                    $nextApprover = ModuleFormApprover::where('ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
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
                        ->where('ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
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
                    $Icon        = 'assets/img/icons/for-approval.png';

                    // Mail::to($email)->send(new LeaveRequestMail($data, $approver));
                    Notification::sendNow($approver, new SystemNotification($Id, $Title, $Description, $Link, $Icon));
                }
            } else {
                $user = User::find($data->UserId);
                $email = $user->email;

                $Title       = "Leave";
                $Link        = route('leaveRequest.view', ['Id' => $Id]);

                if ($Status == 2) { // APPPROVED
                    $Description = "<b>".$data->DocumentNumber."</b> - Your leave has been approved.";
                    $Icon        = 'assets/img/icons/approved.png';
                } else if ($Status == 3) {
                    $Description = "<b>".$data->DocumentNumber."</b> - Your leave has been rejected.";
                    $Icon        = 'assets/img/icons/rejected.png';
                }

                // Mail::to($email)->send(new LeaveRequestMail($data, $user));
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
        $LeaveRequest->IsWholeDay     = $LeaveRequest->LeaveDuration > 1 ? 1 : (isset($request->IsWholeDay) ? 1 : 0);
        $LeaveRequest->StartTime      = $request->StartTime;
        $LeaveRequest->EndTime        = $request->EndTime;
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
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            setFormApprovers(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), $Id); // SET APPROVERS
            $this->sendMail($Id, 0, 1); // ID, APPROVER LEVEL: 0 | FIRST, STATUS: 1 - FOR APPROVAL

            $FullName = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
            LeaveRequest::logActivity("{$FullName} - {$DocumentNumber} created leave request",$LeaveRequest);

            return redirect()
                ->route('leaveRequest')
                ->with('tab', 'My Forms')
                ->with('success', "<b>{$DocumentNumber}</b> successfully saved!");
        } 
    }

    public function view($Id) {
        isReadAllowed(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), true);
        
        $leaveRequestData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName', 'ApproverId', 'u2.FirstName AS aFirstName', 'u2.LastName AS aLastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'leave_requests.UserId')
            ->leftJoin('module_form_approvers AS mfa', function($join) {
                $join->on('mfa.TableId', 'leave_requests.Id')
                    ->where('mfa.ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
                    ->where('mfa.Status', 1)
                    ->where('leave_requests.Status', '=', 1);
            })
            ->leftJoin('users AS u2', 'u2.Id', 'mfa.ApproverId')
            ->where('leave_requests.Id', $Id)
            ->first();
        $pending = isFormPending(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), $Id);

        $data = [
            'title'    => "View Leave",
            'data'     => $leaveRequestData,
            'files'    => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
            'approvers' => ModuleFormApprover::select('module_form_approvers.*', 'u.FirstName', 'u.LastName')
                ->leftJoin('users AS u', 'u.Id', 'ApproverId')
                ->where('ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
                ->where('TableId', $Id)
                ->orderBy('Level', 'ASC')
                ->get(),
            'pending'    => $pending,
            'event'      => 'view',
            'leaveTypes' => LeaveType::where('Status', 1)->get(),
            'MODULE_ID'  => config('constant.ID.MODULES.MODULE_ONE.LEAVE'),
        ];

        return view('leaveRequest.form', $data);
    }

    public function revise($Id) {
        isEditAllowed(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), true);

        // $data = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'u.FirstName', 'u.LastName')
        //     ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
        //     ->leftJoin('users AS u', 'u.Id', 'UserId')
        //     ->where('leave_requests.Id', $Id)
        //     ->first();

        $leaveRequestData = LeaveRequest::select('leave_requests.*', 'lt.Name', 'u.FirstName', 'u.LastName', 'ApproverId', 'u2.FirstName AS aFirstName', 'u2.LastName AS aLastName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'leave_requests.UserId')
            ->leftJoin('module_form_approvers AS mfa', function($join) {
                $join->on('mfa.TableId', 'leave_requests.Id')
                    ->where('mfa.ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
                    ->where('mfa.Status', 1)
                    ->where('leave_requests.Status', '=', 1);
            })
            ->leftJoin('users AS u2', 'u2.Id', 'mfa.ApproverId')
            ->where('leave_requests.Id', $Id)
            ->first();

        $pending = isFormPending(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), $Id);

        // if (!$pending || $leaveRequestData->Status == 3) {
        if (!$pending) {
            $data = [
                'title' => "Revise Leave",
                'data'  => $leaveRequestData,
                'files' => LeaveRequestFiles::where('LeaveRequestId', $Id)->get(),
                'currentApprover' => null,
                'event'      => 'edit',
                'pending'    => $pending,
                'leaveTypes' => LeaveType::where('Status', 1)->get(),
            ];
            return view('leaveRequest.form', $data);
        } else {
            return redirect()->route('leaveRequest.view', ['Id' => $Id])->withErrors(['Cannot revise the form that has been approved or ongoing for approval']);
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
        $LeaveRequest->LeaveTypeId   = $request->LeaveTypeId;
        $LeaveRequest->StartDate     = $request->StartDate;
        $LeaveRequest->EndDate       = $request->EndDate;
        $LeaveRequest->LeaveDuration = $request->LeaveDuration;
        $LeaveRequest->LeaveBalance  = $request->LeaveBalance;
        $LeaveRequest->Reason        = $request->Reason;
        $LeaveRequest->IsWholeDay    = $LeaveRequest->LeaveDuration > 1 ? 1 : (isset($request->IsWholeDay) ? 1 : 0);
        $LeaveRequest->StartTime     = $request->StartTime;
        $LeaveRequest->EndTime       = $request->EndTime;
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
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];
                }

                LeaveRequestFiles::where('LeaveRequestId', $Id)->delete();
                LeaveRequestFiles::insert($leaveRequestFileData);
            }

            setFormApprovers(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), $Id); // SET APPROVERS
            $this->sendMail($Id, 0, 1); // ID, APPROVER: 0 - FIRST, STATUS: 1 - FOR APPROVAL

            $FullName = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
            LeaveRequest::logActivity("{$FullName} - {$DocumentNumber} updated leave request",$LeaveRequest);

            return redirect()
                ->route('leaveRequest')
                ->with('tab', 'My Forms')
                ->with('success', "<b>{$DocumentNumber}</b> successfully updated!");
        } 
    }

    public function deductLeaveCredit($LeaveRequest)
    {
        $UserId        = $LeaveRequest->UserId;
        $LeaveDuration = $LeaveRequest->LeaveDuration ?? 1;
        $OldBalance = $NewBalance = 0;

        $LeaveBalance = UserLeaveBalance::where('UserId', $UserId)
            ->where('LeaveTypeId', $LeaveRequest->LeaveTypeId)
            ->where('Balance', '>', 0)
            ->orderBy('Year', 'ASC')
            ->first();
        
        if ($LeaveBalance) {
            $Credit  = $LeaveBalance->Credit ?? 0;
            $Accrued = $LeaveBalance->Accrued ?? 0;
            $Used    = $LeaveBalance->Used ?? 0;
            $Balance = $LeaveBalance->Balance ?? 0;

            $OldBalance = $Balance;

            $Remaining = $Credit - $LeaveDuration;

            if ($Remaining < 0) {
                $Remaining = abs($Remaining);

                $Accrued = $Accrued - $Remaining;
                $Accrued = $Accrued > 0 ? $Accrued : 0;
            } 

            $Used = $Used + $LeaveDuration;
            $Balance = $Balance - $LeaveDuration;
            $Balance = $Balance > 0 ? $Balance : 0;

            $NewBalance = $Balance;

            $data = [
                'Accrued' => $Accrued,
                'Used'    => $Used,
                'Balance' => $Balance,
            ];
            UserLeaveBalance::where('Id', $LeaveBalance->Id)->update($data);
        }

        return ['OldBalance' => $OldBalance, 'NewBalance' => $NewBalance];
    }
    
    public function approve(Request $request, $Id, $UserId) {
        $LeaveRequest = LeaveRequest::select('leave_requests.*','leave_types.Name as LeaveType',DB::raw("CONCAT(users.FirstName,' ',users.LastName) AS FullName"))
        ->where('leave_requests.Id',$Id)
        ->leftJoin('leave_types','leave_types.Id','leave_requests.LeaveTypeId')
        ->leftJoin('users','users.Id','leave_requests.UserId')
        ->first();
        $DocumentNumber = $LeaveRequest->DocumentNumber;

        $ModuleFormApprover = ModuleFormApprover::where('ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
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
            $newStatus = getFormStatus(config('constant.ID.MODULES.MODULE_ONE.LEAVE'), $Id);
           
            $LeaveRequest->Status = $newStatus;
            $FullName = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
            if ($LeaveRequest->save()) {
                if ($newStatus == 2) { // APPROVED
                    $balance = $this->deductLeaveCredit($LeaveRequest);
                    if ($balance['OldBalance'] > 0) {
                        $attributes=[
                            ['data'=>"{$LeaveRequest->LeaveDuration} is deducted from {$LeaveRequest->FullName}'s {$LeaveRequest->LeaveType} credit"]
                        ];
                        LeaveRequest::logActivity("{$FullName} approved {$DocumentNumber} leave request",$LeaveRequest,$attributes);
                    } else {
                        LeaveRequest::logActivity("{$FullName} approved {$DocumentNumber} leave request",$LeaveRequest);
                    }
                } else{
                    
                    LeaveRequest::logActivity("{$FullName} approved {$DocumentNumber} leave request proceeding to next approver",$LeaveRequest);
                }
                
                
                
                $this->sendMail($Id, $nextLevelApprover, $newStatus);
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
        $DocumentNumber = $LeaveRequest->DocumentNumber;

        $ModuleFormApprover = ModuleFormApprover::where('ModuleId', config('constant.ID.MODULES.MODULE_ONE.LEAVE'))
            ->where('TableId', $Id)
            ->where('ApproverId', $UserId)
            ->limit(1);

        $data = [
            'Date'    => date('Y-m-d H:i:s'),
            'Status'  => 3,
            'Remarks' => $request->Remarks,
        ];

        if ($ModuleFormApprover->update($data) && $LeaveRequest->save()) {
            $newStatus = 3;
            $LeaveRequest->Status = $newStatus;
            if ($LeaveRequest->save()) {
            $FullName = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
            LeaveRequest::logActivity("{$FullName} rejected {$DocumentNumber} leave request",$LeaveRequest);
            $this->sendMail($Id, false, $newStatus);
                return redirect()
                    ->route('leaveRequest')
                    ->with('tab', 'My Forms')
                    ->with('success', "<b>{$LeaveRequest->DocumentNumber}</b> successfully rejected!");
            }
        }
        
    }
    

    

    

    

    

    

    public function externalApprove($Id) {
        return redirect()->route('leaveRequest.view', ['Id' => $Id]);
    }

    public function externalReject($Id) {
        return redirect()->route('leaveRequest.view', ['Id' => $Id]);
    }

}
