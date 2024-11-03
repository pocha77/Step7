<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            ['name' => 'キリン'],
            ['name' => 'サントリー'],
            ['name' => 'コカ・コーラ'],
        ]);
    }
}