<?php

use Illuminate\Database\Seeder;

class CommunicationValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\CommunicationValue',25)->create();
    }
}
