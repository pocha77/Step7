<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        $companies = [
            ['company_name' => 'コカ・コーラ'],
            ['company_name' => 'サントリー'],
            ['company_name' => 'キリン'],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}