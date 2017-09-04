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

/**
 * USER
 */
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

/**
 * LEAD
 */
$factory->define(App\Lead::class, function(Faker\Generator $faker){

	//get application types IDs
    $applicationTypesIDsArray = getApplicationsIDs();


	//get lead categories IDs
    $leadCategoriesIDsArray = getCategoriesIDs();

	// get User IDs
    $userIDsArray = getUsersIDs();


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

/**
 * DOMAIN
 */
$factory->define(\App\Domain::class, function(Faker\Generator $faker){

    // get Lead IDs
    $leadIDsArray = getLeadsIDs();

    return [
        'lead_id' => $leadIDsArray[array_rand($leadIDsArray,1)],
        'value' => $faker->unique()->domainName,
    ];
});

/**
 * COMMUNICATION VALUES
 */
$factory->define(\App\CommunicationValue::class, function(\Faker\Generator $faker){

    // get Lead IDs
    $leadIDsArray = getLeadsIDs();

    // get communicationsChannels IDs
    $communicationsChannelsIDsArray = getCommunicationChannelsIDs();

    return [
        'lead_id' => $leadIDsArray[array_rand($leadIDsArray,1)],
        'channel_id' => $communicationsChannelsIDsArray[array_rand($communicationsChannelsIDsArray,1)],
        'value' => $faker->unique()->safeEmail
    ];
});

/**
 * COMMENTS
 */
$factory->define(\App\Comment::class, function(\Faker\Generator $faker){
    // get User IDs
    $userIDsArray = getUsersIDs();

    // get Lead IDs
    $leadIDsArray = getLeadsIDs();

    return [
        'user_id' => $userIDsArray[array_rand($userIDsArray,1)],
        'lead_id' => $leadIDsArray[array_rand($leadIDsArray,1)],
        'comment' => $faker->text($maxNbChars = 120)
    ];

});

/**
 * COMMUNICATION RECORDS
 */
$factory->define(\App\CommunicationRecord::class, function(\Faker\Generator $faker){

    // get communicationsChannels IDs
    $communicationsChannelsIDsArray = getCommunicationChannelsIDs();

    // get Lead IDs
    $leadIDsArray = getLeadsIDs();

    // get User IDs
    $userIDsArray = getUsersIDs();

    return [
        'channel_id' => $communicationsChannelsIDsArray[array_rand($communicationsChannelsIDsArray,1)],
        'lead_id' =>  $leadIDsArray[array_rand($leadIDsArray,1)],
        'user_id' => $userIDsArray[array_rand($userIDsArray,1)],
        'value' => $faker->sentence($nbWords = 3, $variableNbWords = true)." - ".$faker->e164PhoneNumber
    ];
});

#region SERVICE METHODS
function getApplicationsIDs()
{
    $applicationTypeIDs = DB::table('application_types')->get();
    $applicationTypesIDsArray = [];
    foreach ($applicationTypeIDs AS $key=>$value){
        $applicationTypesIDsArray[] = $value->id;
    }

    return $applicationTypesIDsArray;
}

function getCategoriesIDs()
{
    $leadCategoriesIDs = DB::table('lead_categories')->get();
    $leadCategoriesIDsArray = [];
    foreach($leadCategoriesIDs AS $key=>$value){
        $leadCategoriesIDsArray[] = $value->id;
    }
    return $leadCategoriesIDsArray;
}

function getUsersIDs()
{
    $userIDs = DB::table('users')->get();
    $userIDsArray = [];
    foreach($userIDs AS $key=>$value){
        $userIDsArray[] = $value->id;
    }
    return $userIDsArray;
}

function getLeadsIDs()
{
    $leadIDs = DB::table('leads')->get();
    $leadIDsArray = [];
    foreach ($leadIDs AS $key=>$value){
        $leadIDsArray[] = $value->id;
    }
    return $leadIDsArray;
}

function getCommunicationChannelsIDs()
{
    $communicationChannelsIDs = DB::table('communication_channels')->get();
    $communicationChannelsIDsArray = [];
    foreach ($communicationChannelsIDs AS $key=>$value){
        $communicationChannelsIDsArray[] = $value->id;
    }
    return $communicationChannelsIDsArray;
}

#endregion

