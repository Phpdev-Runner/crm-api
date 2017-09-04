<?php

use Illuminate\Database\Seeder;

class CommunicationRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\CommunicationRecord',180)->create();
    }
}
