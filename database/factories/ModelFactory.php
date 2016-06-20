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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    
    return [
    
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\Set::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'name' => 'fake name',
        'code' => 'FAKE',
        'release_date' => '2015-03-13',
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\Card::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'name' => 'fake name',
        'mana_cost' => null,
        'cmc' => null,
        'middle_text' => 'fake',
        'rules_text' => null,
        'power' => null,
        'toughness' => null,
        'loyalty' => null,
        'f_cost' => null,
        'note' => null,
        'layout' => 'normal',
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\SetCard::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'rarity' => 'Rare',
        'multiverseid' => 1,
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\CardColorIdentity::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});


$factory->define(App\Models\CardColor::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\CardName::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\CardSubtype::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\CardSupertype::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\CardType::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\Event::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'name' => 'Bob event',
        'location' => 'Bob location',
        'date' => '2015-03-13',
        'url' => 'Bob url', 
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\EventDeck::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'player' => 'Bob',
        'finish' => 1,
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

$factory->define(App\Models\CardMetagame::class, function ($faker) {
    
    return [
        
        'id' => rand(50, 100),
        'date' => '2015-03-13',
        'card_id' => 1,
        'md_percentage' => 50.00,
        'sb_percentage' => 50.00,
        'total_percentage' => 100.00,
        'created_at' => '2015-03-13',
        'updated_at' => '2015-03-13'
    ];
});

