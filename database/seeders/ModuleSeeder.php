<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id'        => 1,
                'ParentId'  => null,
                'Title'     => 'Dashboard',
                'Icon'      => 'dashboard.png',
                'RouteName' => 'dashboard',
                'Prefix'    => 'dashboard',
                'Status'    => 'Active',
                'SortOrder' => 1,
            ],
            [
                'id'        => 2,
                'ParentId'  => null,
                'Title'     => 'Employee Directory',
                'Icon'      => 'employeeDirectory.png',
                'RouteName' => 'employeeDirectory',
                'Prefix'    => 'employeeDirectory',
                'Status'    => 'Active',
                'SortOrder' => 2,
            ],
            [
                'id'        => 3,
                'ParentId'  => null,
                'Title'     => 'Project',
                'Icon'      => 'project.png',
                'RouteName' => null,
                'Prefix'    => 'projects',
                'Status'    => 'Active',
                'SortOrder' => 3,
            ],
            [
                'id'        => 4,
                'ParentId'  => 3,
                'Title'     => 'List of Project',
                'Icon'      => 'default.png',
                'RouteName' => 'projects.view',
                'Prefix'    => 'projects',
                'Status'    => 'Active',
                'SortOrder' => 1,
            ],
        ];

        Module::insert($data);
        DB::select("SELECT 
            setval(pg_get_serial_sequence('modules', 'id'), 
            max(id)) 
        FROM modules");
    }
}
