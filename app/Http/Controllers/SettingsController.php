<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller {

	public function saveSettings(Request $request) {

		Cache::forever('card_images', $request->input('card-images'));

		$message = 'Success!';

		return redirect()->route('admin.settings')->with('message', $message);
	}

}