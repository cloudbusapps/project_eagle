<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\Models\User;
use App\Models\UserCertification;
use App\Models\UserSkill;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EmployeeDirectoryController extends Controller
{
    public function index(Request $request) {
        $filterBy = $request->filterBy ?? "All";
        $searchFilter = $request->search ?? '';
        $search = strtolower($request->search);
        
        $filterData = [
            'Employee Name' => DB::table('users')->select(DB::raw('CONCAT("FirstName", \' \', "LastName") AS optval'))->orderBy('FirstName', 'ASC')->get(),
            'Title'         => User::select('Title AS optval')->distinct('Title')->get(),
            'Certification' => UserCertification::select('Code AS optval')->distinct('Code')->get(),
            'Skill'         => UserSkill::select('Title AS optval')->distinct('Title')->get(),
        ];
        $searchData = ($filterBy && $filterBy != "All") ? $filterData[$filterBy] : [];
        
        if ($filterBy == "Employee Name") {
            $userData = User::where(DB::raw('LOWER(CONCAT("FirstName", \' \', "LastName"))'), 'LIKE', "%{$search}%")->orderBy('users.FirstName')
            ->get();
        } else if ($filterBy == "Title") {
            $userData = User::where(DB::raw('LOWER("Title")'), 'LIKE', "%{$search}%")->orderBy('users.FirstName')
            ->get();
        } else if ($filterBy == "Certification") {
            $userData = User::select('users.*')
                ->leftJoin('user_certifications AS uc', 'UserId', 'users.Id')
                ->where(DB::raw('LOWER("uc"."Code")'), 'LIKE', "%{$search}%")
                ->groupBy('users.Id')
                ->orderBy('users.FirstName')
                ->get();
        } else if ($filterBy == "Skill") {
            $userData = User::select('users.*')
                ->leftJoin('user_skills AS us', 'UserId', 'users.Id')
                ->where(DB::raw('LOWER("us"."Title")'), 'LIKE', "%{$search}%")
                ->groupBy('users.Id')
                ->orderBy('users.FirstName')
                ->get();
        } else {
            if ($search) {
                $userData = User::select('users.*')
                    ->leftJoin('user_skills AS us', 'us.UserId', 'users.Id')
                    ->leftJoin('user_certifications AS uc', 'uc.UserId', 'users.Id')
                    ->where(DB::raw('LOWER("us"."Title")'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('LOWER("uc"."Code")'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('LOWER("FirstName")'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('LOWER("LastName")'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('LOWER("users"."Title")'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('LOWER("users"."EmployeeNumber")'), 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw('LOWER("users"."email")'), 'LIKE', "%{$search}%")
                    ->groupBy('users.Id')
                    ->orderBy('users.FirstName')
                    ->get();
            } else {
                $userData = User::orderBy('users.FirstName')->get();
            }
        }

        $data = [
            'title'      => 'Employee Directory',
            'users'      => $userData,
            "filterBy"   => $filterBy,
            'filterData' => $filterData,
            "search"     => $searchFilter,
            "searchData" => $searchData
        ];

        return view('employeeDirectory.index', $data);
    }

    public function viewUserProfile($Id) {
        $data = [
            'title'          => 'Profile',
            'userData'       => User::where('Id', $Id)->first(),
            'certifications' => UserCertification::where('UserId', $Id)->get(),
            'skills'         => UserSkill::where('UserId', $Id)->get(),
        ];

        return view('employeeDirectory.profile', $data);
    }

}
