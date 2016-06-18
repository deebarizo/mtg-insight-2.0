<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Event;
use App\Models\EventDeck;

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

        return view('decks.index', compact('titleTag', 'h2Tag'));
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

        $events = Event::take(7)->orderBy('date', 'desc')->get();

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

        $deck = EventDeck::where('player', $player)
                         ->where('finish', $finish)
                         ->where('event_id', $eventId)
                         ->first();

        if ($deck) {

            $message = 'This deck already exists in the database.';

            $request->flash();

            return redirect()->route('decks.create')->with('message', $message);
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
