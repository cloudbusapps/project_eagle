<?php

namespace App\Http\Controllers;

use App\Models\timekeeping\Timekeeping;
use App\Models\timekeeping\TimekeepingDetails;
use Illuminate\Http\Request;

use Spatie\Activitylog\Models\Activity;
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
            ]
        ];
        return view('utilizationDashboard.index', $data);
    }

    function filter(Request $request, $type){
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
        else if($type==='MONTHLY'){
            return '
        <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2" style="vertical-align : middle;text-align:center;">Resource</th>
                <th colspan="2" class="text-center">January 2022</th>
                <th colspan="2" class="text-center">February 2022</th>
            </tr>
            <tr>
                <th class="text-center">Used Hours</th>
                <th class="text-center">Utilization</th>
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
                <td class="text-center">160</td>
                <td class="text-center">100%</td>
            </tr>
        </tbody>
    </table>
        ';
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
