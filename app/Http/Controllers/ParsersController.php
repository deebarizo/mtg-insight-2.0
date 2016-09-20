<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\UseCases\FileUploader;
use App\UseCases\MtgJsonParser;

use App\Models\Card;

class ParsersController extends Controller {

	public function parseMtgJson(Request $request) {

		$fileUploader = new FileUploader;

		$jsonFile = $fileUploader->uploadMtgJson($request);

		$mtgJsonParser = new MtgJsonParser;

        $results = $mtgJsonParser->parseJson($jsonFile);

        $message = $results->message;

		return redirect()->route('admin.parsers.mtg_json')->with('message', $message);
	}

	public function fixManaCosts() { // this doesn't work

		$cards = Card::join('sets_cards', function($join) {
		  
							$join->on('sets_cards.card_id', '=', 'cards.id');
						})
						->where('sets_cards.set_id', 9)
						->get();

		foreach ($cards as $card) {
			
			$card->mana_cost = preg_replace("/(\S)/", "{\$1}", $card->mana_cost);
			$card->f_mana_cost = preg_replace("/(\S)/", "{\$1}", $card->f_mana_cost);

			$card->save();
		}

		$message = 'Success!';

		return redirect()->route('admin.parsers.fix_mana_costs')->with('message', $message);
	}

}