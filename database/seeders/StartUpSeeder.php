<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\admin\Department;
use App\Models\admin\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\admin\Module;
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
        // USERS
        $AdminId  = config('constant.ID.USERS.ADMIN');
        $BAHeadId = config('constant.ID.USERS.BA_HEAD');

        // ADMIN
        $DepartmentId  = Str::uuid();
        $DesignationId = Str::uuid();

        // DEPARTMENT
        $CloudDepartmentId = config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION');
        $TCDepartmentId    = config('constant.ID.DEPARTMENTS.TECHNOLOGY_CONSULTING');

        // DESIGNATION
        $PMDesignationId   = config('constant.ID.DESIGNATIONS.PROJECT_MANAGER');
        $GMDesignationId   = config('constant.ID.DESIGNATIONS.BA_HEAD');
        $BADesignationId   = config('constant.ID.DESIGNATIONS.BUSINESS_ANALYST');
        $TCDesignationId   = config('constant.ID.DESIGNATIONS.TECHNICAL_CONSULTANT');
        $FCDesignationId   = config('constant.ID.DESIGNATIONS.FUNCTIONAL_CONSULTANT');
        $CMDesignationId   = config('constant.ID.DESIGNATIONS.CUSTOMER_MANAGER');

        // LEAVE TYPES
        $SickLeaveId     = config('constant.ID.LEAVE_TYPES.SICK_LEAVE');
        $VacationLeaveId = config('constant.ID.LEAVE_TYPES.VACATION_LEAVE');

        // ----- DEPARTMENT -----
        DB::table('departments')->insert([
            [
                'Id'          => $DepartmentId,
                'Name'        => 'Information Technology',
                'UserId'      => null,
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => $CloudDepartmentId,
                'Name'        => 'Cloud Business Applications',
                'UserId'      => $BAHeadId,
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => $TCDepartmentId,
                'Name'        => 'Technology Consulting',
                'UserId'      => null,
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ]
        ]);
        // ----- END DEPARTMENT -----


        // ----- DESIGNATION -----
        DB::table('designations')->insert([
            [
                'Id'               => $DesignationId,
                'DepartmentId'     => $DepartmentId,
                'Name'             => 'Administrator',
                'Initial'          => 'ADM',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
            [
                'Id'               => $TCDesignationId,
                'DepartmentId'     => $CloudDepartmentId,
                'Name'             => 'Technical Consultant',
                'Initial'          => 'TC',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
            [
                'Id'               => $GMDesignationId,
                'DepartmentId'     => $CloudDepartmentId,
                'Name'             => 'General Manager - Cloud Applications',
                'Initial'          => 'BA-Head',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
            [
                'Id'               => $FCDesignationId,
                'DepartmentId'     => $CloudDepartmentId,
                'Name'             => 'Functional Consultant',
                'Initial'          => 'FC',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
            [
                'Id'               => $BADesignationId,
                'DepartmentId'     => $CloudDepartmentId,
                'Name'             => 'Business Analyst',
                'Initial'          => 'BA',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
            [
                'Id'               => $PMDesignationId,
                'DepartmentId'     => $TCDepartmentId,
                'Name'             => 'Project Manager',
                'Initial'          => 'PM',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
            [
                'Id'               => $CMDesignationId,
                'DepartmentId'     => $TCDepartmentId,
                'Name'             => 'Customer Manager',
                'Initial'          => 'CM',
                'BeginnerRate'     => 10,
                'IntermediateRate' => 20,
                'SeniorRate'       => 30,
                'ExpertRate'       => 60,
                'DefaultRate'      => 30,
                'Status'           => 1
            ],
        ]);
        // ----- END DESIGNATION -----


        // ----- USERS -----
        DB::table('users')->insert([
            [
                'Id'                => $AdminId,
                'EmployeeNumber'    => 'EPLDT-000001',
                'FirstName'         => 'Project',
                'LastName'          => 'Eagle',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $DepartmentId,
                'DesignationId'     => $DesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'projecteagle@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('projecteagle'),
                'IsAdmin'           => true,
                'Status'            => 1,
            ],
            [
                'Id'                => $BAHeadId,
                'EmployeeNumber'    => 'EPLDT-000002',
                'FirstName'         => 'Monica',
                'LastName'          => 'Borje',
                'Gender'            => 'Female',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $GMDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'monicaborje@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('monicaborje'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000003',
                'FirstName'         => 'Alvin',
                'LastName'          => 'Agato',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $FCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'alvinagato@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('alvinagato'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000004',
                'FirstName'         => 'Arjay',
                'LastName'          => 'Diangzon',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $TCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'arjaydiangzon@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('arjaydiangzon'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000005',
                'FirstName'         => 'Hashim',
                'LastName'          => 'Mascara',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $TCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'hashimmascara@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('hashimmascara'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
        ]);
        // ----- END USERS -----


        // ----- MODULES -----
        $this->call(ModuleSeeder::class);
        // ----- END MODULES -----


        // ----- LEAVE TYPE -----
        DB::table('leave_types')->insert([
            [
                'Id'          => $VacationLeaveId,
                'Name'        => 'Vacation Leave',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => $SickLeaveId,
                'Name'        => 'Sick Leave',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Offset Leave',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Emergency Leave',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
        ]);
        // ----- END LEAVE TYPE -----


        // ----- COMPLEXITY -----
        $this->call(ComplexitySeeder::class);
        // ----- END COMPLEXITY -----


        // ----- PROJECT PHASES -----
        $this->call(ProjectPhaseSeeder::class);
        // ----- END PROJECT PHASES -----


        DB::table('third_parties')->insert([
            [
                'Id'          => config('constant.ID.THIRD_PARTIES.OTHERS'),
                'Name'        => 'Others',
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ]);
    }
}
