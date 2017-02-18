<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\SetCard;

use App\Models\CardMetagame;

use DB;

use App\UseCases\CardMetagameCreator;

class CardMetagameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titleTag = 'Card Metagame | ';
        $h2Tag = 'Card Metagame';

        $latestDate = CardMetagame::orderBy('date', 'desc')->take(1)->pluck('date')[0];

        $cards = DB::table('cards')
                    ->select('cards.id',
                                'cards.name',
                                'sets_cards.multiverseid',
                                'sets.code',
                                'cards.f_cost',
                                'cards.note',
                                'cards.mana_cost',
                                'card_metagames.md_percentage',
                                'card_metagames.sb_percentage',
                                'card_metagames.total_percentage')
                    ->join('sets_cards', 'sets_cards.card_id', '=', 'cards.id')
                    ->join('sets', 'sets.id', '=', 'sets_cards.set_id')
                    ->join('card_metagames', 'card_metagames.card_id', '=', 'cards.id')
                    ->orderBy('cards.f_cost')
                    ->where('card_metagames.date', $latestDate)
                    ->groupBy('cards.name')
                    ->get();

        return view('card_metagame.index', compact('titleTag', 'h2Tag', 'latestDate', 'cards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $titleTag = 'Create Card Metagame | ';
        $h2Tag = 'Create Card Metagame';

        return view('card_metagame.create', compact('titleTag', 'h2Tag'));
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
            
            'date' => 'required|date|unique:card_metagames'
        ]);

        $cardMetagameCreator = new CardMetagameCreator;

        $results = $cardMetagameCreator->create($request->input('date'));

        $message = $results->message;

        return redirect()->route('cards.index')->with('message', $message);
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
    
}
