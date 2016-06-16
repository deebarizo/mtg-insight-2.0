<?php

use App\Set;

/****************************************************************************************
HOME
****************************************************************************************/

Route::get('/', function() {

	$titleTag = '';
	
	return View::make('master', compact('titleTag'));
});


/****************************************************************************************
ADMIN
****************************************************************************************/

Route::get('/admin', function() {

	$titleTag = 'Admin | ';
	
	return View::make('admin/index', compact('titleTag'));
});

Route::get('/admin/parsers/cockatrice_xml', function() {

	$titleTag = 'Parsers - Cockatrice XML';
    $h2Tag = 'Parsers - Cockatrice XML';	

    $sets = Set::orderBy('id', 'desc')->get();

	return View::make('admin/parsers/cockatrice_xml', compact('titleTag', 'h2Tag', 'sets'));
});