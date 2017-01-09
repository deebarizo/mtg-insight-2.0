<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\CardTag;

use App\Models\Set;
use App\Models\SetCard;

use App\Models\YourDeck;
use App\Models\YourDeckCopy;

use DB;

class YourDecksController extends Controller
{
    private $firstSetCode = 'BFZ';
    private $latestSetCode = 'AER';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titleTag = 'Your Decks | ';
        $h2Tag = 'Your Decks';

        // http://www.xaprb.com/blog/2006/12/07/how-to-select-the-firstleastmax-row-per-group-in-sql/

        $yourDecks = DB::select(DB::raw('SELECT f.id, f.name, f.slug, f.saved_at, sets.code as set_code
                                         FROM (
                                            SELECT name, MAX(unix_saved_at) as max_unix_saved_at
                                            from your_decks group by name                                            
                                         ) AS x INNER JOIN your_decks as f on f.name = x.name and f.unix_saved_at = x.max_unix_saved_at
                                         JOIN sets ON sets.id = f.latest_set_id
                                         ORDER BY unix_saved_at desc'));

        # ddAll($yourDecks);

        return view('your_decks.index', compact('titleTag', 'h2Tag', 'yourDecks'));
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

        $firstSetCode = $this->firstSetCode;
        $latestSetCode = $this->latestSetCode;

        $cards = $this->createCardsTable($firstSetCode, $latestSetCode);

        # ddAll($cards);

        return view('your_decks.create', compact('titleTag', 'h2Tag', 'cards', 'latestSetCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request['decklist'];

        $yourDeck = new YourDeck;

        $yourDeck->latest_set_id = Set::where('code', $input['latestSetCode'])->pluck('id')[0];
        $yourDeck->name = $input['name'];
        $yourDeck->slug = preg_replace('/\s/', '_', $input['name']);
        $yourDeck->md_count = $input['mdCount'];
        $yourDeck->sb_count = $input['sbCount'];
        $yourDeck->saved_at = date('Y-m-d h:i:sa');
        $yourDeck->unix_saved_at = strtotime(date('Y-m-d h:i:sa'));

        $yourDeck->save();  

        foreach ($input['copies'] as $copy) {

            $yourDeckCopy = new YourDeckCopy;

            $yourDeckCopy->your_deck_id = $yourDeck->id;
            $yourDeckCopy->quantity = $copy['quantity'];
            $yourDeckCopy->card_id = $copy['cardId'];
            $yourDeckCopy->role = $copy['role'];

            $yourDeckCopy->save();
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($latestSetCode, $slug)
    {
        $yourDeck = YourDeck::where('slug', $slug)->orderBy('unix_saved_at', 'desc')->first();

        $titleTag = $yourDeck->name.' | ';
        $h2Tag = $yourDeck->name;

        $firstSetCode = $this->firstSetCode;
        $latestSetCode = $this->latestSetCode;

        $cards = $this->createCardsTable($firstSetCode, $latestSetCode);

        $copies = [

            'md' => $this->getCopies($yourDeck->id, 'md'),
            'sb' => $this->getCopies($yourDeck->id, 'sb')
        ];

        # ddAll($copies);

        return view('your_decks.show', compact('titleTag', 'h2Tag', 'cards', 'latestSetCode', 'yourDeck', 'copies'));
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


    /****************************************************************************************
    HELPERS
    ****************************************************************************************/

    private function getCopies($yourDeckId, $role) {

        return YourDeckCopy::select('your_deck_copies.role',
                                    'cards.name',
                                    'cards.mana_cost',
                                    'your_deck_copies.quantity',
                                    'your_deck_copies.card_id',
                                    'cards.f_mana_cost',
                                    'cards.f_cost',
                                    'cards.mana_sources',
                                    'sets.code')
                            ->join('cards', function($join) {
          
                                $join->on('cards.id', '=', 'your_deck_copies.card_id');
                            })
                            ->join('sets_cards', function($join) {
          
                                $join->on('sets_cards.card_id', '=', 'cards.id');
                            })
                            ->join('sets', function($join) {
          
                                $join->on('sets.id', '=', 'sets_cards.set_id');
                            })
                            ->groupBy('your_deck_copies.card_id')
                            ->orderBy(DB::raw('FIELD(cards.f_cost, "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "Variable", "Land")'))
                            ->where('your_deck_copies.your_deck_id', $yourDeckId)
                            ->where('your_deck_copies.role', $role)
                            ->get();
    }

    private function createCardsTable($firstSetCode, $latestSetCode) {

        $firstSetId = Set::where('code', $firstSetCode)->pluck('id')[0];
        $latestSetId = Set::where('code', $latestSetCode)->pluck('id')[0];

        return Card::select('cards.id', 
                              'cards.name', 
                              'cards.f_cost', 
                              'cards.mana_cost',
                              'cards.f_mana_cost', 
                              'cards.mana_sources')
                        ->with('sets_cards.set')
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
                        ->where(function($query) {

                            return $query->where('card_tags.tag', '!=', 'back-of-double-faced-card')
                                            ->orWhereNull('card_tags.tag');
                        })
                        ->where(function($query) use($firstSetId, $latestSetId) {

                            return $query->where('sets.id', '>=', $firstSetId)
                                            ->where('sets.id', '<=', $latestSetId)
                                            ->orWhere('sets.id', 1); // LANDS (basic lands)
                        })                        
                        ->get();       
    }

}
