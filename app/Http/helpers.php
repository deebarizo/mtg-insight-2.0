<?php

/****************************************************************************************
PRINT VARIABLE
****************************************************************************************/

function ddAll($var) {

	echo '<pre>';
	print_r($var);
	echo '</pre>';

	exit();
}

function prf($var) {

    echo '<pre>';
    print_r($var);
    echo '</pre>';
}


/****************************************************************************************
SET ACTIVE TAB
****************************************************************************************/

function setActive($path, $active = 'active') {

	return Request::is($path) ? $active : '';
}


/****************************************************************************************
NUMBER FORMAT
****************************************************************************************/

function numFormat($number, $decimalPlaces = 2) {
	
	$number = number_format(round($number, $decimalPlaces), $decimalPlaces);

	return $number;
}


/****************************************************************************************
GET MANA SYMBOLS
****************************************************************************************/

function getManaSymbols($manaCost) {

	$manaCost = preg_replace("/{(\d+)}/", '<i class="mi mi-mana mi-shadow mi-$1"></i>', $manaCost);

	$manaCost = preg_replace("/{(\D)}/", '<i class="mi mi-mana mi-shadow mi-$1"></i>', $manaCost);
	$manaCost = strtolower($manaCost);

	return $manaCost;
}


/****************************************************************************************
GET COLOR ABBREVIATIONS
****************************************************************************************/

function getColorAbbrs($manaCost) {

	$colorAbbrString = '';

	$colorAbbrs = ['W', 'U', 'B', 'R', 'G', 'C'];

	foreach ($colorAbbrs as $key => $colorAbbr) {

		if (strpos($manaCost, $colorAbbr) !== false) {

			$colorAbbrString .= $colorAbbr;
		}
	}

	return $colorAbbrString;
} 


/****************************************************************************************
CREATE TAGS STRING
****************************************************************************************/

function createTagsString($cardTags) {

    $tags = '';

    foreach ($cardTags as $key => $cardTag) {
        
        $tags .= $cardTag->tag.' ';
    }

    $tags = trim($tags);

    return $tags;
}
	