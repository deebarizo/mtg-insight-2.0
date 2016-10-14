<?php namespace App\UseCases;

use App\Models\Transaction;

use App\Models\CardPrice;

use App\Models\Card;
use App\Models\Set;
use App\Models\SetCard;

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

		$totalRevenue = 0;

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
				'current_total_price' => 0,
				'profit' => 0,
				'profit_percentage' => 0,
				'ownership_percentage' => 0,
				'set_name' => null,
				'set_code' => null,
				'wikiprice_card_number' => Card::where('id', $cardId)->pluck('wikiprice_card_number')[0]
			];

			foreach ($transactions as $transaction) {
				
				if ($transaction->type === 'Buy') {

					$card['quantity'] += $transaction->quantity;
					$card['tix'] += $transaction->tix;
				}
			}

			$card['price_per_copy'] = $card['tix'] / $card['quantity'];

			$card['current_total_price'] = $card['mtg_goldfish_price'] * $card['quantity'];

			$card['profit'] = ($card['mtg_goldfish_price'] * $card['quantity']) - ($card['tix']);

			$card['profit_percentage'] = $card['profit'] / $card['tix'] * 100;

			$card['ownership_percentage'] = $card['tix'] / $tixInCards * 100;

			$card['set_name'] = Card::join('sets_cards', function($join) {
      
	                    					$join->on('sets_cards.card_id', '=', 'cards.id');
				                        })
				                        ->join('sets', function($join) {
				      
				                            $join->on('sets_cards.set_id', '=', 'sets.id');
				                        }) 
				                        ->where('cards.id', $cardId)
				                        ->pluck('sets.name')[0];

			$card['set_code'] = Card::join('sets_cards', function($join) {
      
	                    					$join->on('sets_cards.card_id', '=', 'cards.id');
				                        })
				                        ->join('sets', function($join) {
				      
				                            $join->on('sets_cards.set_id', '=', 'sets.id');
				                        }) 
				                        ->where('cards.id', $cardId)
				                        ->pluck('sets.code')[0];

			array_push($cards, $card);

			$totalRevenue += $card['current_total_price'];
		}

		$totalProfit = $totalRevenue - $tixInCards;

		$totalProfitPercentage = $totalProfit / $tixInCards * 100;

		$overview = [

			'latestDateForCardPrices' => $latestDateForCardPrices,
			'tixAvailable' => $tixAvailable,
			'tixInCards' => $tixInCards,
			'totalRevenue' => $totalRevenue,
			'totalProfit' => $totalProfit,
			'totalProfitPercentage' => $totalProfitPercentage,
			'cards' => $cards
		];

		# ddAll($overview);

		return $overview;
	}

}