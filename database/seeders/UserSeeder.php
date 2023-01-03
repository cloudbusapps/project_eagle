<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Str;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // USERS
        $AdminId      = config('constant.ID.USERS.ADMIN');
        $BAHeadId     = config('constant.ID.USERS.BA_HEAD');
        $AlvinAgatoId = config('constant.ID.USERS.ALVIN_AGATO');

        // DEPARTMENT
        $AdminDepartmentId = config('constant.ID.DEPARTMENTS.INFORMATION_TECHNOLOGY');
        $CloudDepartmentId = config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION');
        $TCDepartmentId    = config('constant.ID.DEPARTMENTS.TECHNOLOGY_CONSULTING');

        // DESIGNATION
        $AdminDesignationId = config('constant.ID.DESIGNATIONS.ADMINISTRATOR');
        $PMDesignationId    = config('constant.ID.DESIGNATIONS.PROJECT_MANAGER');
        $GMDesignationId    = config('constant.ID.DESIGNATIONS.BA_HEAD');
        $BADesignationId    = config('constant.ID.DESIGNATIONS.BUSINESS_ANALYST');
        $TCDesignationId    = config('constant.ID.DESIGNATIONS.TECHNICAL_CONSULTANT');
        $FCDesignationId    = config('constant.ID.DESIGNATIONS.FUNCTIONAL_CONSULTANT');
        $CMDesignationId    = config('constant.ID.DESIGNATIONS.CUSTOMER_MANAGER');
 
        DB::table('users')->insert([
            [
                'Id'                => $AdminId,
                'EmployeeNumber'    => 'EPLDT-000001',
                'FirstName'         => 'Project',
                'LastName'          => 'Eagle',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $AdminDepartmentId,
                'DesignationId'     => $AdminDesignationId,
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
                'Id'                => $AlvinAgatoId,
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
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000006',
                'FirstName'         => 'Mariciel',
                'LastName'          => 'Tubale',
                'Gender'            => 'Female',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $BADesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'maricieltubale@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('maricieltubale'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000007',
                'FirstName'         => 'Edelyn',
                'LastName'          => 'Tanquerido',
                'Gender'            => 'Female',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $FCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'edelyntanquerido@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('edelyntanquerido'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000008',
                'FirstName'         => 'Mark Ray',
                'LastName'          => 'Beguas',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $FCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'markraybeguas@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('markraybeguas'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000009',
                'FirstName'         => 'Janus Jade',
                'LastName'          => 'Basa',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $FCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'janusjadebasa@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('janusjadebasa'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000010',
                'FirstName'         => 'Marlon',
                'LastName'          => 'Timbas',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $TCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'marlontimbas@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('marlontimbas'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000012',
                'FirstName'         => 'Gerard Arbill',
                'LastName'          => 'Hernandez',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $TCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'gerardarbillhernandez@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('gerardarbillhernandez'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000013',
                'FirstName'         => 'Nasario',
                'LastName'          => 'Tolabing',
                'Gender'            => 'Male',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $TCDesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'nasariotolabing@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('nasariotolabing'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
            [
                'Id'                => Str::uuid(),
                'EmployeeNumber'    => 'EPLDT-000014',
                'FirstName'         => 'Esmeralda',
                'LastName'          => 'Canoog',
                'Gender'            => 'Female',
                'Address'           => 'Makati City, Philippines',
                'ContactNumber'     => fake()->phoneNumber(),
                'DepartmentId'      => $CloudDepartmentId,
                'DesignationId'     => $BADesignationId,
                'About'             => fake()->paragraph(3),
                'email'             => 'esmeraldacanoog@epldt.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('esmeraldacanoog'),
                'IsAdmin'           => false,
                'Status'            => 1,
            ],
        ]);
    }
}
