<?php

namespace App\Http\Controllers;

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
}
