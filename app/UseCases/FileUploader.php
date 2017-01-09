<?php namespace App\UseCases;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

class FileUploader {

	public function uploadCardImage($request) {

		$fileDirectory = 'files/card_images/'.$request->input('set-code').'/';

		$cardName = preg_replace('/\'/', '', $request->input('name')); 

		$fileName = $cardName.'.png';
		$file = $fileDirectory . $fileName;

		Input::file('png')->move($fileDirectory, $fileName);   
	}

    public function uploadMtgJson($request) {

		$fileDirectory = 'files/mtg_json/';
		$fileName = Input::file('json')->getClientOriginalName();
		$file = $fileDirectory . $fileName;

       	Input::file('json')->move($fileDirectory, $fileName);    

        return $file;   
    }

    public function uploadCockatriceXml($request) {

		$fileDirectory = 'files/cockatrice_xml/';
		$fileName = Input::file('xml')->getClientOriginalName();
		$file = $fileDirectory . $fileName;

       	Input::file('xml')->move($fileDirectory, $fileName);    

        return $file;     	
    }

}