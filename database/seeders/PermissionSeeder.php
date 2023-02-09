<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class PermissionSeeder extends Seeder
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
        
        // DESIGNATION
        $AdminDesignationId = config('constant.ID.DESIGNATIONS.ADMINISTRATOR');
        $PMDesignationId    = config('constant.ID.DESIGNATIONS.PROJECT_MANAGER');
        $GMDesignationId    = config('constant.ID.DESIGNATIONS.BA_HEAD');
        $BADesignationId    = config('constant.ID.DESIGNATIONS.BUSINESS_ANALYST');
        $TCDesignationId    = config('constant.ID.DESIGNATIONS.TECHNICAL_CONSULTANT');
        $FCDesignationId    = config('constant.ID.DESIGNATIONS.FUNCTIONAL_CONSULTANT');
        $CMDesignationId    = config('constant.ID.DESIGNATIONS.CUSTOMER_MANAGER');

        // MODULES
        $module1DashboardId = 1;
        $module1LeaveId     = 4;
        $module2CustomerId  = 18;
        $module2DashboardId  = 8;
        $modeule2TimekeepingId  = 16;

        $Designations = DB::table('designations')->get();
        foreach ($Designations as $dt) {
            DB::table('permissions')->insert([
                // BA HEAD
                [
                    'Id'            => Str::uuid(),
                    'DesignationId' => $dt->Id,
                    'ModuleId'      => $module1DashboardId, 
                    'Read'          => 1,
                    'Create'        => 1,
                    'Edit'          => 1,
                    'Delete'        => 1,
                    'CreatedById'   => $AdminId,
                    'UpdatedById'   => $AdminId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                [
                    'Id'            => Str::uuid(),
                    'DesignationId' => $dt->Id,
                    'ModuleId'      => $module1LeaveId, 
                    'Read'          => 1,
                    'Create'        => 1,
                    'Edit'          => 1,
                    'Delete'        => 1,
                    'CreatedById'   => $AdminId,
                    'UpdatedById'   => $AdminId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
            ]);
        }

        DB::table('permissions')->insert([
            [
                'Id'            => Str::uuid(),
                'DesignationId' => $GMDesignationId,
                'ModuleId'      => $module2CustomerId, 
                'Read'          => 1,
                'Create'        => 1,
                'Edit'          => 1,
                'Delete'        => 1,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'Id'            => Str::uuid(),
                'DesignationId' => $BADesignationId,
                'ModuleId'      => $module2CustomerId, 
                'Read'          => 1,
                'Create'        => 1,
                'Edit'          => 1,
                'Delete'        => 1,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'Id'            => Str::uuid(),
                'DesignationId' => $TCDesignationId,
                'ModuleId'      => $module2CustomerId, 
                'Read'          => 1,
                'Create'        => 1,
                'Edit'          => 1,
                'Delete'        => 1,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'Id'            => Str::uuid(),
                'DesignationId' => $FCDesignationId,
                'ModuleId'      => $module2CustomerId, 
                'Read'          => 1,
                'Create'        => 1,
                'Edit'          => 1,
                'Delete'        => 1,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'Id'            => Str::uuid(),
                'DesignationId' => $CMDesignationId,
                'ModuleId'      => $module2CustomerId, 
                'Read'          => 1,
                'Create'        => 1,
                'Edit'          => 1,
                'Delete'        => 1,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
