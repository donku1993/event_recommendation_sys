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

use App\Models\Helper;
use Carbon\Carbon;

function JsonSimilator(int $max_length) {
    $json = [];

    $total = rand(1, $max_length / 2);
    $true_value = [];
    for ($i=0; $i < $total; $i++) { 
        $value = rand(0, $max_length - 1);
        while (in_array($value, $true_value)) {
            $value = rand(0, $max_length - 1);
        }

        array_push($true_value, $value);
    }

    for ($i=0; $i < $max_length; $i++) {
        $json[$i] = (in_array($i, $true_value)) ? true : false;
    }

    return $json;
}

// User
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    $interest_skills = JsonSimilator(sizeof(Helper::getConstantArray('interest_skills')['value']));
    $available_time = JsonSimilator(sizeof(Helper::getConstantArray('available_time')['value']));
    $available_area = JsonSimilator(sizeof(Helper::getConstantArray('location')['value']));

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'phone' => '6' . strval(rand(1000000, 9999999)),
        'address_location' => rand(0, 8),
        'gender' => rand(0, 1),
        'career' => rand(0, 5),
        'allow_email' => rand(0, 1),
        'interest_skills' => $interest_skills,
        'available_time' => $available_time,
        'available_area' => $available_area
    ];
});

$factory->state(App\Models\User::class, 'group_manager', function (Faker\Generator $faker) {
    return [
        'type' => 3
    ];
});

$factory->state(App\Models\User::class, 'administrator', function (Faker\Generator $faker) {
    return [
        'type' => 0
    ];
});


// Group
/*
    need to fill 'user_id', 'name', 'principal_name'.
    status default to 0 (new group form).
*/
$factory->define(App\Models\Group::class, function(Faker\Generator $faker) {
    $activity_area = JsonSimilator(sizeof(Helper::getConstantArray('location')['value']));

    return [
        'registered_id' => $faker->unique()->numberBetween(100000000000, 999999999999),
        'registered_file' => str_random(10) . '.pdf',
        'establishment_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'email' => $faker->unique()->safeEmail,
        'phone' => '28' . strval(rand(100000, 999999)),
        'address' => $faker->address(),
        'activity_area' => $activity_area,
    ];
});

$factory->state(App\Models\Group::class, 'waiting_group_form', function(Faker\Generator $faker) {
    return [
        'status' => 1
    ];
});

$factory->state(App\Models\Group::class, 'group', function(Faker\Generator $faker) {
    return [
        'status' => 2
    ];
});

$factory->state(App\Models\Group::class, 'rejected_group_form', function(Faker\Generator $faker) {
    return [
        'status' => 3
    ];
});

// Event
/*
    need to fill 'groups_events_relation_table'.
*/
$factory->define(App\Models\Event::class, function(Faker\Generator $faker) {
    $bonus_skills = JsonSimilator(sizeof(Helper::getConstantArray('interest_skills')['value']));

    $now = Carbon::now();
    $created_at = $faker->dateTimeBetween('-2 months', 'now');

    $signUpEndDate = new Carbon($created_at->format('Y-m-d H:i:s'));
    $signUpEndDate->addDays(rand(30, 70));

    $startDate = new Carbon($signUpEndDate->format('Y-m-d H:i:s'));
    $startDate->addDays(rand(5, 10));

    $endDate = new Carbon($startDate->format('Y-m-d H:i:s'));
    $endDate->addDays(rand(1, 3));

    if ($endDate->lt($now)) {
        $status = 2;
    } elseif ($signUpEndDate->lt($now)) {
        $status = 1;
    } else {
        $status = 0;
    }

    return [
        'numberOfPeople' => 5 * rand(2, 6),
        'title' => 'Random Event: ' . str_random(5),
        'content' => $faker->realText(200),
        'location' => rand(0, 8),
        'type' => rand(0, 6),
        'schedule' => $faker->realText(200),
        'requirement' => $faker->realText(200),
        'bonus_skills' => $bonus_skills,
        'created_at' => $created_at,
        'signUpEndDate' => $signUpEndDate,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'status' => $status
    ];
});

$factory->state(App\Models\Event::class, 'one_year_ago', function(Faker\Generator $faker) {
    $now = Carbon::now();
    $created_at = $faker->dateTimeBetween('-1 years', 'now');

    $signUpEndDate = new Carbon($created_at->format('Y-m-d H:i:s'));
    $signUpEndDate->addDays(rand(30, 70));

    $startDate = new Carbon($signUpEndDate->format('Y-m-d H:i:s'));
    $startDate->addDays(rand(5, 10));

    $endDate = new Carbon($startDate->format('Y-m-d H:i:s'));
    $endDate->addDays(rand(1, 3));

    if ($endDate->lt($now)) {
        $status = 2;
    } elseif ($signUpEndDate->lt($now)) {
        $status = 1;
    } else {
        $status = 0;
    }

    return [
        'created_at' => $created_at,
        'signUpEndDate' => $signUpEndDate,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'status' => $status
    ];
});