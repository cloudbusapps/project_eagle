<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Str;
use DB;

class ApprovalSeeder extends Seeder
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

        // DESIGNATION
        $AdminDesignationId = config('constant.ID.DESIGNATIONS.ADMINISTRATOR');
        $PMDesignationId    = config('constant.ID.DESIGNATIONS.PROJECT_MANAGER');
        $GMDesignationId    = config('constant.ID.DESIGNATIONS.BA_HEAD');
        $BADesignationId    = config('constant.ID.DESIGNATIONS.BUSINESS_ANALYST');
        $TCDesignationId    = config('constant.ID.DESIGNATIONS.TECHNICAL_CONSULTANT');
        $FCDesignationId    = config('constant.ID.DESIGNATIONS.FUNCTIONAL_CONSULTANT');
        $CMDesignationId    = config('constant.ID.DESIGNATIONS.CUSTOMER_MANAGER');

        // MODULES
        $module1LeaveId = 4;
 
        DB::table('module_approvals')->insert([
            // BUSINESS ANALYST
            [
                'ModuleId'      => $module1LeaveId,
                'DesignationId' => $BADesignationId,
                'Level'         => 1,
                'ApproverId'    => $AlvinAgatoId,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'ModuleId'      => $module1LeaveId,
                'DesignationId' => $BADesignationId,
                'Level'         => 2,
                'ApproverId'    => $BAHeadId,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // FUNCTIONAL CONSULTANT
            [
                'ModuleId'      => $module1LeaveId,
                'DesignationId' => $FCDesignationId,
                'Level'         => 1,
                'ApproverId'    => $AlvinAgatoId,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'ModuleId'      => $module1LeaveId,
                'DesignationId' => $FCDesignationId,
                'Level'         => 2,
                'ApproverId'    => $BAHeadId,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            // TECHNICAL CONSULTANT
            [
                'ModuleId'      => $module1LeaveId,
                'DesignationId' => $TCDesignationId,
                'Level'         => 1,
                'ApproverId'    => $AlvinAgatoId,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'ModuleId'      => $module1LeaveId,
                'DesignationId' => $TCDesignationId,
                'Level'         => 2,
                'ApproverId'    => $BAHeadId,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
