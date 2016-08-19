<?php namespace App\UseCases;

use App\Models\EventDeckCopy;
use App\Models\YourDeckCopy;

class ManaProcessor {

	public function getManaSymbols($quantity, $manaCost, $numManaSymbols) {

		$manaSymbols = ['{W}', '{U}', '{B}', '{R}', '{G}', '{C}'];

		foreach ($manaSymbols as $key => $manaSymbol) {
			
			$numManaSymbols[$key] += $quantity * substr_count($manaCost, $manaSymbol);
		}

		return $numManaSymbols;
	}

	public function getManaSources($quantity, $manaSources, $numManaSources, $cardName, $deckType, $deckId) {

		if ($cardName === 'Evolving Wilds') {

			if ($deckType === 'Event Deck') {

				$lands = EventDeckCopy::join('cards', function($join) {
			  
											$join->on('cards.id', '=', 'event_deck_copies.card_id');
										})	
										->where('event_deck_id', $deckId)
										->where('role', 'md')
										->where('cards.f_cost', 'Land')
										->get();

				$manaSources = $this->getEvolvingWildsManaSources($lands);
			}
		}

		$numManaSources = $this->getManaSymbols($quantity, $manaSources, $numManaSources);

		return $numManaSources;
	}

	private function getEvolvingWildsManaSources($lands) {

		$manaSources = '';

		foreach ($lands as $land) {
			
			switch ($land->name) {
				
				case 'Plains':
					$manaSources .= '{W}';
					break;

				case 'Island':
					$manaSources .= '{U}';
					break;

				case 'Swamp':
					$manaSources .= '{B}';
					break;

				case 'Mountain':
					$manaSources .= '{R}';
					break;

				case 'Forest':
					$manaSources .= '{G}';
					break;

				case 'Wastes':
					$manaSources .= '{C}';
					break;
				
				default:
					break;
			}
		}

		return $manaSources;
	}

}