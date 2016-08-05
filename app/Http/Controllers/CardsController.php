<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\SetCard;
use App\Models\Set;
use App\Models\CardTag;
use App\Models\CardPrice;

use App\Models\CardMetagame;

use DB;

class CardsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$titleTag = 'Cards | ';
		$h2Tag = 'Cards';

		$latestDateForCardMetagame = CardMetagame::orderBy('date', 'desc')->take(1)->pluck('date')[0];

		$latestDateForCardPrices = CardPrice::orderBy('created_at', 'desc')->take(1)->pluck('created_at')[0];

		$firstSet = [

			'code' => 'DTK'
		];

		$firstSet['id'] = Set::where('code', $firstSet['code'])->pluck('id')[0];

		$lastSet = [

			'code' => 'EMN'
		];

		$lastSet['id'] = Set::where('code', $lastSet['code'])->pluck('id')[0];

		$cards = Card::select('cards.id', 
							  'cards.name', 
							  'cards.f_cost', 
							  'cards.mana_cost',
							  'cards.rating',
							  'card_prices.price',
							  'card_metagames.md_percentage',
							  'card_metagames.sb_percentage',
							  'card_metagames.total_percentage',
							  'sets.id')
						->with('card_tags')
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
						->where(function($query) use($latestDateForCardMetagame) {

							return $query->where('card_metagames.date', $latestDateForCardMetagame)
											->orWhereNull('card_metagames.date');
						})
						->where(function($query) use($latestDateForCardPrices) {

							return $query->where('card_prices.created_at', $latestDateForCardPrices)
											->orWhereNull('card_prices.created_at');
						})
						->groupBy('cards.id')
						->having('sets.id', '>=', $firstSet['id'])
						->having('sets.id', '<=', $lastSet['id'])
						->get();

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

		return view('cards.index', compact('titleTag', 'h2Tag', 'latestDateForCardMetagame', 'latestDateForCardPrices', 'cards', 'fCosts', 'colors'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$card = DB::table('cards')
					->select('cards.id',
							 'cards.name',
							 'cards.f_cost',
							 'sets.code',
							 'cards.rating',
							 'cards.mana_sources')
					->join('sets_cards', 'sets_cards.card_id', '=', 'cards.id')
					->join('sets', 'sets.id', '=', 'sets_cards.set_id')
					->where('cards.id', $id)
					->first();

		$titleTag = 'Edit Card | '.$card->name.' | ';
		$h2Tag = 'Edit Card | '.$card->name;

		$cardTags = CardTag::where('card_id', $id)->get();

		$tags = createTagsString($cardTags);

		return view('cards.edit', compact('titleTag', 'h2Tag', 'card', 'tags'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, [
			
			'rating' => 'required|integer|min:0',
			'tags' => 'string',
			'mana-sources' => 'string'
		]);

		$fCost = trim($request->input('f-cost'));

		if (is_numeric($fCost) && $fCost !== '') {

			if ($fCost < 0) {

				$message = 'Functional cost must be greater than -1 or "Variable" or "Land" or blank.';

				$request->flash();

				return redirect()->route('cards.edit', $id)->with('message', $message);
			}

			$fCost = (int)$fCost;
		}

		if ($fCost !== 'Variable' && $fCost !== 'Land' && $fCost !== '') {

			if (!is_int($fCost)) {

				$message = 'Functional cost must be an integer greater than -1 or "Variable" or "Land" or blank.';

				$request->flash();

				return redirect()->route('cards.edit', $id)->with('message', $message);                
			}            
		}

		if ($fCost === '') {

			$fCost = null;
		}

		$card = Card::find($id);

		$card->f_cost = $fCost;
		$card->rating = trim($request->input('rating'));
		$card->mana_sources = trim($request->input('mana-sources'));

		$card->save();

		$cardTags = CardTag::where('card_id', $id)->get();

		$tags = createTagsString($cardTags);

		if ($tags !== $request->input('tags')) {

			CardTag::where('card_id', $id)->delete();

			$newTags = trim($request->input('tags'));

			if ($newTags != '') {
				
				$cardTags = explode(' ', $newTags);

				foreach ($cardTags as $cardTag) {
					
					$eCardTag = new CardTag;

					$eCardTag->card_id = $id;
					$eCardTag->tag = $cardTag;

					$eCardTag->save();
				}
			}
		}

		$message = 'Success!';

		return redirect()->route('cards.edit', $id)->with('message', $message);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

}
