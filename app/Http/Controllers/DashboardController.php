<?php

namespace App\Http\Controllers;

use App\Models\admin\LeaveType;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Auth;

class DashboardController extends Controller
{
    public function index() {
        if(isAdminOrHead()){
            $approvedData = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'lt.Acronym', 'u.FirstName', 'u.LastName','p.Name AS ProjectName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->leftJoin('projects AS p', function($join){
                $join->on('p.KickoffDate', '>=', 'leave_requests.StartDate');
                $join->on('p.KickoffDate','<=','leave_requests.EndDate');
            })
            ->where('leave_requests.Status', 2)
            ->whereMonth('leave_requests.StartDate', now()->month)
            ->orderBy('leave_requests.StartDate','ASC','leave_requests.EndDate','ASC')
            ->get();

        $data = [
            'title'   => "Dashboard",   
            'activities' => Activity::where(DB::raw('DATE("created_at")'), date('Y-m-d'))
                ->orderBy('updated_at', 'DESC')
                ->limit(6)
                ->get(),
            'total' => [
                'users' => DB::table('users')->count(),
                'projects' => DB::table('projects')->count()
            ],
            'leavesData' => $approvedData,
            'leaveTypes' => LeaveType::leftJoin('leave_requests','leave_requests.LeaveTypeId','=','leave_types.Id')
            ->groupBy('leave_types.Id')
            ->where('leave_types.Status',1)
            ->get(['leave_types.*',DB::raw('COUNT(CASE WHEN "leave_requests"."Status" = 2 THEN 1 END) AS "totalLeave"')])
        ];
        } else{
            $data = [
                'title'   => "Dashboard",   
                'activities' => Activity::where(DB::raw('DATE("created_at")'), date('Y-m-d'))
                    ->orderBy('updated_at', 'DESC')
                    ->limit(6)
                    ->where('causer_id',Auth::id())
                    ->get(),
                'total' => [
                    'users' => DB::table('users')->count(),
                    'projects' => DB::table('projects')->count()
                ]
            ];
        }
        return view('dashboard', $data);
    }
}
