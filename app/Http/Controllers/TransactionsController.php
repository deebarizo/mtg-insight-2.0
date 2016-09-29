<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Card;
use App\Models\CardPrice;

class TransactionsController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$titleTag = 'Transactions | ';
		$h2Tag = 'Transactions';
		
		return view('transactions/index', compact('titleTag', 'h2Tag'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$titleTag = 'Create Transaction | ';
		$h2Tag = 'Create Transaction';
		
		return view('transactions/create', compact('titleTag', 'h2Tag'));
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
			
			'type' => 'required|string',
			'set-code' => 'required',
			'f-cost' => 'required',
			'tix' => 'required|integer|min:0',
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
