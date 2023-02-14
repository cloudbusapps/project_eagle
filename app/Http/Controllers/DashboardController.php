<?php

namespace App\Http\Controllers;

use App\Models\admin\LeaveType;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        // if(isAdminOrHead()){
            $approvedData = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'lt.Acronym', 'u.FirstName', 'u.LastName','p.Name AS ProjectName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->leftJoin('projects AS p', function($join){
                $join->on('p.KickoffDate', '>=', 'leave_requests.StartDate');
                $join->on('p.KickoffDate','<=','leave_requests.EndDate');
            })
            ->where('leave_requests.Status', 2)
            ->whereMonth('leave_requests.StartDate', now()->month)
            ->orderBy('leave_requests.StartDate','DESC','leave_requests.EndDate','DESC')
            ->get();

            // UPCOMING LEAVE 14 DAYS FROM NOW
            $upcomingLeaves = LeaveRequest::select('leave_requests.*', 'lt.Name AS LeaveType', 'lt.Acronym', 'u.FirstName', 'u.LastName','p.Name AS ProjectName')
            ->leftJoin('leave_types AS lt', 'leave_requests.LeaveTypeId', 'lt.Id')
            ->leftJoin('users AS u', 'u.Id', 'UserId')
            ->leftJoin('projects AS p', function($join){
                $join->on('p.KickoffDate', '>=', 'leave_requests.StartDate');
                $join->on('p.KickoffDate','<=','leave_requests.EndDate');
            })
            ->where('leave_requests.Status', 2)
            ->whereBetween('leave_requests.StartDate',[Carbon::today(),Carbon::today()->addDays(14)])
            ->orderBy('leave_requests.StartDate','DESC','leave_requests.EndDate','DESC')
            ->get();

            $data = [
                'title'   => "Dashboard",   
                'activities' => Activity::where(DB::raw("DATE(created_at)"), date('Y-m-d'))
                    ->orderBy('updated_at', 'DESC')
                    ->limit(6)
                    ->get(),
                'total' => [
                    'users' => DB::table('users')->count(),
                    'projects' => DB::table('projects')->count(),
                    'approvedLeave' => DB::table('leave_requests')->where('Status',2)->count(),
                    'pendingLeave' => DB::table('leave_requests')->where('Status',1)->count(),
                    'rejectedLeave' => DB::table('leave_requests')->where('Status',3)->count(),
                ],
                'leavesData'     => $approvedData,
                'upcomingLeaves' => $upcomingLeaves,
                'leaveTypes' => LeaveType::where('Status',1)
                    ->get(['*',
                        DB::raw("(SELECT COUNT(*) FROM leave_requests WHERE Status = 2 AND leave_types.Id = LeaveTypeId) AS totalLeave")])
            ];

        // } else{
        //     $data = [
        //         'title'   => "Dashboard",   
        //         'activities' => Activity::where(DB::raw("DATE(created_at)"), date('Y-m-d'))
        //             ->where('causer_id',Auth::id())
        //             ->orderBy('updated_at', 'DESC')
        //             ->limit(6)
        //             ->get(),
        //         'total' => [
        //             'users' => DB::table('users')->count(),
        //             'projects' => DB::table('projects')->count()
        //         ]
        //     ];
        // }
        return view('dashboard', $data);
    }
}
