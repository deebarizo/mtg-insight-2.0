<?php namespace App\UseCases;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

class FileUploader {

    public function uploadMtgJson($request) {

		$fileDirectory = 'files/mtg_json/';
		$fileName = Input::file('json')->getClientOriginalName();
		$file = $fileDirectory . $fileName;

       	Input::file('json')->move($fileDirectory, $fileName);    

        return $file;   
    }

}