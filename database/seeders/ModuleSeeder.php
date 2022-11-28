<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $UserId = Str::uuid();

        $data = [
            [
                'id'        => 1,
                'ParentId'  => null,
                'Title'     => 'Dashboard',
                'WithApproval' => false,
                'Icon'      => 'dashboard.png',
                'RouteName' => 'dashboard',
                'Prefix'    => 'dashboard',
                'Status'    => 'Active',
                'SortOrder' => 1,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 2,
                'ParentId'  => null,
                'Title'     => 'Employee Directory',
                'WithApproval' => false,
                'Icon'      => 'employeeDirectory.png',
                'RouteName' => 'employeeDirectory',
                'Prefix'    => 'employeeDirectory',
                'Status'    => 'Active',
                'SortOrder' => 2,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 3,
                'ParentId'  => null,
                'Title'     => 'Forms',
                'WithApproval' => false,
                'Icon'      => 'form.png',
                'RouteName' => null,
                'Prefix'    => 'forms',
                'Status'    => 'Active',
                'SortOrder' => 3,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 4,
                'ParentId'  => 3,
                'Title'     => 'Leave Request',
                'WithApproval' => true,
                'Icon'      => 'default.png',
                'RouteName' => 'leaveRequest',
                'Prefix'    => 'leaveRequest',
                'Status'    => 'Active',
                'SortOrder' => 1,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 5,
                'ParentId'  => 3,
                'Title'     => 'Overtime Request',
                'WithApproval' => true,
                'Icon'      => 'default.png',
                'RouteName' => 'overtimeRequest',
                'Prefix'    => 'overtimeRequest',
                'Status'    => 'Active',
                'SortOrder' => 2,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 6,
                'ParentId'  => null,
                'Title'     => 'Onboarding',
                'WithApproval' => false,
                'Icon'      => 'onboarding.png',
                'RouteName' => null,
                'Prefix'    => 'onboarding',
                'Status'    => 'Active',
                'SortOrder' => 4,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 7,
                'ParentId'  => null,
                'Title'     => 'List of KPIs',
                'WithApproval' => false,
                'Icon'      => 'kpi.png',
                'RouteName' => null,
                'Prefix'    => 'kpi',
                'Status'    => 'Active',
                'SortOrder' => 5,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 8,
                'ParentId'  => null,
                'Title'     => 'Project',
                'WithApproval' => false,
                'Icon'      => 'project.png',
                'RouteName' => null,
                'Prefix'    => 'projects',
                'Status'    => 'Active',
                'SortOrder' => 6,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 9,
                'ParentId'  => 8,
                'Title'     => 'List of Project',
                'WithApproval' => false,
                'Icon'      => 'default.png',
                'RouteName' => 'projects.view',
                'Prefix'    => 'projectView',
                'Status'    => 'Active',
                'SortOrder' => 1,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 10,
                'ParentId'  => null,
                'Title'     => 'Training',
                'WithApproval' => false,
                'Icon'      => 'training.png',
                'RouteName' => null,
                'Prefix'    => 'training',
                'Status'    => 'Active',
                'SortOrder' => 7,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 11,
                'ParentId'  => null,
                'Title'     => 'Certification',
                'WithApproval' => false,
                'Icon'      => 'certification.png',
                'RouteName' => null,
                'Prefix'    => 'certification',
                'Status'    => 'Active',
                'SortOrder' => 8,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
            [
                'id'        => 12,
                'ParentId'  => null,
                'Title'     => 'Reports',
                'WithApproval' => false,
                'Icon'      => 'report.png',
                'RouteName' => null,
                'Prefix'    => 'report',
                'Status'    => 'Active',
                'SortOrder' => 9,
                'Created_By_Id' => $UserId,
                'Updated_By_Id' => $UserId
            ],
        ];

        Module::insert($data);
        DB::select("SELECT 
            setval(pg_get_serial_sequence('modules', 'id'), 
            max(id)) 
        FROM modules");
    }
}
