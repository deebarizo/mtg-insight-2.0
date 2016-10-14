<?php namespace App\UseCases;

use App\Models\Transaction;

use App\Models\CardPrice;

class TransactionsProcessor {

	public function calculateOverview() {

		$latestDateForCardPrices = CardPrice::orderBy('created_at', 'desc')->take(1)->pluck('created_at')[0];

		$cards = Transaction::select('transactions.type',
										'transactions.quantity',
									 	'transactions.card_id',
									 	'transactions.tix',
									 	'transactions.price_per_copy',
									 	'cards.name',
									 	'card_prices.price')
							->join('cards', function($join) {
		  
								$join->on('cards.id', '=', 'transactions.card_id');
							})
							->join('card_prices', function($join) {
		  
								$join->on('card_prices.card_id', '=', 'cards.id');
							})
							->where('transactions.card_id', '!=', 1)
							->where('card_prices.created_at', $latestDateForCardPrices)
							->get();

		ddAll($cards);
	}

}