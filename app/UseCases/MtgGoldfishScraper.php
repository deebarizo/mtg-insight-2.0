<?php namespace App\UseCases;

use App\Models\Card;
use App\Models\Set;
use App\Models\SetCard;
use App\Models\CardTag;

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

        # ddAll($cards);

        $client = new Client();

        foreach ($cards as $card) {

        	if (strpos($card->name, "'") !== false) {

        		$card->name = preg_replace("/'/", '', $card->name);
        	}

        	if (strpos($card->name, ",") !== false) {

        		$card->name = preg_replace("/,/", '', $card->name);
        	}
        	
        	$crawler = $client->request('GET', 'https://www.mtggoldfish.com/price/'.$card->set_name.'/'.$card->name.'#online');

        	$price = $crawler->filter('div.price-box.online > div.price-box-price')->eq(0)->text();

        	prf($card->name.' '.$price);
        }

		$this->message = 'Success';		

		return $this;
	}

}