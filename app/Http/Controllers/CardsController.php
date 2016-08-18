<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;

use App\Models\Card;
use App\Models\SetCard;
use App\Models\Set;
use App\Models\CardTag;
use App\Models\CardPrice;

use App\Models\CardMetagame;

use App\UseCases\CardsTableCreator;
use App\UseCases\FileUploader;

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

		$firstSet = [

			'code' => 'DTK'
		];

		$firstSet['id'] = Set::where('code', $firstSet['code'])->pluck('id')[0];

		$lastSet = [

			'code' => 'EMN'
		];

		$lastSet['id'] = Set::where('code', $lastSet['code'])->pluck('id')[0];

		$cardsTableCreator = new CardsTableCreator;

		list($latestDateForCardMetagame, $latestDateForCardPrices, $cards, $fCosts, $sets, $colors) = $cardsTableCreator->createCardsTable($firstSet, $lastSet);

		return view('cards.index', compact('titleTag', 'h2Tag', 'latestDateForCardMetagame', 'latestDateForCardPrices', 'cards', 'fCosts', 'sets', 'colors'));
	}

	public function post_rotation_cards()
	{
		$titleTag = 'Cards (Post Rotation) | ';
		$h2Tag = 'Cards (Post Rotation)';

		$firstSet = [

			'code' => 'BFZ'
		];

		$firstSet['id'] = Set::where('code', $firstSet['code'])->pluck('id')[0];

		$lastSet = [

			'code' => 'KLD'
		];

		$lastSet['id'] = Set::where('code', $lastSet['code'])->pluck('id')[0];

		$cardsTableCreator = new CardsTableCreator;

		list($latestDateForCardMetagame, $latestDateForCardPrices, $cards, $fCosts, $sets, $colors) = $cardsTableCreator->createCardsTable($firstSet, $lastSet);

		return view('cards.index', compact('titleTag', 'h2Tag', 'latestDateForCardMetagame', 'latestDateForCardPrices', 'cards', 'fCosts', 'sets','colors'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
        $titleTag = 'Create Card | ';
        $h2Tag = 'Create Card';

        $latestSetCode = Set::orderBy('id', 'desc')->take(1)->pluck('code')[0];

        return view('cards.create', compact('titleTag', 'h2Tag', 'latestSetCode'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			
			'name' => 'required|string',
			'set-code' => 'required',
			'f-cost' => 'required',
			'rating' => 'required|integer|min:0',
			'rarity' => 'required',
			'png' => 'required'
		]);

		$card = new Card;

		$card->name = $request->input('name');
		$card->mana_cost = ($request->input('mana-cost') != '') ? $request->input('mana-cost') : null;
		$card->f_mana_cost = ($request->input('f-mana-cost') != '') ? $request->input('f-mana-cost') : null;
		$card->mana_sources = ($request->input('mana-sources') != '') ? $request->input('mana-sources') : null;
		$card->f_cost = $request->input('f-cost');
		$card->rating = $request->input('rating');

		$card->save();

		//////////////////////////////////////////////////

		$setCard = new SetCard;

		$setCard->set_id = Set::where('code', $request->input('set-code'))->pluck('id')[0];
		$setCard->card_id = $card->id;
		$setCard->rarity = $request->input('rarity');
		$setCard->multiverseid = 111;

		$setCard->save(); 

		//////////////////////////////////////////////////

		$fileUploader = new FileUploader;

		$fileUploader->uploadCardImage($request);

		//////////////////////////////////////////////////		

		$message = 'Success!';

		return redirect()->route('cards.create')->with('message', $message);
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
