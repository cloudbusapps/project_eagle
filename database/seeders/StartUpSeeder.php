<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Module;
use DB;

class StartUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $DepartmentId  = Str::uuid();
        $DesignationId = Str::uuid();
        $UserId        = Str::uuid();

        DB::table('departments')->insert([
            'Id'     => $DepartmentId,
            'Name'   => 'Information Technology',
            'Status' => 'Active'
        ]);

        DB::table('designations')->insert([
            'Id'           => $DesignationId,
            'DepartmentId' => $DepartmentId,
            'Name'         => 'Administrator',
            'Status'       => 'Active'
        ]);

        DB::table('users')->insert([
            'Id'                => $UserId,
            'EmployeeNumber'    => 'EPLDT-000001',
            'FirstName'         => 'Project',
            'LastName'          => 'Eagle',
            'Gender'            => 'Male',
            'Address'           => 'Makati City, Philippines',
            'ContactNumber'     => '09099054766',
            'Title'             => 'Administrator',
            'DepartmentId'      => $DepartmentId,
            'DesignationId'     => $DesignationId,
            'About'             => fake()->paragraph(3),
            'IsAdmin'           => true,
            'email'             => 'projecteagle@epldt.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('projecteagle')
        ]);

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
                'Prefix'    => 'form',
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
                'RouteName' => null,
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
                'RouteName' => null,
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
