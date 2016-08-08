<?php namespace App\UseCases;

use App\Models\Card;
use App\Models\SetCard;
use App\Models\Set;
use App\Models\CardTag;
use App\Models\CardPrice;

use App\Models\CardMetagame;

use DB;

class CardsTableCreator {

	public function createCardsTable($firstSet, $lastSet) {

		$latestDateForCardMetagame = CardMetagame::orderBy('date', 'desc')->take(1)->pluck('date')[0];

		$latestDateForCardPrices = CardPrice::orderBy('created_at', 'desc')->take(1)->pluck('created_at')[0];

		$cards = Card::select('cards.id', 
							  'cards.name', 
							  'cards.f_cost', 
							  'cards.mana_cost',
							  'cards.rating',
							  'card_prices.price',
							  'sets.code')
						->join('sets_cards', function($join) {
	  
							$join->on('sets_cards.card_id', '=', 'cards.id');
						})
						->join('sets', function($join) {
	  
							$join->on('sets.id', '=', 'sets_cards.set_id');
						})
						->leftJoin('card_tags', function($join) {
	  
							$join->on('card_tags.card_id', '=', 'cards.id');
						})
						->leftJoin('card_prices', function($join) {
	  
							$join->on('card_prices.card_id', '=', 'cards.id');
						})
						->leftJoin('card_metagames', function($join) {
	  
							$join->on('card_metagames.card_id', '=', 'cards.id');
						})
						->where(function($query) {

							return $query->where('card_tags.tag', '!=', 'back-of-double-faced-card')
											->orWhereNull('card_tags.tag');
						})
						->where(function($query) use($latestDateForCardPrices) {

							return $query->where('card_prices.created_at', $latestDateForCardPrices)
											->orWhereNull('card_prices.created_at');
						})
						->where('sets.id', '>=', $firstSet['id'])
						->where('sets.id', '<=', $lastSet['id'])
						->groupBy('cards.id')
						->get();

		$cardMetagame = CardMetagame::where('date', $latestDateForCardMetagame)->get();

		# ddAll($cardMetagame);

		foreach ($cards as $card) {

			$cardIsInMetagame = false;

			foreach ($cardMetagame as $metagameCard) {
				
				if ($card->id === $metagameCard->card_id) {

					$card->md_percentage = NumFormat($metagameCard->md_percentage, 2);
					$card->sb_percentage = NumFormat($metagameCard->sb_percentage, 2);
					$card->total_percentage = NumFormat($metagameCard->total_percentage, 2);

					$cardIsInMetagame = true;

					break;
				}
			}

			if (!$cardIsInMetagame) {

				$card->md_percentage = NumFormat(0, 2);
				$card->sb_percentage = NumFormat(0, 2);
				$card->total_percentage = NumFormat(0, 2);
			}
		}

		# ddAll($cards);

		$fCosts = Card::select('cards.f_cost')
						->leftJoin('card_tags', function($join) {
	  
							$join->on('card_tags.card_id', '=', 'cards.id');
						})
						->where(function($query) {

							return $query->where('card_tags.tag', '!=', 'back-of-double-faced-card')
											->where('card_tags.tag', '!=', 'non-spell-land')
											->orWhereNull('card_tags.tag');
						})
						->groupBy('cards.f_cost')
						->orderBy(DB::raw('FIELD(cards.f_cost, "Land", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "Variable")'))
						->pluck('cards.f_cost')
						->toArray();

		$sets = Set::where('code', '!=', 'LANDS')->orderBy('id', 'desc')->pluck('code')->toArray();

		# ddAll($sets);

		$colors = [

			[
				'abbr' => 'w',
				'name' => 'white'
			],
			[
				'abbr' => 'u',
				'name' => 'blue'
			],
			[
				'abbr' => 'b',
				'name' => 'black'
			],
			[
				'abbr' => 'r',
				'name' => 'red'
			],
			[
				'abbr' => 'g',
				'name' => 'green'
			],
			[
				'abbr' => 'c',
				'name' => 'colorless'
			]            
		];

		return array($latestDateForCardMetagame, $latestDateForCardPrices, $cards, $fCosts, $sets, $colors);
	}

}