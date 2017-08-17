<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name'=>'Andrey Naumoff',
	        'email'=>'andrey.naumoff@gmail.com',
	        'role_id'=>\App\User::getRolesIDs([config('roles.admin')])[0],
	        'password'=>bcrypt('secret')
        ]);
        
        factory('App\User',5)->create();
    }
}
