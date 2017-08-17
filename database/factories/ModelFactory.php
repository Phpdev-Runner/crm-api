<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    $password = 'secret';
	$managerRoleIDs = App\User::getRolesIDs([config('roles.manager'),config('roles.unauthorized')]);
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
	    'role_id' => $managerRoleIDs[array_rand($managerRoleIDs,1)],
        'password' => bcrypt($password),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Lead::class, function(Faker\Generator $faker){
	
	return [
		'category_id' => '',
		'application_type_id'=>'',
		'creator_id'=>'',
		'assignee_id'=>'',
		'name'=>$faker->name,
		'responsive'=>''
	];
});
