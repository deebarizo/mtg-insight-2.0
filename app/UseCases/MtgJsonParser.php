<?php namespace App\UseCases;

ini_set('max_execution_time', 10800); // 10800 seconds = 3 hours

use App\Set;
use App\Card;
use App\SetCard;

use Illuminate\Support\Facades\Input;

use Session;

class MtgJsonParser {

	public function parseJson($jsonFile) {

		$jsonString = file_get_contents($jsonFile);
		$set = json_decode($jsonString, true);

		$set['id'] = Set::where('code', $set['code'])->pluck('id'); 

		if (count($set['id']) === 0) { // this variable is an array

			$this->message = 'The set "'.$set['code'].'" is missing from the database. Please add it manually to the database.';		

			return $this;
		}	

		$this->basicLandCount = 0; // this is for testing	

		foreach ($set['cards'] as $card) {

			$cardIsBasicLand = $this->checkIfCardIsBasicLand($card);

			if ($cardIsBasicLand) {

				$this->basicLandCount++;

				continue;
			}

			// $this->storeCard($card, $set['id']);
		}

		return $this;
	}

	private function storeCard($card, $setId) {

		$eCard = new Card;

		$eCard->name = $card['name'];

		if (isset($card['color'])) {

		}
	}

/*

color varchar(255) 
mana_cost varchar(255) 
cmc int(11) 
type varchar(255) 
rules_text text 
power varchar(255) 
toughness varchar(255) 
loyalty varchar(255) 
f_cost varchar(255) 
note text 

Array
(
    [cmc] => 6
    [manaCost] => {6}
    [multiverseid] => 394681
    [power] => 4
    [rarity] => Uncommon
    [text] => Flying
    [toughness] => 4
    [type] => Creature — Dragon Spirit
)
*/

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