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

        # ddAll($decklistLines);

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
        }

        $eventDeck = new EventDeck;

        $eventDeck->player = $player;
        $eventDeck->finish = $finish;
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

        return redirect()->route('decks.index')->with('message', $message);
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
