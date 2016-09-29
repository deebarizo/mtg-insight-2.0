<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Transaction;
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
			'tix' => 'required|integer|min:0',
		]);

		$type = trim($request->input('type'));
		$quantity = trim($request->input('quantity'));
		$cardName = trim($request->input('card'));
		$tix = trim($request->input('tix'));
		$note = trim($request->input('note'));

		if ($note == '') {

			$note = null;
		}

		if ($type === 'Buy' || $type === 'Sell') {

			if ($quantity == '') {

				$message = 'The '.$type.' type transaction requires a quantity.';

				return redirect()->route('transactions.create')->with('message', $message);
			}

			if ($cardName == '') {

				$message = 'The '.$type.' type transaction requires a card.';

				return redirect()->route('transactions.create')->with('message', $message);
			}		

			$card = Card::where('name', $cardName)->first();

			if ($card === null) {

				$message = 'The card, '.$cardName.', does not exist in the database.';

				return redirect()->route('transactions.create')->with('message', $message);			
			}	

			$cardId = $card->id;
		}

		$transaction = new Transaction;

		if ($type === 'Deposit' || $type === 'Withdraw') {

			$quantity = 0;
			$cardId = 1;
		
		}
		
		$transaction->type = $type;
		$transaction->quantity = $quantity;
		$transaction->card_id = $cardId;
		$transaction->tix = $tix;
		$transaction->note = $note;

		$transaction->save();

		$message = 'Success!';

		return redirect()->route('transactions.create')->with('message', $message);
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
