<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\admin\Department;
use App\Models\admin\Designation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\admin\Module;
use DB;

class StartUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ----- DEPARTMENT -----
        $this->call(DepartmentSeeder::class);
        // ----- END DEPARTMENT -----


        // ----- DESIGNATION -----
        $this->call(DesignationSeeder::class);
        // ----- END DESIGNATION -----


        // ----- USERS -----
        $this->call(UserSeeder::class);
        // ----- END USERS -----


        // ----- MODULES -----
        $this->call(ModuleSeeder::class);
        // ----- END MODULES -----


        // ----- LEAVE TYPE -----
        $this->call(LeaveTypeSeeder::class);
        // ----- END LEAVE TYPE -----


        // ----- COMPLEXITY -----
        $this->call(ComplexitySeeder::class);
        // ----- END COMPLEXITY -----


        // ----- PROJECT PHASES -----
        $this->call(ProjectPhaseSeeder::class);
        // ----- END PROJECT PHASES -----


        // ----- THIRD PARTIES -----
        $this->call(ThirdPartySeeder::class);
        // ----- END THIRD PARTIES -----


        // ----- PERMISSION -----
        $this->call(PermissionSeeder::class);
        // ----- END PERMISSION -----
    }
}
