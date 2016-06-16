<?php namespace App\UseCases;

ini_set('max_execution_time', 10800); // 10800 seconds = 3 hours

use App\Models\Set;
use App\Models\Card;
use App\Models\SetCard;

use Illuminate\Support\Facades\Input;

use Session;

class MtgJsonParser {

	public function parseJson($jsonFile) {

		$jsonString = file_get_contents($jsonFile);
		$set = json_decode($jsonString, true);

		$eSet = Set::where('code', $set['code'])->first(); 

		if (!$eSet) { 

			$this->message = 'The set "'.$set['code'].'" is missing from the database. Please add it manually to the database.';		

			return $this;
		}	

		$this->basicLandCount = 0; // this is for testing
		$this->cardExistsCount = 0; // this is for testing	

		foreach ($set['cards'] as $card) {

			$cardIsBasicLand = $this->checkIfCardIsBasicLand($card);

			if ($cardIsBasicLand) {

				$this->basicLandCount++;

				continue;
			}


			$cardExists = Card::where('name', $card['name'])->first();

			if ($cardExists) {

				$this->cardExistsCount++;

				continue;
			}

			$this->storeCard($card, $eSet->id);
		}

		return $this;
	}

	private function storeCard($card, $setId) {

		$eCard = new Card;

		$eCard->name = $card['name'];

		if (isset($card['manaCost'])) {

			$eCard->mana_cost = $card['manaCost'];
		
		} else {

			$eCard->mana_cost = null;
		}

		if (isset($card['cmc'])) {

			$eCard->cmc = $card['cmc'];
		
		} else {

			$eCard->cmc = null;
		}

		$eCard->middle_text = $card['type'];

		if (isset($card['text'])) {

			$eCard->rules_text = $card['text'];
		
		} else {

			$eCard->rules_text = null;
		}

		if (isset($card['power'])) {

			$eCard->power = $card['power'];
		
		} else {

			$eCard->power = null;
		}

		if (isset($card['toughness'])) {

			$eCard->toughness = $card['toughness'];
		
		} else {

			$eCard->toughness = null;
		}

		if (isset($card['loyalty'])) {

			$eCard->loyalty = $card['loyalty'];
		
		} else {

			$eCard->loyalty = null;
		}

		if (isset($card['cmc'])) {

			$eCard->f_cost = $card['cmc'];
		
		} else {

			$eCard->f_cost = null;
		}

		$eCard->note = null;

		$eCard->layout = $card['layout'];

		$eCard->save();

		$setCard = new SetCard;

		$setCard->set_id = $setId;
		$setCard->card_id = $eCard->id;
		$setCard->rarity = $card['rarity'];
		$setCard->multiverseid = $card['multiverseid'];

		$setCard->save();
	}

	private function checkIfCardIsBasicLand($card) {

		if ($card['name'] === 'Plains') {

			return true;
		}

		if ($card['name'] === 'Island') {

			return true;
		}

		if ($card['name'] === 'Swamp') {

			return true;
		}

		if ($card['name'] === 'Mountain') {

			return true;
		}

		if ($card['name'] === 'Forest') {

			return true;
		}

		return false;
	}

}