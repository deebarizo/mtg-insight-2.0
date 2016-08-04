<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use Goutte\Client;
use vendor\symfony\DomCrawler\Crawler;

use App\UseCases\MtgGoldfishScraper;

class ScrapersController extends Controller {

	public function scrapeMtgGoldfish(Request $request) {

		$mtgGoldfishScraper = new MtgGoldfishScraper;

		$setCodes = [

			'first' => 'BFZ',
			'last' => 'EMN'
		];

        $results = $mtgGoldfishScraper->scrapePrices($setCodes['first'], $setCodes['last']);

        $message = $results->message;

		return redirect()->route('admin.scrapers.mtg_goldfish')->with('message', $message);
	}

}