<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            ],
            [
                'id'        => 3,
                'ParentId'  => null,
                'Title'     => 'Forms',
                'WithApproval' => false,
                'Icon'      => 'form.png',
                'RouteName' => null,
                'Prefix'    => 'form',
                'Status'    => 'Active',
                'SortOrder' => 3,
            ],
            [
                'id'        => 4,
                'ParentId'  => 3,
                'Title'     => 'Leave Request',
                'WithApproval' => true,
                'Icon'      => 'default.png',
                'RouteName' => null,
                'Prefix'    => 'leaveRequest',
                'Status'    => 'Active',
                'SortOrder' => 1,
            ],
            [
                'id'        => 5,
                'ParentId'  => 3,
                'Title'     => 'Overtime Request',
                'WithApproval' => true,
                'Icon'      => 'default.png',
                'RouteName' => null,
                'Prefix'    => 'overtimeRequest',
                'Status'    => 'Active',
                'SortOrder' => 2,
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
            ],
        ];

        Module::insert($data);
        DB::select("SELECT 
            setval(pg_get_serial_sequence('modules', 'id'), 
            max(id)) 
        FROM modules");
    }
}
