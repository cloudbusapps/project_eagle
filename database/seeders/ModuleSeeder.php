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

        // MODULE 1
        $DASHBOARD1 = config('constant.ID.MODULES.MODULE_ONE.DASHBOARD');
        $ONBOARDING_PROCEDURES1 = config('constant.ID.MODULES.MODULE_ONE.ONBOARDING_PROCEDURES');
        $DIRECTORY1 = config('constant.ID.MODULES.MODULE_ONE.DIRECTORY');
        $LEAVE1 = config('constant.ID.MODULES.MODULE_ONE.LEAVE');
        $EVALUATIONS1 = config('constant.ID.MODULES.MODULE_ONE.EVALUATIONS');
        $TRAININGS1 = config('constant.ID.MODULES.MODULE_ONE.TRAININGS');
        $CERTIFICATIONS1 = config('constant.ID.MODULES.MODULE_ONE.CERTIFICATIONS');

        // MODULE 2
        $DASHBOARD2 = config('constant.ID.MODULES.MODULE_TWO.DASHBOARD');
        $OPPORTUNITY2 = config('constant.ID.MODULES.MODULE_TWO.OPPORTUNITY');
        $PROJECT_UTILIZATION2 = config('constant.ID.MODULES.MODULE_TWO.PROJECT_UTILIZATION');
        $PROJECTS2 = config('constant.ID.MODULES.MODULE_TWO.PROJECTS');
        $RESOURCES2 = config('constant.ID.MODULES.MODULE_TWO.RESOURCES');
        $COMPONENTS2 = config('constant.ID.MODULES.MODULE_TWO.COMPONENTS');
        $BUDGET2 = config('constant.ID.MODULES.MODULE_TWO.BUDGET');
        $OVERTIME2 = config('constant.ID.MODULES.MODULE_TWO.OVERTIME');
        $TIMEKEEPING2 = config('constant.ID.MODULES.MODULE_TWO.TIMEKEEPING');

        // MODULE 3
        $DASHBOARD3 = config('constant.ID.MODULES.MODULE_THREE.DASHBOARD');
        $REPORTS3 = config('constant.ID.MODULES.MODULE_THREE.REPORTS');

        $data = [
            // ----- EMPLOYEE PROFILE -----
                [
                    'id'            => $DASHBOARD1,
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
                    'id'            => $ONBOARDING_PROCEDURES1,
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
                    'id'            => $DIRECTORY1,
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
                    'id'            => $LEAVE1,
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
                    'id'            => $EVALUATIONS1,
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
                    'id'            => $TRAININGS1,
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
                    'id'            => $CERTIFICATIONS1,
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
                    'id'            => $DASHBOARD2,
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
                    'id'            => $OPPORTUNITY2,
                    'CategoryId'    => 2,
                    'ParentId'      => null,
                    'Title'         => 'Opportunity',
                    'WithApproval'  => false,
                    'Icon'          => 'customer.png',
                    'RouteName'     => 'customers',
                    'Prefix'        => 'opportunity',
                    'Status'        => 1,
                    'SortOrder'     => 2,
                    'TableName'     => null,
                ],
                [
                    'id'            => $PROJECT_UTILIZATION2,
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
                    'id'            => $PROJECTS2,
                    'CategoryId'    => 2,
                    'ParentId'      => $PROJECT_UTILIZATION2,
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
                    'id'            => $RESOURCES2,
                    'CategoryId'    => 2,
                    'ParentId'      => $PROJECT_UTILIZATION2,
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
                    'id'            => $COMPONENTS2,
                    'CategoryId'    => 2,
                    'ParentId'      => $PROJECT_UTILIZATION2,
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
                    'id'            => $BUDGET2,
                    'CategoryId'    => 2,
                    'ParentId'      => $PROJECT_UTILIZATION2,
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
                    'id'            => $OVERTIME2,
                    'CategoryId'    => 2,
                    'ParentId'      => $PROJECT_UTILIZATION2,
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
                    'id'            => $TIMEKEEPING2,
                    'CategoryId'    => 2,
                    'ParentId'      => $PROJECT_UTILIZATION2,
                    'Title'         => 'Timekeeping',
                    'WithApproval'  => false,
                    'Icon'          => 'default.png',
                    'RouteName'     => 'timekeeping',
                    'Prefix'        => 'timekeeping',
                    'Status'        => 1,
                    'SortOrder'     => 6,
                    'TableName'     => 'timekeeping',
                ],
            // ----- END UTILIZATION -----


            // ----- REPORTS AND DASHBOARDS -----
                [
                    'id'            => $DASHBOARD3,
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
                    'id'            => $REPORTS3,
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
                'ModuleId'  => $DIRECTORY1,
                'Title'     => 'Leave Balance',
                'TableName' => 'user_leave_balances',
            ],
            [
                'ModuleId'  => $TIMEKEEPING2, // TIMEKEEPING
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
