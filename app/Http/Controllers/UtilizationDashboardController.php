<?php

namespace App\Http\Controllers;


use App\Models\timekeeping\Timekeeping;
use App\Models\timekeeping\TimekeepingDetails;

use Illuminate\Http\Request;

// MODELS
use Spatie\Activitylog\Models\Activity;
use App\Models\admin\CompanySetting;
use App\Models\Resource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Auth;

class UtilizationDashboardController extends Controller
{
    public function index() {
        if(Auth::id()==config('constant.ID.USERS.BA_HEAD') || Auth::user()->IsAdmin==1){
            $data = [
                'title'   => "Utilizatiion Dashboard",   
                'activities' => Activity::where(DB::raw('DATE("created_at")'), date('Y-m-d'))
                    ->orderBy('updated_at', 'DESC')
                    ->limit(6)
                    ->get(),
                'total' => [
                    'users' => DB::table('users')->count(),
                    'projects' => DB::table('projects')->count()
                ],
                'projectResources' => $this->getUserProject('admin'),
                'projects' => Project::all(),
                'users' => DB::table('users')->where('IsAdmin',false)->get(),
                'timekeepingDatas' => $this->getEmployeeHours(),
                'WorkinghoursData' => $this->WorkinghoursData(),
            ];
        } else{
            $data = [
                'title'   => "Utilizatiion Dashboard",   
                'activities' => Activity::where(DB::raw('DATE("created_at")'), date('Y-m-d'))
                    ->orderBy('updated_at', 'DESC')
                    ->limit(6)
                    ->get(),
                'total' => [
                    'users' => DB::table('users')->count(),
                    'projects' => DB::table('projects')->count()
                ],
                'projectResources' => $this->getUserProject(),
                'projects' => Resource::where('UserId',Auth::id())
                ->leftJoin('projects','projects.Id','=','resources.ProjectId')
                ->get(['projects.*','resources.ProjectId']),
                'users' => DB::table('users')->where('Id',Auth::id())->get(),
                'timekeepingDatas' => $this->getEmployeeHours(Auth::id()),
            ];
        }

        return view('utilizationDashboard.index', $data);
    }

    // HELPERS FOR UTILIZATION DASHBOARD CONTROLLER

    // DATA FOR DATATABLE
    public function filterDataByDate(Request $request){
        return $this->WorkinghoursData($request->startDate,$request->endDate);
    }

    public function getUserProject($userType=''){
        if($userType==='admin'){
            $projectResources= Resource::select('resources.*','projects.Name AS ProjectName','projects.IsComplex')
            ->leftJoin('projects','resources.ProjectId','projects.Id')
            ->get();
            $users = User::where('IsAdmin',false)->orderBy('DesignationId')
            ->leftJoin('designations','designations.Id','=','users.DesignationId')
            ->where('users.Status',1)
            ->get(['users.*','designations.Name AS DesignationName']);
        } else{
            $projectResources= Resource::select('resources.*','projects.Name AS ProjectName')
        ->leftJoin('projects','resources.ProjectId','projects.Id')
        ->where('UserId',Auth::id())
        ->get();
        $users = User::where('IsAdmin',false)->orderBy('DesignationId')
        ->leftJoin('designations','designations.Id','=','users.DesignationId')
        ->where('users.Id',Auth::id())
        ->get(['users.*','designations.Name AS DesignationName']);
        }
        
        $data=[];

        foreach($users as $user){
            $temp=[
                'Id'             => $user->Id,
                'FirstName'      => $user->FirstName,
                'LastName'       => $user->LastName,
                'FullName'       => $user->FirstName.' '.$user->LastName,
                'DesignationName'  => $user->DesignationName,
                'ProjectsId'     => [],
                'ProjectCount'   => [
                    'Complex'       => 0,
                    'Intermediate'  => 0,
                    'Easy'          => 0
                ]
            ];
            $complex = 0;
            $intermediate = 0;
            $easy = 0;

            foreach($projectResources as $key=> $projectResource){
                if($projectResource->UserId===$user->Id){
                    $temp['ProjectsId'][]=$projectResource->ProjectId;
                    if($projectResource->IsComplex==1){
                        $complex += 1;
                        $temp['ProjectCount']=[
                            'Complex' => $complex,
                            'Intermediate' => $intermediate,
                            'Easy' => $easy,
                        ];
                    } if($projectResource->IsComplex==2){
                        $intermediate += 1;
                        $temp['ProjectCount']=[
                            'Complex' => $complex,
                            'Intermediate' => $intermediate,
                            'Easy' => $easy,
                        ];
                    
                    } if($projectResource->IsComplex==3){
                        $easy += 1;
                        $temp['ProjectCount']=[
                            'Complex' => $complex,
                            'Intermediate' => $intermediate,
                            'Easy' => $easy,
                        ];
                    } 
                }
               
            }
            $data[]=$temp;
        }

        return $data;

    }

    function WorkinghoursData($startDate='',$endDate=''){
        if($startDate!=='' && $endDate!==''){

            $CompanySetting = CompanySetting::select('AnnualWorkingHours')->first();

            $users = User::where('IsAdmin',false)->orderBy('DesignationId')
            ->leftJoin('designations','designations.Id','=','users.DesignationId')
            ->leftJoin('timekeepings','timekeepings.UserId','=','users.Id')
            ->leftJoin('leave_requests', function($join){
                $join->on('leave_requests.UserId', '=', 'users.Id');
                $join->where('leave_requests.Status','=',2);
            })
            ->where('users.Status',1)
            // ->whereBetween('timekeepings.Date', [$startDate, $endDate])
            ->groupBy('users.Id')
            ->get(['users.*','designations.Name AS DesignationName',
            DB::raw('(SUM(CASE WHEN timekeepings.Date BETWEEN "'.$startDate.'" AND "'.$endDate.'" 
            THEN timekeepings.TotalHours ELSE 0 END)) as TotalSumHours, 
            SUM(leave_requests.LeaveDuration) as TotalLeaveDuration')]);
        } else{
            $CompanySetting = CompanySetting::select('AnnualWorkingHours')->first();

            $users = User::where('IsAdmin',false)->orderBy('DesignationId')
            ->leftJoin('designations','designations.Id','=','users.DesignationId')
            ->leftJoin('timekeepings','timekeepings.UserId','=','users.Id')
            ->leftJoin('leave_requests', function($join){
                $join->on('leave_requests.UserId', '=', 'users.Id');
                $join->where('leave_requests.Status','=',2);
            })
            ->where('users.Status',1)
            
            ->groupBy('users.Id')
            ->get(['users.*','designations.Name AS DesignationName',
            DB::raw('SUM(timekeepings.TotalHours) as TotalSumHours, SUM(leave_requests.LeaveDuration) as TotalLeaveDuration')]);
        }

       

        $data=[];
        foreach($users as $user){

            $forecastedAnnualHours = $CompanySetting->AnnualWorkingHours - ($user->TotalLeaveDuration*8);

            $temp=[
                'FullName' =>$user->FirstName.' '.$user->LastName,
                'TotalSumHours' => $user->TotalSumHours,
                'DesignationName' =>$user->DesignationName,
                'forecastedAnnualHours' => $forecastedAnnualHours
            ];
            $data[]=$temp;
        }
        
        return $data;

    }

    function getEmployeeHours($EmployeeId=''){
        if($EmployeeId==''){
            $timeKeepingData = Timekeeping::select('timekeepings.*','U.FirstName','U.LastName',
            DB::raw('SUM(timekeepings.TotalHours) as TotalSumHours'),
            DB::raw('(
                SELECT SUM(tasks.Manhour)
                FROM tasks
            WHERE u.Id = tasks.UserId
            ) AS TotalBudgetedHours'),
            'D.Name AS DesignationName')
            ->leftJoin('users as U','U.Id','=','timekeepings.UserId')
            ->leftJoin('designations as D','D.Id','=','U.DesignationId')
            ->groupBy('timekeepings.UserId')
            ->get();

            return $timeKeepingData;
        } else{
            $timeKeepingData = Timekeeping::select('timekeepings.*','U.FirstName','U.LastName',
            DB::raw('SUM(timekeepings.TotalHours) as TotalSumHours'),'D.Name AS DesignationName')
            ->where('timekeepings.UserId',$EmployeeId)
            ->leftJoin('users as U','U.Id','=','timekeepings.UserId')
            ->leftJoin('designations as D','D.Id','=','U.DesignationId')
            ->groupBy('timekeepings.UserId')
            ->get();

            return $timeKeepingData;
        }
    }

    function filterUtilization(Request $request, $type){
        if($type==='DAILY'){
            return '
        <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2" style="vertical-align : middle;text-align:center;">Resource</th>
                <th colspan="2" class="text-center">MONDAY</th>
            </tr>
            <tr>
                <th class="text-center">Budgeted Hours</th>
                <th class="text-center">Used Hours</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Arjay Diangzon</td>
                <td class="text-center">160</td>
                <td class="text-center">8</td>
            </tr>
        </tbody>
    </table>
        ';
        }
        else if($type==='WEEKLY'){
            return '
        <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2" style="vertical-align : middle;text-align:center;">Resource</th>
                <th colspan="2" class="text-center">MONDAY</th>
                <th colspan="2" class="text-center">TUESDAY</th>
                <th colspan="2" class="text-center">WEDNESDAY</th>
                <th colspan="2" class="text-center">THURSDAY</th>
                <th colspan="2" class="text-center">FRIDAY</th>
            </tr>
            <tr>
                <th class="text-center">Budgeted Hours</th>
                <th class="text-center">Used Hours</th>
                <th class="text-center">Budgeted Hours</th>
                <th class="text-center">Used Hours</th>
                <th class="text-center">Budgeted Hours</th>
                <th class="text-center">Used Hours</th>
                <th class="text-center">Budgeted Hours</th>
                <th class="text-center">Used Hours</th>
                <th class="text-center">Budgeted Hours</th>
                <th class="text-center">Used Hours</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Arjay Diangzon</td>
                <td class="text-center">160</td>
                <td class="text-center">8</td>
              
                <td class="text-center">160</td>
                <td class="text-center">8</td>
                
                <td class="text-center">160</td>
                <td class="text-center">8</td>
               
                <td class="text-center">160</td>
                <td class="text-center">8</td>
                
                <td class="text-center">160</td>
                <td class="text-center">8</td>
            </tr>
        </tbody>
    </table>
        ';
        }
        else if($type==='MONTHLY'){
            return "
        <table class='table table-bordered table-striped table-hover'>
        <thead>
            <tr>
                <th colspan='100%' style='vertical-align : middle;text-align:center;'>
                    <h3>January</h3>
                </th>
            </tr>
            <tr>
                <th rowspan='2'>#</th>
                <th rowspan='2' style='vertical-align : middle;text-align:center;'>Resource</th>
                <th colspan='2' class='text-center'>Project Eagle</th>
                <th colspan='2' class='text-center'>Carmen's Best</th>
            </tr>
            <tr>
                <th class='text-center'>Used Hours</th>
                <th class='text-center'>Utilization</th>
                <th class='text-center'>Used Hours</th>
                <th class='text-center'>Utilization</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Arjay Diangzon</td>
                <td class='text-center'>160</td>
                <td class='text-center'>100%</td>
                <td class='text-center'>160</td>
                <td class='text-center'>100%</td>
            </tr>
        </tbody>
    </table>
        ";
        }
        else if($type==='YEARLY'){
            return '
        <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2" style="vertical-align : middle;text-align:center;">Resource</th>
                <th colspan="2" class="text-center">2022</th>
            </tr>
            <tr>
                <th class="text-center">Used Hours</th>
                <th class="text-center">Utilization</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Arjay Diangzon</td>
                <td class="text-center">160</td>
                <td class="text-center">100%</td>
            </tr>
        </tbody>
    </table>
        ';
        }
    }



    
}
