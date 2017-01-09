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
use App\Models\CardType;

use Illuminate\Support\Facades\Input;

use Session;

class CockatriceXmlParser {

	public function parseXml($xmlFile) {

		$set = simplexml_load_file($xmlFile);

		$setCode = (string)$set->sets->set->name; // http://stackoverflow.com/questions/19329120/how-to-get-the-first-element-of-a-simplexml-object

		$eSet = Set::where('code', $setCode)->first(); 

		if (!$eSet) { 

			$this->message = 'The set "'.$set['code'].'" is missing from the database. Please add it manually to the database.';		

			return $this;
		}	

		$cards = $set->cards->card;

		foreach ($cards as $card) {

			# ddAll($card);

			$cardIsBasicLand = $this->checkIfCardIsBasicLand((string)$card->name);

			if ($cardIsBasicLand) {

				continue;
			}

			$cardExists = Card::where('name', (string)$card->name)->first();

			if ($cardExists) {

				$this->updateExistingCard($card, $eSet->id);

				continue;
			}

			$this->storeNewCard($card, $eSet->id);
		}

		$this->message = 'Success!';

		return $this;
	}

	private function updateExistingCard($card, $setId) {

		$eCard = Card::where('name', (string)$card->name)->first();

		$this->storeOrUpdateCard($eCard, $card, $setId);
	}

	private function storeNewCard($card, $setId) {

		$eCard = new Card;

		$eCard->name = (string)$card->name;

		$this->storeOrUpdateCard($eCard, $card, $setId);
	}

	private function storeOrUpdateCard($eCard, $card, $setId) {

		if (isset($card->manacost)) {

			$rawManaCost = trim((string)$card->manacost);

			if ($rawManaCost != '') {

				$manaCostCharacters = str_split($rawManaCost);

				$manaCost = '';

				foreach ($manaCostCharacters as $manaCostCharacter) {
					
					$manaCost .= '{'.$manaCostCharacter.'}';
				}

				$eCard->mana_cost = $manaCost;
				$eCard->f_mana_cost = $manaCost;

				# ddAll($eCard);
			
			} else {

				$eCard->mana_cost = null;
				$eCard->f_mana_cost = null;				
			}

		} else {

			$eCard->mana_cost = null;
			$eCard->f_mana_cost = null;
		}

		if (isset($card->cmc)) {

			$cmc = trim((string)$card->cmc);

			if ($cmc != '') {

				$eCard->cmc = $cmc;
			
			} else {

				$cmc = null;

				$eCard->cmc = null;
			}
		
		} else {

			$cmc = null;

			$eCard->cmc = null;
		}

		$eCard->middle_text = (string)$card->type;

		if (isset($card->text)) {

			$rulesText = trim((string)$card->text);

			if ($rulesText != '') {

				$eCard->rules_text = $rulesText;

			} else {

				$eCard->rules_text = null;
			}
		
		} else {

			$eCard->rules_text = null;
		}

		if (strpos($eCard->middle_text, 'Land') !== false) {

			$eCard->f_cost = 'Land';
		
		} else {

			$eCard->f_cost = $cmc;
		}

		$eCard->layout = 'normal';

		$eCard->save();

		$setCard = SetCard::where('set_id', $setId)->where('card_id', $eCard->id)->first();

		if (!$setCard) {

			$setCard = new SetCard;
		}

		$setCard->set_id = $setId;
		$setCard->card_id = $eCard->id;
		$setCard->rarity = 'Common';
		$setCard->multiverseid = 777;

		$setCard->save();
	}

	private function checkIfCardIsBasicLand($cardName) {

		# dd($cardName);

		if ($cardName === 'Plains') {

			return true;
		}

		if ($cardName === 'Island') {

			return true;
		}

		if ($cardName === 'Swamp') {

			return true;
		}

		if ($cardName === 'Mountain') {

			return true;
		}

		if ($cardName === 'Forest') {

			return true;
		}

		return false;
	}

}