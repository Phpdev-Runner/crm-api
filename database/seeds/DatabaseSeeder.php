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
//        $this->call(LeadsTableSeeder::class);
//        $this->call(DomainsTableSeeder::class);
        factory(App\Lead::class,150)->create()->each(function($u){
            $u->domains()->save(factory(App\Domain::class)->make());
        });

        $this->call(DomainsTableSeeder::class);
    }
}
