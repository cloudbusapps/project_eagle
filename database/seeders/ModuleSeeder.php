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
        Module::truncate();
        DB::table('module_table_related')->truncate();

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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => 'users',
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
            ],
            [
                'id'            => 14,
                'CategoryId'    => 2,
                'ParentId'      => 8,
                'Title'         => 'Timekeeping',
                'WithApproval'  => false,
                'Icon'          => 'default.png',
                'RouteName'     => 'timekeeping',
                'Prefix'        => 'timekeeping',
                'Status'        => 1,
                'SortOrder'     => 6,
                'TableName'     => 'timekeeping',
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
                'TableName'     => null,
            ],
            [
                'id'            => 18,
                'CategoryId'    => 2,
                'ParentId'      => null,
                'Title'         => 'Customer',
                'WithApproval'  => false,
                'Icon'          => 'customer.png',
                'RouteName'     => 'customers',
                'Prefix'        => 'customer',
                'Status'        => 1,
                'SortOrder'     => 2,
                'TableName'     => null,
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
                'TableName'     => null,
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
                'TableName'     => null,
            ],
            // ----- END REPORTS AND DASHBOARDS -----
            
        ];

        $relatedData = [
            // DIRECTORY | ID = 3
            [
                'ModuleId'  => 3,
                'Title'     => 'Leave Balance',
                'TableName' => 'user_leave_balances',
            ],
            [
                'ModuleId'  => 14, // TIMEKEEPING
                'Title'     => 'Details',
                'TableName' => 'timekeeping_details',
            ],
        ];

        Module::insert($data);
        DB::select("SELECT 
            setval(pg_get_serial_sequence('modules', 'id'), 
            max(id)) 
        FROM modules");

        DB::table('module_table_related')->insert($relatedData);
    }
}
