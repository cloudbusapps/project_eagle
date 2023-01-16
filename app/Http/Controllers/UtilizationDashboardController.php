<?php

namespace App\Http\Controllers;

use App\Models\timekeeping\Timekeeping;
use App\Models\timekeeping\TimekeepingDetails;
use Illuminate\Http\Request;

// MODELS
use Spatie\Activitylog\Models\Activity;
use App\Models\Resource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UtilizationDashboardController extends Controller
{
    public function index() {
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
            'projects' => Project::all(),
            'users' => DB::table('users')->where('IsAdmin',false)->get(),
        ];
        return view('utilizationDashboard.index', $data);
    }

    // HELPERS FOR UTILIZATION DASHBOARD CONTROLLER

    public function getUserProject(){
        $projectResources= Resource::select('resources.*','projects.Name AS ProjectName')
        ->leftJoin('projects','resources.ProjectId','projects.Id')
        ->get();
        $users = User::where('IsAdmin',false)->get();
        $data=[];
        foreach($users as $user){
            $temp=[
                'Id' => $user->Id,
                'FirstName' => $user->FirstName,
                'LastName'  => $user->LastName,
                'FullName'  => $user->FirstName.' '.$user->LastName,
                'ProjectsId'  => []
            ];
            foreach($projectResources as $projectResource){
                if($projectResource->UserId===$user->Id){
                    $temp['ProjectsId'][]=$projectResource->ProjectId;
                }
            }
            $data[]=$temp;
        }
        return $data;

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
