<?php

use App\Models\Set;

use App\Models\EventDeck;
use App\Models\EventDeckCopy;
use App\Models\Card;

use App\Models\Temp1CardMetagame;

Route::get('/bob', function() {

	$deckId = 24;

	$deck = EventDeck::find($deckId);

	$max = [

		'md' => $deck->md_count / 15,
		'sb' => 4
	];

	$copies = EventDeckCopy::with('event_deck')->with('card')->where('event_deck_id', $deckId)->get();

	foreach ($copies as $copy) {
		
		$temp1CardMetagame = new Temp1CardMetagame;

		$temp1CardMetagame->event_deck_id = $deckId;
		$temp1CardMetagame->card_id = $copy->card_id;
		$temp1CardMetagame->quantity = $copy->quantity;
		$temp1CardMetagame->percentage = numFormat($copy->quantity / $max[$copy->role] * 100, 2);
		$temp1CardMetagame->role = $copy->role;

		$temp1CardMetagame->save();
	}

	$totalMdPercentage = Temp1CardMetagame::where('role', 'md')->sum('percentage');

	$totalSbPercentage = Temp1CardMetagame::where('role', 'sb')->sum('percentage');

	prf('Total MD%: '.$totalMdPercentage); // ($totalMdPercentage > 1499.50 && $totalMdPercentage < 1500.50)

	prf('Total SB%: '.$totalSbPercentage); // $totalSbPercentage == $max['sb'] / 4 * 100

	Temp1CardMetagame::getQuery()->delete();

	ddAll('Success!');
});




/****************************************************************************************
HOME
****************************************************************************************/

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('master', compact('titleTag'));
});


/****************************************************************************************
EVENTS
****************************************************************************************/

Route::resource('events', 'EventsController');


/****************************************************************************************
DECKS
****************************************************************************************/

Route::resource('decks', 'DecksController');


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