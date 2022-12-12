<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class ComplexitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $req_id_one   = Str::uuid();
        $req_id_two   = Str::uuid();
        $req_id_three = Str::uuid();
        $req_id_four  = Str::uuid();
        $req_id_five  = Str::uuid();
        DB::table('complexity')->insert([
            [
                'Id'            => $req_id_one,
                'Name'          => 'More than (1) one Company /Business Units involve',
            ],
            [
                'Id'            => $req_id_two,
                'Name'          => 'More than (1) one Salesforce modules is needed as solution',
            ],
            [
                'Id'            => $req_id_three,
                'Name'          => 'Needs integration or multiple integrations',
            ],
            [
                'Id'            => $req_id_four,
                'Name'          => 'Highly customized solution using development',
            ],
            [
                'Id'            => $req_id_five,
                'Name'          => 'Highly customized solution using configuration',
            ],
        ]);

        DB::table('complexity_details')->insert([
            [
                'Id'             => Str::uuid(),
                'ComplexityId'  => $req_id_four,
                'Details'        => 'Mobile app development',
            ],
            [
                'Id'             => Str::uuid(),
                'ComplexityId'  => $req_id_four,
                'Details'        => 'Website development',
            ],
            [
                'Id'             => Str::uuid(),
                'ComplexityId'  => $req_id_four,
                'Details'        => 'Development efforts to be used due to Salesforce limitations',
            ],
            [
                'Id'            => Str::uuid(),
                'ComplexityId' => $req_id_five,
                'Details'        => 'Standards Reports and Dashboards',
            ],
            [
                'Id'            => Str::uuid(),
                'ComplexityId' => $req_id_five,
                'Details'        => 'Analytics',
            ],
            [
                'Id'            => Str::uuid(),
                'ComplexityId' => $req_id_five,
                'Details'        => 'Marketing Cloud (Mobile Studio, Interaction Studio, Advertising Studio, Journey Builder, Datorama)',
            ],
            [
                'Id'            => Str::uuid(),
                'ComplexityId' => $req_id_five,
                'Details'        => 'Communities',
            ],
            [
                'Id'            => Str::uuid(),
                'ComplexityId' => $req_id_five,
                'Details'        => 'Chatbot (iOs and Android, Website, and Messenger)',
            ],
            [
                'Id'            => Str::uuid(),
                'ComplexityId' => $req_id_five,
                'Details'        => 'Commerce Cloud',
            ],
        ]);
    }
}
