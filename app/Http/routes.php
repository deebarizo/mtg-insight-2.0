<?php

use App\Models\Set;

use App\Models\Event;
use App\Models\EventDeck;
use App\Models\EventDeckCopy;
use App\Models\Card;

use App\Models\Temp1CardMetagame;

Route::get('/bob', function() {

	Temp1CardMetagame::getQuery()->delete();

	$eventId = 4;

	$event = Event::with('event_decks')->find($eventId);

	$numOfDecks = count($event->event_decks);

	$totalMaxSb = 0;

	$totalMetagamePoints = EventDeck::where('event_id', $eventId)->sum('finish');	

	$multipliers = [];

	for ($finishIndex = 1; $finishIndex <= $numOfDecks; $finishIndex++) { 
		
		$multipliers[$finishIndex] = ($numOfDecks - $finishIndex + 1) / $totalMetagamePoints;
	}

	foreach ($event->event_decks as $deck) {

		$max = [

			'md' => $deck->md_count / 15,
			'sb' => 4
		];

		$copies = EventDeckCopy::with('event_deck')->with('card')->where('event_deck_id', $deck->id)->get();

		foreach ($copies as $copy) {
			
			$temp1CardMetagame = new Temp1CardMetagame;

			$temp1CardMetagame->event_deck_id = $deck->id;
			$temp1CardMetagame->card_id = $copy->card_id;
			$temp1CardMetagame->quantity = $copy->quantity;
			$temp1CardMetagame->percentage = numFormat($copy->quantity / $max[$copy->role] * 100 * $multipliers[$deck->finish], 2);
			$temp1CardMetagame->role = $copy->role;

			$temp1CardMetagame->save();
		}

		$totalMaxSb += $deck->sb_count / 4 * 100;
	}

	$totalMdPercentage = Temp1CardMetagame::where('role', 'md')->sum('percentage');

	$totalSbPercentage = Temp1CardMetagame::where('role', 'sb')->sum('percentage');

	prf('Total MD%: '.$totalMdPercentage); // ($totalMdPercentage > 1499.50 && $totalMdPercentage < 1500.50)
	prf(' ');

	prf('Total SB%: '.$totalSbPercentage); // $totalSbPercentage == $max['sb'] / 4 * 100
	prf('Total Max SB%: '.$totalMaxSb / $numOfDecks);

	prf(' ');
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