<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\SetCard;
use App\Models\CardTag;

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

        $latestDate = CardMetagame::orderBy('date', 'desc')->take(1)->pluck('date')[0];

        $cards = Card::select('cards.id', 
                              'cards.name', 
                              'cards.f_cost', 
                              'cards.mana_cost')
                        ->with('sets_cards.set')
                        ->with('card_tags')
                        ->with(['card_metagames' => function ($query) use ($latestDate) { // https://laravel.com/docs/5.2/eloquent-relationships#constraining-eager-loads

                            $query->where('date', $latestDate);
                        }])
                        ->get();

        return view('cards.index', compact('titleTag', 'h2Tag', 'latestDate', 'cards'));
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
                             'sets.code')
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
        $fCost = $request->input('f-cost');

        if (is_numeric($fCost) && $fCost !== '') {

            if ($fCost < 0) {

                $message = 'Functional cost must be greater than -1 or "variable" or blank.';

                $request->flash();

                return redirect()->route('cards.edit', $id)->with('message', $message);
            }

            $fCost = (int)$fCost;
        }

        if ($fCost !== 'variable' && $fCost !== '') {

            if (!is_int($fCost)) {

                $message = 'Functional cost must be an integer greater than -1 or "variable" or blank.';

                $request->flash();

                return redirect()->route('cards.edit', $id)->with('message', $message);                
            }            
        }

        if ($fCost === '') {

            $fCost = null;
        }

        $card = Card::find($id);

        $card->f_cost = $fCost;

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

        return redirect()->route('cards.index')->with('message', $message);
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
