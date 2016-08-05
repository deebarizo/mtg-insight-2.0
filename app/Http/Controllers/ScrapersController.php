<?php namespace App\Http\Controllers;

ini_set('max_execution_time', 10800); // 10800 seconds = 3 hours

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use Goutte\Client;
use vendor\symfony\DomCrawler\Crawler;

use App\UseCases\MtgGoldfishScraper;

class ScrapersController extends Controller {

	public function scrapeMtgGoldfish(Request $request) {

		$setCodes = [

			'first' => 'BFZ',
			'last' => 'EMN'
		];

		$mtgGoldfishScraper = new MtgGoldfishScraper;

        $results = $mtgGoldfishScraper->scrapePrices($setCodes['first'], $setCodes['last']);

        $message = $results->message;

		return redirect()->route('admin.scrapers.mtg_goldfish')->with('message', $message);
	}

}