<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class LeaveTypeSeeder extends Seeder
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
        
        // LEAVE TYPES
        $SickLeaveId     = config('constant.ID.LEAVE_TYPES.SICK_LEAVE');
        $VacationLeaveId = config('constant.ID.LEAVE_TYPES.VACATION_LEAVE');

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
    }
}
