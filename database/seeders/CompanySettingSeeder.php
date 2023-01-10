<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Str;
use DB;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $WeeksPerYear       = 52;
        $HoursPerDay        = 8;
        $HoursPerWeek       = 40;
        $PTO                = 34; 
        $PaidHoliday        = 7; 
        $AnnualWorkingHours = ($HoursPerWeek*$WeeksPerYear)-($PTO+$PaidHoliday)* $HoursPerDay;

        DB::table('company_settings')->insert([
            [
                'Id'                    => Str::uuid(),
                'HoursPerDay'           => $HoursPerDay,
                'HoursPerWeek'          => $HoursPerWeek,
                'PTO'                   => $PTO,
                'PaidHoliday'           => $PaidHoliday,
                'AnnualWorkingHours'    => $AnnualWorkingHours,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]
        ]);
    }
}
