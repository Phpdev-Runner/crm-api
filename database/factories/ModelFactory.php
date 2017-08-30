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
	$managerRoleIDs = App\User::getRolesIDs([
		config('constants.roles.manager'),
		config('constants.roles.unauthorized')
	]);
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
	    'role_id' => $managerRoleIDs[array_rand($managerRoleIDs,1)],
        'password' => bcrypt($password),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Lead::class, function(Faker\Generator $faker){

	//get application types IDs
	$applicationTypeIDs = DB::table('application_types')->get();
	$applicationTypesIDsArray = [];
	foreach ($applicationTypeIDs AS $key=>$value){
		$applicationTypesIDsArray[] = $value->id;
	}

	//get lead categories IDs
	$leadCategoriesIDs = DB::table('lead_categories')->get();
	$leadCategoriesIDsArray = [];
	foreach($leadCategoriesIDs AS $key=>$value){
		$leadCategoriesIDsArray[] = $value->id;
	}

	$userIDs = DB::table('users')->get();
	$userIDsArray = [];
	foreach($userIDs AS $key=>$value){
		$userIDsArray[] = $value->id;
	}

	//get application
	return [
		'category_id' => $leadCategoriesIDsArray[array_rand($leadCategoriesIDsArray,1)],
		'application_type_id'=>$applicationTypesIDsArray[array_rand($applicationTypesIDsArray,1)],
		'creator_id'=>$userIDsArray[array_rand($userIDsArray,1)],
		'assignee_id'=>$userIDsArray[array_rand($userIDsArray,1)],
		'name'=>$faker->name,
		'responsive'=>rand(0,1)
	];
});

$factory->define(\App\Domain::class, function(Faker\Generator $faker){

    $leadIDs = DB::table('leads')->get();

    $leadIDsArray = [];

    foreach ($leadIDs AS $key=>$value){
        $leadIDsArray[] = $value->id;
    }

    return [
        'lead_id' => $leadIDsArray[array_rand($leadIDsArray,1)],
        'value' => $faker->unique()->domainName,
    ];
});
