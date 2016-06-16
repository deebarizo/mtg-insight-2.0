<?php namespace App\UseCases;

ini_set('max_execution_time', 10800); // 10800 seconds = 3 hours

use App\Models\Set;
use App\Models\Card;
use App\Models\SetCard;
use App\Models\CardColorIdentity;
use App\Models\CardColor;
use App\Models\CardName;
use App\Models\CardSubtype;
use App\Models\CardSupertype;

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

		if (isset($card['colorIdentity'])) {

			$colorIdentities = $card['colorIdentity'];

			$this->storeColorIdentities($eCard->id, $colorIdentities);
		}

		if (isset($card['colors'])) {

			$this->storeColors($eCard->id, $card['colors']);
		}

		if (isset($card['names'])) {

			$this->storeNames($eCard->id, $card['names']);
		}

		if (isset($card['subtypes'])) {

			$this->storeSubtypes($eCard->id, $card['subtypes']);
		}

		if (isset($card['supertypes'])) {

			$this->storeSupertypes($eCard->id, $card['supertypes']);
		}
	}

	private function storeSupertypes($cardId, $supertypes) {

		foreach ($supertypes as $key => $supertype) {

			$cardSupertype = new CardSupertype;

			$cardSupertype->card_id = $cardId;
			$cardSupertype->supertype = $supertype;

			$cardSupertype->save();
		}
	}

	private function storeSubtypes($cardId, $subtypes) {

		foreach ($subtypes as $key => $subtype) {

			$cardSubtype = new CardSubtype;

			$cardSubtype->card_id = $cardId;
			$cardSubtype->subtype = $subtype;

			$cardSubtype->save();
		}
	}

	private function storeNames($cardId, $names) {

		foreach ($names as $key => $name) {
			
			$cardName = new CardName;

			$cardName->card_id = $cardId;
			$cardName->name = $name;

			$cardName->save();
		}
	}

	private function storeColors($cardId, $colors) {

		foreach ($colors as $key => $color) {
			
			$cardColor = new CardColor;

			$cardColor->card_id = $cardId;
			$cardColor->color = $color;

			$cardColor->save();
		}
	}

	private function storeColorIdentities($cardId, $colorIdentities) {

		foreach ($colorIdentities as $key => $colorIdentity) {
			
			$cardColorIdentity = new CardColorIdentity;

			$cardColorIdentity->card_id = $cardId;
			$cardColorIdentity->color_identity = $this->changeLetterToColor($colorIdentity);

			$cardColorIdentity->save();
		}
	}

	private function changeLetterToColor($colorIdentity) {

		switch ($colorIdentity) {
			
			case 'W':
				return 'White';

			case 'U':
				return 'Blue';

			case 'B':
				return 'Black';

			case 'R':
				return 'Red';

			case 'G':
				return 'Green';
			
			default:
				return $colorIdentity;
		}

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