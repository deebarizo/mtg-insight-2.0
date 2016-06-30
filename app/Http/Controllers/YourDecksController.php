<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\CardTag;

use App\Models\Set;
use App\Models\SetCard;

use DB;

class YourDecksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titleTag = 'Your Decks | ';
        $h2Tag = 'Your Decks';

        return view('your_decks.index', compact('titleTag', 'h2Tag'));
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

        $cards = Card::select('cards.id', 
                              'cards.name', 
                              'cards.f_cost', 
                              'cards.mana_cost',
                              'cards.mana_sources')
                        ->with('sets_cards.set')
                        ->with('card_tags')
                        ->leftJoin('card_tags', function($join) {
      
                            $join->on('card_tags.card_id', '=', 'cards.id');
                        })
                        ->where(function($query) {

                            return $query->where('card_tags.tag', '!=', 'back-of-double-faced-card')
                                            ->orWhereNull('card_tags.tag');
                        })
                        ->get();

        # ddAll($cards);

        return view('your_decks.create', compact('titleTag', 'h2Tag', 'cards'));
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
