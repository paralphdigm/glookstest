<?php

use Illuminate\Database\Seeder;

class ApiTokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ApiToken::class, 1)->create();
    }
}
