<?php

/****************************************************************************************
HOME
****************************************************************************************/

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('master', compact('titleTag'));
});


/****************************************************************************************
TRANSACTIONS
****************************************************************************************/

Route::get('/transactions', function() {

	$titleTag = 'Transactions | ';
	$h2Tag = 'Transactions';
	
	return View::make('transactions/index', compact('titleTag', 'h2Tag'));
});


/****************************************************************************************
CARD METAGAME
****************************************************************************************/

Route::resource('card_metagame', 'CardMetagameController');


/****************************************************************************************
CARDS
****************************************************************************************/

Route::resource('cards', 'CardsController');

Route::get('/post_rotation_cards', 'CardsController@post_rotation_cards');
Route::get('/rotating_cards', 'CardsController@rotating_cards');


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

Route::post('/your_decks/store', 'YourDecksController@store'); // this is needed because i'm using ajax
Route::get('/your_decks/{latestSetCode}/{slug}', 'YourDecksController@show');
Route::resource('your_decks', 'YourDecksController');


/****************************************************************************************
SEARCH
****************************************************************************************/

Route::get('/search', 'SearchesController@getSearchQuery');
Route::post('/search', 'SearchesController@runSearchQuery');


/****************************************************************************************
ADMIN
****************************************************************************************/

Route::get('/admin', function() {

	$titleTag = 'Admin | ';
	
	return View::make('admin/index', compact('titleTag'));
});

use App\Models\Set;

Route::get('/admin/parsers/cockatrice_xml', function() {

	$titleTag = 'Parsers - Cockatrice XML | ';
    $h2Tag = 'Parsers - Cockatrice XML';	

    $sets = Set::orderBy('id', 'desc')->get();

	return View::make('admin/parsers/cockatrice_xml', compact('titleTag', 'h2Tag', 'sets'));
});

Route::get('/admin/parsers/mtg_json', ['as' => 'admin.parsers.mtg_json', function() {

	$titleTag = 'Parsers - MTG JSON | ';
    $h2Tag = 'Parsers - MTG JSON';	

	return View::make('admin/parsers/mtg_json', compact('titleTag', 'h2Tag', 'sets'));
}]);

Route::post('/admin/parsers/mtg_json', 'ParsersController@parseMtgJson');

Route::get('/admin/parsers/fix_mana_costs', ['as' => 'admin.parsers.fix_mana_costs', function() {

	$titleTag = 'Parsers - Fix Mana Costs | ';
    $h2Tag = 'Parsers - Fix Mana Costs';	

	return View::make('admin/parsers/fix_mana_costs', compact('titleTag', 'h2Tag'));
}]);

Route::post('/admin/parsers/fix_mana_costs', 'ParsersController@fixManaCosts');

Route::get('/admin/scrapers/mtg_goldfish', ['as' => 'admin.scrapers.mtg_goldfish', function() {

	$titleTag = 'Scrapers - MTG Goldfish | ';
    $h2Tag = 'Scrapers - MTG Goldfish';	

	return View::make('admin/scrapers/mtg_goldfish', compact('titleTag', 'h2Tag'));
}]);

Route::post('/admin/scrapers/mtg_goldfish', 'ScrapersController@scrapeMtgGoldfish');

Route::get('/admin/settings', ['as' => 'admin.settings', function() {

	$titleTag = 'Settings | ';
    $h2Tag = 'Settings';	

	return View::make('admin/settings/index', compact('titleTag', 'h2Tag'));
}]);

Route::post('/admin/settings', 'SettingsController@saveSettings');


/****************************************************************************************
ONE TIME PROCESS
****************************************************************************************/

// use App\Models\Card;

Route::get('/one_time_process', function() {

	ddAll('Success!');
});