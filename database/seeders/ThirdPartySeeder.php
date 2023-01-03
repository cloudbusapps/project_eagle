<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class ThirdPartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('third_parties')->insert([
            [
                'Id'          => config('constant.ID.THIRD_PARTIES.OTHERS'),
                'Name'        => 'Others',
                'CreatedById' => config('constant.ID.USERS.ADMIN'),
                'UpdatedById' => config('constant.ID.USERS.ADMIN'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ]);
    }
}
