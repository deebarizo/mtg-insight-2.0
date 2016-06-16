<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\UseCases\FileUploader;
use App\UseCases\MtgJsonParser;

class ParsersController extends Controller {

	public function parseMtgJson(Request $request) {

		$fileUploader = new FileUploader;

		$jsonFile = $fileUploader->uploadMtgJson($request);

		ddAll('success');

		$mtgJsonParser = new MtgJsonParser;

        $results = $mtgJsonParser->parseJson($jsonFile);

        $message = $results->message;

		return redirect()->route('admin.parsers.mtg_json')->with('message', $message);
	}

}