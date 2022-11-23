<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'EmployeeNumber'    => 'EPLDT-000001',
            'FirstName'         => 'Project',
            'LastName'          => 'Eagle',
            'Gender'            => 'Male',
            'Address'           => 'Makati City, Philippines',
            'ContactNumber'     => '09099054766',
            'Title'             => 'Administrator',
            'About'             => fake()->paragraph(3),
            'IsAdmin'           => true,
            'email'             => 'projecteagle@epldt.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('projecteagle')
        ]);
    }
}
