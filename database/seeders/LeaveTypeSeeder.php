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
                'Acronym'     => 'VL',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => $SickLeaveId,
                'Name'        => 'Sick Leave',
                'Acronym'     => 'SL',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Offset Leave',
                'Acronym'     => 'OL',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Emergency Leave',
                'Acronym'     => 'EL',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Paternity Leave',
                'Acronym'     => 'PL',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Maternity Leave',
                'Acronym'     => 'ML',
                'Status'      => 1,
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
        ]);
    }
}
