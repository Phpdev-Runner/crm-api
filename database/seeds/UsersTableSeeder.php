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
        DB::table('users')->insert(
            [
                'name'=>'admin',
                'email'=>'admin@gmail.com',
                'role_id'=>\App\User::getRolesIDs([config('constants.roles.admin')])[0],
                'password'=>bcrypt('secret')
            ]
        );

        DB::table('users')->insert(
            [
                'name'=>'manager',
                'email'=>'manager@gmail.com',
                'role_id'=>\App\User::getRolesIDs([config('constants.roles.manager')])[0],
                'password'=>bcrypt('secret')
            ]
        );
        
        factory('App\User',5)->create();
    }
}
