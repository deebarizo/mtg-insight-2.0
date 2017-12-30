/****************************************************************************************
EVOLVING WILDS
****************************************************************************************/

var calculateSourcesForEvolvingWilds = function() {

	var manaSources = '';

	$('tr.md td.copy-card-name').each(function() {
		
		var cardName = $(this).text().trim();

		if (cardName == 'Plains') {

			manaSources += 'W';
		}

		if (cardName == 'Forest') {

			manaSources += 'G';
		}

		if (cardName == 'Mountain') {

			manaSources += 'R';
		}

		if (cardName == 'Swamp') {

			manaSources += 'B';
		}

		if (cardName == 'Island') {

			manaSources += 'U';
		}

		if (cardName == 'Wastes') {

			manaSources += 'C';
		}
	});

	return manaSources;
}