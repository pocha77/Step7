<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 他のシーダーがある場合は以下の行に追加していきます。
        
        // CompaniesTableSeederを呼び出す
        $this->call(CompaniesTableSeeder::class);
    }
}
