<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\admin\Module;
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
                'Prefix'        => 'directory',
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
                'Prefix'        => 'leave',
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
                'SortOrder'     => 3,
            ],
            [
                'id'            => 9,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Projects',
                'WithApproval'  => false,
                'Icon'          => 'project.png',
                'RouteName'     => 'projects',
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
            [
                'id'            => 18,
                'CategoryId'    => 2,
                'ParentId'      => null,
                'Title'         => 'Customer',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => 'customers',
                'Prefix'        => 'customer',
                'Status'        => 1,
                'SortOrder'     => 2,
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
            
        ];

        Module::insert($data);
        DB::select("SELECT 
            setval(pg_get_serial_sequence('modules', 'id'), 
            max(id)) 
        FROM modules");
    }
}
