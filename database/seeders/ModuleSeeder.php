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
            // ----- EMPLOYEE PROFILE -----
            [
                'id'            => 1,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Dashboard',
                'WithApproval'  => false,
                'Icon'          => 'dashboard.png',
                'RouteName'     => 'dashboard',
                'Prefix'        => 'dashboard',
                'Status'        => 1,
                'SortOrder'     => 1,
            ],
            [
                'id'            => 2,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Onboarding Procedures',
                'WithApproval'  => false,
                'Icon'          => 'onboarding.png',
                'RouteName'     => null,
                'Prefix'        => 'onboardingProcedure',
                'Status'        => 1,
                'SortOrder'     => 2,
            ],
            [
                'id'            => 3,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Directory',
                'WithApproval'  => false,
                'Icon'          => 'employeeDirectory.png',
                'RouteName'     => 'employeeDirectory',
                'Prefix'        => 'employeeDirectory',
                'Status'        => 1,
                'SortOrder'     => 3,
            ],
            [
                'id'            => 4,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Leave',
                'WithApproval'  => true,
                'Icon'          => 'form.png',
                'RouteName'     => 'leaveRequest',
                'Prefix'        => 'leaveRequest',
                'Status'        => 1,
                'SortOrder'     => 4,
            ],
            [
                'id'            => 5,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Evaluation',
                'WithApproval'  => false,
                'Icon'          => 'evaluation.png',
                'RouteName'     => null,
                'Prefix'        => 'evaluation',
                'Status'        => 1,
                'SortOrder'     => 5,
            ],
            [
                'id'            => 6,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Trainings',
                'WithApproval'  => false,
                'Icon'          => 'training.png',
                'RouteName'     => null,
                'Prefix'        => 'training',
                'Status'        => 1,
                'SortOrder'     => 6,
            ],
            [
                'id'            => 7,
                'CategoryId'    => 1,
                'ParentId'      => null,
                'Title'         => 'Certifications',
                'WithApproval'  => false,
                'Icon'          => 'certification.png',
                'RouteName'     => null,
                'Prefix'        => 'certification',
                'Status'        => 1,
                'SortOrder'     => 7,
            ],
            // ----- END EMPLOYEE PROFILE -----


            // ----- UTILIZATION -----
            [
                'id'            => 8,
                'CategoryId'    => 2,
                'ParentId'      => null,
                'Title'         => 'Project Utilization',
                'WithApproval'  => false,
                'Icon'          => 'project.png',
                'RouteName'     => null,
                'Prefix'        => 'projectUtilization',
                'Status'        => 1,
                'SortOrder'     => 2,
            ],
            [
                'id'            => 9,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Projects',
                'WithApproval'  => false,
                'Icon'          => 'project.png',
                'RouteName'     => null,
                'Prefix'        => 'projects',
                'Status'        => 1,
                'SortOrder'     => 1,
            ],
            [
                'id'            => 10,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Resources',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => null,
                'Prefix'        => 'resources',
                'Status'        => 1,
                'SortOrder'     => 2,
            ],
            [
                'id'            => 11,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Components',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => null,
                'Prefix'        => 'components',
                'Status'        => 1,
                'SortOrder'     => 3,
            ],
            [
                'id'            => 12,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Budget',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => null,
                'Prefix'        => 'budget',
                'Status'        => 1,
                'SortOrder'     => 4,
            ],
            [
                'id'            => 13,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Overtime',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => 'overtimeRequest',
                'Prefix'        => 'overtimeRequest',
                'Status'        => 1,
                'SortOrder'     => 5,
            ],
            [
                'id'            => 14,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Timekeeping',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => null,
                'Prefix'        => 'timekeeping',
                'Status'        => 1,
                'SortOrder'     => 6,
            ],
            [
                'id'            => 15,
                'CategoryId'    => 2,
                'ParentId'      => null,
                'Title'         => 'Dashboard',
                'WithApproval'  => false,
                'Icon'          => 'dashboard.png',
                'RouteName'     => null,
                'Prefix'        => 'projectDashboard',
                'Status'        => 1,
                'SortOrder'     => 1,
            ],
            // ----- END UTILIZATION -----


            // ----- REPORTS AND DASHBOARDS -----
            [
                'id'            => 16,
                'CategoryId'    => 3,
                'ParentId'      => null,
                'Title'         => 'Dashboard',
                'WithApproval'  => false,
                'Icon'          => 'dashboard.png',
                'RouteName'     => null,
                'Prefix'        => 'reportDashboard',
                'Status'        => 1,
                'SortOrder'     => 1,
            ],
            [
                'id'            => 17,
                'CategoryId'    => 3,
                'ParentId'      => null,
                'Title'         => 'Reports',
                'WithApproval'  => false,
                'Icon'          => 'report.png',
                'RouteName'     => null,
                'Prefix'        => 'reports',
                'Status'        => 1,
                'SortOrder'     => 2,
            ],
            // ----- END REPORTS AND DASHBOARDS -----




            // [
            //     'id'        => 2,
            //     'ParentId'  => null,
            //     'Title'     => 'Employee Directory',
            //     'WithApproval' => false,
            //     'Icon'      => 'employeeDirectory.png',
            //     'RouteName' => 'employeeDirectory',
            //     'Prefix'    => 'employeeDirectory',
            //     'Status'    => 1,
            //     'SortOrder' => 2,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 3,
            //     'ParentId'  => null,
            //     'Title'     => 'Forms',
            //     'WithApproval' => false,
            //     'Icon'      => 'form.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'forms',
            //     'Status'    => 1,
            //     'SortOrder' => 3,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 4,
            //     'ParentId'  => 3,
            //     'Title'     => 'Leave Request',
            //     'WithApproval' => true,
            //     'Icon'      => 'default.png',
            //     'RouteName' => 'leaveRequest',
            //     'Prefix'    => 'leaveRequest',
            //     'Status'    => 1,
            //     'SortOrder' => 1,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 5,
            //     'ParentId'  => 3,
            //     'Title'     => 'Overtime Request',
            //     'WithApproval' => true,
            //     'Icon'      => 'default.png',
            //     'RouteName' => 'overtimeRequest',
            //     'Prefix'    => 'overtimeRequest',
            //     'Status'    => 1,
            //     'SortOrder' => 2,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 6,
            //     'ParentId'  => null,
            //     'Title'     => 'Onboarding',
            //     'WithApproval' => false,
            //     'Icon'      => 'onboarding.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'onboarding',
            //     'Status'    => 1,
            //     'SortOrder' => 4,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 7,
            //     'ParentId'  => null,
            //     'Title'     => 'List of KPIs',
            //     'WithApproval' => false,
            //     'Icon'      => 'kpi.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'kpi',
            //     'Status'    => 1,
            //     'SortOrder' => 5,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 8,
            //     'ParentId'  => null,
            //     'Title'     => 'Project',
            //     'WithApproval' => false,
            //     'Icon'      => 'project.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'projects',
            //     'Status'    => 1,
            //     'SortOrder' => 6,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 9,
            //     'ParentId'  => 8,
            //     'Title'     => 'List of Project',
            //     'WithApproval' => false,
            //     'Icon'      => 'default.png',
            //     'RouteName' => 'projects.view',
            //     'Prefix'    => 'projectView',
            //     'Status'    => 1,
            //     'SortOrder' => 1,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 10,
            //     'ParentId'  => null,
            //     'Title'     => 'Training',
            //     'WithApproval' => false,
            //     'Icon'      => 'training.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'training',
            //     'Status'    => 1,
            //     'SortOrder' => 7,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 11,
            //     'ParentId'  => null,
            //     'Title'     => 'Certification',
            //     'WithApproval' => false,
            //     'Icon'      => 'certification.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'certification',
            //     'Status'    => 1,
            //     'SortOrder' => 8,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
            // [
            //     'id'        => 12,
            //     'ParentId'  => null,
            //     'Title'     => 'Reports',
            //     'WithApproval' => false,
            //     'Icon'      => 'report.png',
            //     'RouteName' => null,
            //     'Prefix'    => 'report',
            //     'Status'    => 1,
            //     'SortOrder' => 9,
            //     'Created_By_Id' => $UserId,
            //     'Updated_By_Id' => $UserId
            // ],
        ];

        Module::insert($data);
        DB::select("SELECT 
            setval(pg_get_serial_sequence('modules', 'id'), 
            max(id)) 
        FROM modules");
    }
}
