<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Event;
use App\Models\EventDeck;
use App\Models\EventDeckCopy;

use App\Models\Card;

use DB;

class DecksController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$titleTag = 'Decks | ';
		$h2Tag = 'Decks';

		$decks = DB::table('event_decks')
					->select(DB::raw('event_decks.id,
									  event_decks.player,
									  event_decks.finish,
									  event_decks.event_id,
									  events.name as event_name,
									  events.location as event_location,
									  events.date as event_date'))
					->join('events', 'events.id', '=', 'event_decks.event_id')
					->orderBy('date', 'desc')
					->orderBy('event_id', 'desc')
					->orderBy('finish', 'asc')
					->get();

		return view('decks.index', compact('titleTag', 'h2Tag', 'decks'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$titleTag = 'Create Deck | ';
		$h2Tag = 'Create Deck';

		$events = Event::take(7)->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

		return view('decks.create', compact('titleTag', 'h2Tag', 'events'));
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
			
			'player' => 'required',
			'finish' => 'required',
			'event-id' => 'required', 
			'decklist' => 'required'
		]);

		$player = trim($request->input('player'));
		$player = strtolower($player);
		$player = ucwords($player);

		$finish = trim($request->input('finish'));
		$eventId = trim($request->input('event-id'));
		$decklist = trim($request->input('decklist'));

		$deck = EventDeck::where('player', $player)
						 ->where('finish', $finish)
						 ->where('event_id', $eventId)
						 ->first();

		if ($deck) {

			$message = 'This deck already exists in the database.';

			$request->flash();

			return redirect()->route('decks.create')->with('message', $message);
		}

		$decklistLines = explode(PHP_EOL, $decklist);

		$copies = [];

		$role = 'md';

		$roleCount = [

			'md' => 0,
			'sb' => 0
		];

		foreach ($decklistLines as $key => $decklistLine) {

			if (!is_numeric(substr($decklistLine, 0, 1))) {

				$role = 'sb';

				continue;
			}
			
			$copy = [

				'quantity' => trim(preg_replace("/(\d+)(.+)/", "$1", $decklistLine)),
				'card_name' => trim(preg_replace("/(\d+)(\s+)(.+)/", "$3", $decklistLine)),
				'role' => $role
			];

			$card = Card::where('name', $copy['card_name'])->first();

			if (!$card) {

				$message = 'The card, '.$copy['card_name'].', does not exist in the database.';

				$request->flash();

				return redirect()->route('decks.create')->with('message', $message);                
			}

			$copy['card_id'] = $card->id;

			$copies[] = $copy;

			if ($role === 'md') {

				$roleCount['md'] += $copy['quantity'];
			}

			if ($role === 'sb') {

				$roleCount['sb'] += $copy['quantity'];
			}
		}

		if ($roleCount['md'] < 60) {

			$message = 'This deck only has '.$roleCount['md'].' main deck cards.';

			$request->flash();

			return redirect()->route('decks.create')->with('message', $message);                
		}

		if ($roleCount['sb'] > 15) {

			$message = 'This deck has '.$roleCount['sb'].' sideboard cards.';

			$request->flash();

			return redirect()->route('decks.create')->with('message', $message);                
		}

		$eventDeck = new EventDeck;

		$eventDeck->player = $player;
		$eventDeck->finish = $finish;
		$eventDeck->md_count = $roleCount['md'];
		$eventDeck->sb_count = $roleCount['sb'];
		$eventDeck->event_id = $eventId;

		$eventDeck->save();

		foreach ($copies as $key => $copy) {

			$eventDeckCopy = new EventDeckCopy;

			$eventDeckCopy->event_deck_id = $eventDeck->id;
			$eventDeckCopy->quantity = $copy['quantity'];
			$eventDeckCopy->card_id = $copy['card_id'];
			$eventDeckCopy->role = $copy['role'];

			$eventDeckCopy->save();
		}

		$message = 'Success!';

		return redirect()->route('decks.create')->with('message', $message);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$deck = EventDeck::with('event')->where('id', $id)->first();

		$roles = ['md', 'sb'];

		$copies = [

			'md' => [],
			'sb' => []
		];

		foreach ($roles as $key => $role) {

			$copies[$role] = EventDeckCopy::with('card.sets_cards.set')
											->where('event_deck_id', $id)
											->where('role', $role)
											->join('cards', function($join) {
						  
												$join->on('cards.id', '=', 'event_deck_copies.card_id');
											})
											->orderBy(DB::raw('FIELD(cards.f_cost, "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "Variable", "Land")'))
											->orderBy('quantity', 'desc')
											->get();
		}

		$metadata = [];

		$metadata['numMdCards'] = EventDeckCopy::where('event_deck_id', $id)
												->where('role', 'md')
												->sum('quantity');

		$metadata['numNonlandCards'] = EventDeckCopy::join('cards', function($join) {
						  
														$join->on('cards.id', '=', 'event_deck_copies.card_id');
													})	
													->where('event_deck_id', $id)
													->where('role', 'md')
													->where('cards.f_cost', '!=', 'Land')
													->sum('quantity');

		$metadata['numLandCards'] = EventDeckCopy::join('cards', function($join) {
						  
														$join->on('cards.id', '=', 'event_deck_copies.card_id');
													})	
													->where('event_deck_id', $id)
													->where('role', 'md')
													->where('cards.f_cost', 'Land')
													->sum('quantity');

		$metadata['numSbCards'] = EventDeckCopy::where('event_deck_id', $id)
												->where('role', 'sb')
												->sum('quantity');

		$manaCurveDrops = [1, 2, 3, 4, 5, 6, 7, "Variable"];

		$manaCurve = [];

		foreach ($manaCurveDrops as $key => $drop) {

			if ($drop !== 7) {

				$manaCurve[] = (int)EventDeckCopy::join('cards', function($join) {
				  
												$join->on('cards.id', '=', 'event_deck_copies.card_id');
											})	
											->where('event_deck_id', $id)
											->where('role', 'md')
											->where('cards.f_cost', $drop)
											->sum('quantity');

			} elseif ($drop === 7) {
				
				$manaCurve[] = (int)EventDeckCopy::join('cards', function($join) {
				  
												$join->on('cards.id', '=', 'event_deck_copies.card_id');
											})	
											->where('event_deck_id', $id)
											->where('role', 'md')
											->where('cards.f_cost', '>=', $drop)
											->where('cards.f_cost', '!=', 'Variable')
											->where('cards.f_cost', '!=', 'Land')
											->sum('quantity');
			}

			if ($manaCurve[$key] == 0) {

				$manaCurve[$key] = null;
			}
		}

		$manaCurve = json_encode($manaCurve);

		$colorStats = [

			'symbols' => [0, 0, 0, 0, 0, 0],
			'sources' => [0, 0, 0, 0, 0, 0]
		];

		foreach ($colorStats as $key => &$colorStat) {

			foreach ($copies['md'] as $copy) {
			
				switch ($key) {

					case 'symbols':
						$colorStat = $this->getManaSymbols($copy->quantity, $copy->mana_cost, $colorStat);
						break;
					
					case 'sources':
						$colorStat = $this->getManaSources($copy->quantity, $copy->mana_sources, $colorStat, $copy->name, $id);
						break;
				}
			}

			foreach ($colorStat as &$value) {
				
				if ($value == 0) {

					$value = null;
				}
			}

			unset($value);

			$colorStat = json_encode($colorStat);
		}

		unset($colorStat);

		# ddAll($colorStats);

		$titleTag = 'Deck '.$id.' by '.$deck->player.' | '.$deck->event->name.' '.$deck->event->location.' | ';
		$h2Tag = 'Deck '.$id.' by '.$deck->player;

		return view('decks.show', compact('titleTag', 'h2Tag', 'deck', 'copies', 'roles', 'metadata', 'manaCurve', 'colorStats'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
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
		//
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

	private function getManaSymbols($quantity, $manaCost, $numManaSymbols) {

		$manaSymbols = ['{W}', '{U}', '{B}', '{R}', '{G}', '{C}'];

		foreach ($manaSymbols as $key => $manaSymbol) {
			
			$numManaSymbols[$key] += $quantity * substr_count($manaCost, $manaSymbol);
		}

		return $numManaSymbols;
	}

	private function getManaSources($quantity, $manaSources, $numManaSources, $cardName, $eventDeckId) {

		if ($cardName === 'Evolving Wilds') {

			$lands = EventDeckCopy::join('cards', function($join) {
		  
										$join->on('cards.id', '=', 'event_deck_copies.card_id');
									})	
									->where('event_deck_id', $eventDeckId)
									->where('role', 'md')
									->where('cards.f_cost', 'Land')
									->get();

			$manaSources = $this->getEvolvingWildsManaSources($lands);
		}

		$numManaSources = $this->getManaSymbols($quantity, $manaSources, $numManaSources);

		return $numManaSources;
	}

	private function getEvolvingWildsManaSources($lands) {

		$manaSources = '';

		foreach ($lands as $land) {
			
			switch ($land->name) {
				
				case 'Plains':
					$manaSources .= '{W}';
					break;

				case 'Island':
					$manaSources .= '{U}';
					break;

				case 'Swamp':
					$manaSources .= '{B}';
					break;

				case 'Mountain':
					$manaSources .= '{R}';
					break;

				case 'Forest':
					$manaSources .= '{G}';
					break;

				case 'Wastes':
					$manaSources .= '{C}';
					break;
				
				default:
					break;
			}
		}

		return $manaSources;
	}
	
}
