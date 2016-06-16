<?php

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('master', compact('titleTag'));
});