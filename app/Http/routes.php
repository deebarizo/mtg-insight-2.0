<?php

use App\Models\Set;


/****************************************************************************************
HOME
****************************************************************************************/

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('master', compact('titleTag'));
});


/****************************************************************************************
CARD METAGAME
****************************************************************************************/

Route::resource('card_metagame', 'CardMetagameController');


/****************************************************************************************
CARDS
****************************************************************************************/

Route::resource('cards', 'CardsController');


/****************************************************************************************
EVENTS
****************************************************************************************/

Route::resource('events', 'EventsController');


/****************************************************************************************
DECKS
****************************************************************************************/

Route::resource('decks', 'DecksController');


/****************************************************************************************
YOUR DECKS
****************************************************************************************/

Route::resource('your_decks', 'YourDecksController');


/****************************************************************************************
ADMIN
****************************************************************************************/

Route::get('/admin', function() {

	$titleTag = 'Admin | ';
	
	return View::make('admin/index', compact('titleTag'));
});

Route::get('/admin/parsers/cockatrice_xml', function() {

	$titleTag = 'Parsers - Cockatrice XML';
    $h2Tag = 'Parsers - Cockatrice XML';	

    $sets = Set::orderBy('id', 'desc')->get();

	return View::make('admin/parsers/cockatrice_xml', compact('titleTag', 'h2Tag', 'sets'));
});

Route::get('/admin/parsers/mtg_json', ['as' => 'admin.parsers.mtg_json', function() {

	$titleTag = 'Parsers - MTG JSON';
    $h2Tag = 'Parsers - MTG JSON';	

	return View::make('admin/parsers/mtg_json', compact('titleTag', 'h2Tag', 'sets'));
}]);

Route::post('/admin/parsers/mtg_json', 'ParsersController@parseMtgJson');

Route::get('/admin/scrapers/mtg_goldfish', ['as' => 'admin.scrapers.mtg_goldfish', function() {

	$titleTag = 'Scrapers - MTG Goldfish';
    $h2Tag = 'Scrapers - MTG Goldfish';	

	return View::make('admin/scrapers/mtg_goldfish', compact('titleTag', 'h2Tag'));
}]);

Route::post('/admin/scrapers/mtg_goldfish', 'ScrapersController@scrapeMtgGoldfish');


/****************************************************************************************
TEST
****************************************************************************************/

// use App\Models\Card;
// use App\Models\CardTag;

Route::get('/one_time_process', function() {

	ddAll('Success!');
});