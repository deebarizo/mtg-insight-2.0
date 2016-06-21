<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\SetCard;

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
                    ->leftJoin('card_metagames', 'card_metagames.card_id', '=', 'cards.id')
                    ->orderBy('cards.f_cost')
                    ->groupBy('cards.name')
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
