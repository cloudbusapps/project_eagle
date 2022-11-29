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

        $CloudDepartmentId = Str::uuid();
        $TCDesignationId   = Str::uuid();
        $FCDesignationId   = Str::uuid();
        $GMDesignationId   = Str::uuid();

        DB::table('departments')->insert([
            [
                'Id'     => $DepartmentId,
                'Name'   => 'Information Technology',
                'Status' => 'Active'
            ],
            [
                'Id'     => $CloudDepartmentId,
                'Name'   => 'Cloud Business Applications',
                'Status' => 'Active'
            ]
        ]);

        DB::table('designations')->insert([
            [
                'Id'           => $DesignationId,
                'DepartmentId' => $DepartmentId,
                'Name'         => 'Administrator',
                'Status'       => 'Active'
            ],
            [
                'Id'           => $TCDesignationId,
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'Technical Consultant',
                'Status'       => 'Active'
            ],
            [
                'Id'           => $GMDesignationId,
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'General Manager - Cloud Applications',
                'Status'       => 'Active'
            ],
            [
                'Id'           => $FCDesignationId,
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'Functional Consultant',
                'Status'       => 'Active'
            ],
            [
                'Id'           => Str::uuid(),
                'DepartmentId' => $CloudDepartmentId,
                'Name'         => 'Business Analyst',
                'Status'       => 'Active'
            ],
        ]);

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
                'IsAdmin'           => true,
                'email'             => 'projecteagle@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('projecteagle')
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000002',
                'FirstName'         => 'Arjay',
                'LastName'          => 'Diangzon',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $TCDesignationId,
                'About'             => fake()->paragraph(3),
                'IsAdmin'           => false,
                'email'             => 'arjaydiangzon@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('arjaydiangzon')
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000003',
                'FirstName'         => 'Monica',
                'LastName'          => 'Borje',
                'Gender'            => 'Female',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $GMDesignationId,
                'About'             => fake()->paragraph(3),
                'IsAdmin'           => false,
                'email'             => 'monicaborje@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('monicaborje')
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000004',
                'FirstName'         => 'Alvin',
                'LastName'          => 'Agato',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $FCDesignationId,
                'About'             => fake()->paragraph(3),
                'IsAdmin'           => false,
                'email'             => 'alvinagato@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('alvinagato')
            ],
        ]);

        $this->call(ModuleSeeder::class);
    }
}
