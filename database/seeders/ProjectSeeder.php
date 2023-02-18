<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // USERS
        $AdminId = config('constant.ID.USERS.ADMIN');

        DB::table('projects')->insert([
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Landers',
                'Description' => 'Sample Description',
                'KickoffDate' => '2022-12-01',
                'ClosedDate'  => '2023-12-01',
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'City of Davao',
                'Description' => 'Sample Description',
                'KickoffDate' => '2022-12-01',
                'ClosedDate'  => '2023-12-01',
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Smart Admin as a Service',
                'Description' => 'Sample Description',
                'KickoffDate' => '2022-12-01',
                'ClosedDate'  => '2023-12-01',
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Smart B2C',
                'Description' => 'Sample Description',
                'KickoffDate' => '2022-12-01',
                'ClosedDate'  => '2023-12-01',
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
            [
                'Id'          => Str::uuid(),
                'Name'        => 'Ricoh',
                'Description' => 'Sample Description',
                'KickoffDate' => '2022-12-01',
                'ClosedDate'  => '2023-12-01',
                'CreatedById' => $AdminId,
                'UpdatedById' => $AdminId,
            ],
        ]);
    }
}
