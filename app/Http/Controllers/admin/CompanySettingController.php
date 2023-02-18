<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin\CompanySetting;

class CompanySettingController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Company Setting',
            'data'  => CompanySetting::first(),
        ];
        return view('admin.companySettings.index', $data);
    }

    public function form() {
        $data = [
            'title'      => "New Complexity",
            'complexity' => ''
        ];
        return view('admin.companySettings.form', $data);
    }
    public function update(Request $request){
        $companySetting = CompanySetting::first();

        $WeeksPerYear = 52;

        $HoursPerDay    = $request->HoursPerDay;
        $HoursPerWeek   = $request->HoursPerWeek;
        $PTO            = $request->PTO;
        $PaidHoliday    = $request->PaidHoliday;

        $companySetting->HoursPerDay    = $HoursPerDay;
        $companySetting->HoursPerWeek   = $HoursPerWeek;
        $companySetting->PTO            = $PTO;
        $companySetting->PaidHoliday    = $PaidHoliday;
        
        $AnnualWorkingHours = ($HoursPerWeek*$WeeksPerYear)-($PTO+$PaidHoliday)* $HoursPerDay;
        $companySetting->AnnualWorkingHours  = $AnnualWorkingHours;

        if($companySetting->update()){
            return redirect()
            ->route('companySetting')
            ->with('success', "<b>Company Setting</b> successfully updated!");
        } else {
            return redirect()
            ->route('companySetting')
            ->with('fail', "Something went wrong, please try again");
        }

    }
}
