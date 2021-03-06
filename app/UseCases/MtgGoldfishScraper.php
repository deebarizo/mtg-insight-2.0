<?php namespace App\UseCases;

ini_set('max_execution_time', 10800); // 10800 seconds = 3 hours

use App\Models\Card;
use App\Models\Set;
use App\Models\SetCard;
use App\Models\CardTag;
use App\Models\CardPrice;

use DB;

use Goutte\Client;
use vendor\symfony\DomCrawler\Crawler;

class MtgGoldfishScraper {

	public function scrapePrices($firstSetCode, $lastSetCode) {

		$setIds = [

			'first' => Set::where('code', $firstSetCode)->pluck('id')[0],
			'last' => Set::where('code', $lastSetCode)->pluck('id')[0]
		];;

        $cards = Card::select(DB::raw('cards.name as name,
        					  		   cards.id,
        					  		   sets.name as set_name'))
                        ->with('sets_cards.set')
                        ->join('sets_cards', function($join) {
      
                            $join->on('sets_cards.card_id', '=', 'cards.id');
                        })
                        ->join('sets', function($join) {
      
                            $join->on('sets_cards.set_id', '=', 'sets.id');
                        })  
                        ->leftJoin('card_tags', function($join) {
      
                            $join->on('card_tags.card_id', '=', 'cards.id');
                        })                      
                        ->where(function($query) {

                            return $query->where('sets_cards.rarity', 'Rare')
                                            ->orWhere('sets_cards.rarity', 'Mythic Rare');
                        })
                        ->where(function($query) {

                            return $query->where('card_tags.tag', '!=', 'back-of-double-faced-card')
                                            ->orWhereNull('card_tags.tag');
                        })
                        ->where('sets.id', '>=', $setIds['first'])
                        ->where('sets.id', '<=', $setIds['last'])
                        ->orderBy('cards.name')
                        ->get();

        $card = Card::with('sets_cards.set')->where('name', 'Fatal Push')->first();
        $card->set_name = $card->sets_cards[0]->set->name;
        # ddAll($card);

        $cards[] = $card;

        # ddAll($cards);

        $client = new Client();

        foreach ($cards as $card) {

        	if (strpos($card->name, "'") !== false) {

        		$card->name = preg_replace("/'/", '', $card->name);
        	}

        	if (strpos($card->name, ",") !== false) {

        		$card->name = preg_replace("/,/", '', $card->name);
        	}

            if (strpos($card->name, "//") !== false) {

                $card->name = preg_replace("/ \/\//", '', $card->name);
            }

            # prf($card->name);
        	
        	$crawler = $client->request('GET', 'https://www.mtggoldfish.com/price/'.$card->set_name.'/'.$card->name.'#online');

        	$card->price = $crawler->filter('div.price-box.online > div.price-box-price')->eq(0)->text();
        }

        # dd('die');

        foreach ($cards as $card) {
          
            $cardPrice = new CardPrice;

            $cardPrice->card_id = $card->id;
            $cardPrice->price = $card->price;

            $cardPrice->save();  
        }

		$this->message = 'Success!';		

		return $this;
	}

}