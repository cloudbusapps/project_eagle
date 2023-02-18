<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        DB::table('designations')->insert([
            [
                'Id'               => $AdminDesignationId,
                'DepartmentId'     => $AdminDepartmentId,
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
    }
}
