<?php namespace App\UseCases;

use App\Models\Transaction;

use App\Models\CardPrice;

class TransactionsProcessor {

	public function calculateOverview() {

		$latestDateForCardPrices = CardPrice::orderBy('created_at', 'desc')->take(1)->pluck('created_at')[0];

		$cardIds = Transaction::select('transactions.card_id')
								->join('cards', function($join) {
			  
									$join->on('cards.id', '=', 'transactions.card_id');
								})
								->where('transactions.card_id', '!=', 1)
								->groupBy('card_id')
								->lists('transactions.card_id')
								->toArray();

		$totalTix = [

			'deposit' => Transaction::where('type', 'Deposit')->sum('tix'),
			'withdraw' =>Transaction::where('type', 'Withdraw')->sum('tix'),
			'buy' => Transaction::where('type', 'Buy')->sum('tix'),
			'sell' => Transaction::where('type', 'Sell')->sum('tix')
		];

		$tixAvailable = $totalTix['deposit'] - $totalTix['withdraw'] - $totalTix['buy'] + $totalTix['sell'];

		$tixInCards = $totalTix['buy'] - $totalTix['sell'];

		$cards = [];

		foreach ($cardIds as $cardId) {

			$transactions = Transaction::select('transactions.type',
												'transactions.quantity',
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
								->where('transactions.card_id', $cardId)
								->get(); 

			$card = [

				'quantity' => 0,
				'tix' => 0,
				'price_per_copy' => 0,
				'name' => $transactions[0]->name,
				'mtg_goldfish_price' => $transactions[0]->price,
				'profit' => 0,
				'profit_percentage' => 0,
				'ownership_percentage' => 0
			];

			foreach ($transactions as $transaction) {
				
				if ($transaction->type === 'Buy') {

					$card['quantity'] += $transaction->quantity;
					$card['tix'] += $transaction->tix;
				}
			}

			$card['price_per_copy'] = numFormat($card['tix'] / $card['quantity'], 2);

			$profit = ($card['mtg_goldfish_price'] * $card['quantity']) - ($card['tix']);
			$card['profit'] = numFormat($profit, 2);

			$card['profit_percentage'] = numFormat($card['profit'] / $card['tix'] * 100, 2);

			$card['ownership_percentage'] = numFormat($card['tix'] / $tixInCards * 100, 2);

			array_push($cards, $card);
		}

		$overview = [

			'tixAvailable' => $tixAvailable,
			'tixInCards' => $tixInCards,
			'cards' => $cards
		];

		# ddAll($overview);

		return $overview;
	}

}