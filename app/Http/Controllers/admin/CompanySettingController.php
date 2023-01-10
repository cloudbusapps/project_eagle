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
}
