<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        factory(App\Lead::class,150)->create()->each(function($u){
            $u->domains()->save(factory(App\Domain::class)->make());
        })->each(function($u){
            $u->communicationValues()->save(factory(\App\CommunicationValue::class)->make());
        });

        $this->call(DomainsTableSeeder::class);

        $this->call(CommunicationValuesTableSeeder::class);

        $this->call(CommentsTableSeeder::class);

        $this->call(CommunicationRecordsTableSeeder::class);
    }
}
