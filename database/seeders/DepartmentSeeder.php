<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class DepartmentSeeder extends Seeder
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
        
        // DEPARTMENT
        $AdminDepartmentId = config('constant.ID.DEPARTMENTS.INFORMATION_TECHNOLOGY');
        $CloudDepartmentId = config('constant.ID.DEPARTMENTS.CLOUD_BUSINESS_APPLICATION');
        $TCDepartmentId    = config('constant.ID.DEPARTMENTS.TECHNOLOGY_CONSULTING');

        DB::table('departments')->insert([
            [
                'Id'          => $AdminDepartmentId,
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
    }
}
