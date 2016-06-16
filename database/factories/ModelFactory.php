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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    
    return [
    
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Set::class, function ($faker) {
    
    return [
        
        'id' => rand(1, 100),
        'name' => 'fake name',
        'code' => 'FAKE',
        'release_date' => '2015-03-13',
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});
