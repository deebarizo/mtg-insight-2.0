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

		foreach ($set['cards'] as $card) {

			$cardIsBasicLand = $this->checkIfCardIsBasicLand($card);

			if ($cardIsBasicLand) {

				continue;
			}

			$this->storeCard($card, $set['id']);
		}
	}

	private function storeCard($card, $setId) {

		$eCard = new Card;

		
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