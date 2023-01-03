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
        $GMDesignationId = config('constant.ID.DESIGNATIONS.BA_HEAD');

        DB::table('permissions')->insert([
            [
                'Id'            => Str::uuid(),
                'DesignationId' => $GMDesignationId,
                'ModuleId'      => 18, // CUSTOMER
                'Read'          => 1,
                'Create'        => 1,
                'Edit'          => 1,
                'Delete'        => 1,
                'CreatedById'   => $AdminId,
                'UpdatedById'   => $AdminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        ]);
    }
}
