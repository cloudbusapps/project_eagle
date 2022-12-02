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

        $CloudDepartmentId = config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION');
        $TCDepartmentId    = config('constant.ID.DEPARTMENTS.TECHNOLOGY_CONSULTING');
        $TCDesignationId   = Str::uuid();
        $FCDesignationId   = Str::uuid();
        $GMDesignationId   = Str::uuid();

        // ----- DEPARTMENT -----
        DB::table('departments')->insert([
            [
                'Id'     => $DepartmentId,
                'Name'   => 'Information Technology',
                'Status' => 1
            ],
            [
                'Id'     => $CloudDepartmentId,
                'Name'   => 'Cloud Business Applications',
                'Status' => 1
            ],
            [
                'Id'     => $TCDepartmentId,
                'Name'   => 'Technology Consulting',
                'Status' => 1
            ]
        ]);
        // ----- END DEPARTMENT -----


        // ----- DESIGNATION -----
        DB::table('designations')->insert([
            [
                'Id'           => $DesignationId,
                'DepartmentId' => $DepartmentId,
                'Name'         => 'Administrator',
                'Status'       => 1
            ],
            [
                'Id'           => $TCDesignationId,
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'Technical Consultant',
                'Status'       => 1
            ],
            [
                'Id'           => $GMDesignationId,
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'General Manager - Cloud Applications',
                'Status'       => 1
            ],
            [
                'Id'           => $FCDesignationId,
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'Functional Consultant',
                'Status'       => 1
            ],
            [
                'Id'           => Str::uuid(),
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'Business Analyst',
                'Status'       => 1
            ],
        ]);
        // ----- END DESIGNATION -----


        // ----- USERS -----
        DB::table('users')->insert([
            [
                'Id'                => $UserId,
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
                'Id'            => Str::uuid(),
                'Name'          => 'Vacation Leave',
                'Status'        => 1,
                'CreatedById' => $UserId,
                'UpdatedById' => $UserId,
            ],
            [
                'Id'            => Str::uuid(),
                'Name'          => 'Sick Leave',
                'Status'        => 1,
                'CreatedById' => $UserId,
                'UpdatedById' => $UserId,
            ],
        ]);
        // ----- END LEAVE TYPE -----
    }
}
