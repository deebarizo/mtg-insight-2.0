<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;

class SearchesController extends Controller {

	public function getSearchQuery() {

		$titleTag = 'Search | ';
		$h2Tag = 'Search';

		return view('search.index', compact('titleTag', 'h2Tag'));
	}

	public function runSearchQuery(Request $request) {

		$titleTag = 'Search Results | ';
		$h2Tag = 'Search Results';

		$type = strtolower($request->input('type'));
		$fCost = $request->input('f-cost');
		$power = $request->input('power');
		$powerComparision = $request->input('power-comparison');
		$toughness = $request->input('toughness');
		$toughnessComparision = $request->input('toughness-comparison');

		if ($type === 'creature') {

			$cards = Card::select('cards.name',
								  'sets.code')
							->join('sets_cards', function($join) {
		  
								$join->on('sets_cards.card_id', '=', 'cards.id');
							})
							->join('sets', function($join) {
		  
								$join->on('sets.id', '=', 'sets_cards.set_id');
							})
							->where('middle_text', 'LIKE', '%'.$type.'%')
						 	->where('f_cost', $fCost)
						 	->where('power', $powerComparision, $power)
						 	->where('toughness', $toughnessComparision, $toughness)
						 	->whereNotNull('mana_cost')
						 	->groupBy('cards.id')
						 	->get();
		}

		# ddAll($cards);

		return view('search.results', compact('titleTag', 'h2Tag', 'cards'));
	}

}