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

        $this->call(ModuleSeeder::class);
    }
}
