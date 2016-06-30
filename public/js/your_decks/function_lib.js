/****************************************************************************************
UPDATE DECKLIST
****************************************************************************************/

var updateDecklist = function() {

	decklist = new Decklist();

	console.log(decklist.totals);

	$('span.total-md-cards').text(decklist.totals.md);
	$('span.total-nonlands').text(decklist.totals.nonlands);
	$('span.total-lands').text(decklist.totals.lands);
	$('span.total-sb-cards').text(decklist.totals.sb);
}


/****************************************************************************************
GET DECKLIST TOTALS
****************************************************************************************/

var getDecklistTotals = function() {

	var decklistTotals = {

		md: 0,

		nonlands: 0,

		lands: 0,
		
		sb: 0,

		mana: {					

			white: {

				symbols: 0,

				sources: 0
			},

			blue: {

				symbols: 0,

				sources: 0
			},

			black: {

				symbols: 0,

				sources: 0
			},

			red: {

				symbols: 0,

				sources: 0
			},

			green: {

				symbols: 0,

				sources: 0
			}, 

			colorless: {

				symbols: 0,

				sources: 0
			}
		},

		drops: [null, null, null, null, null, null, null, null] // for mana curve chart
	};

	var roles = ['md', 'sb'];

	for (var i = 0; i < roles.length; i++) {
		
		$('table#'+roles[i]+' tr.copy-row').each(function(index) {

			var copyRow = $(this);

			var quantity = Number(copyRow.attr('data-copy-quantity'));

			decklistTotals[roles[i]] += quantity;

			var fCost = copyRow.attr('data-card-f-cost');

			if (roles[i] === 'md') {

				if (fCost === 'Land') {

					decklistTotals.lands += quantity;
				
				} else {

					decklistTotals.nonlands += quantity;
				}
			}

	
		});	
	};

	return decklistTotals;	
}


/****************************************************************************************
GET INSERT SPOT FOR COPY ROW
****************************************************************************************/

var getInsertSpotForCopyRow = function(card, role) {

	var insertSpot = {

		spot: null,

		howToInsert: null
	};

	var copyRows = $('table#'+role+' tbody').find('tr.copy-row');

	if (copyRows.length == 0) {

		insertSpot.howToInsert = 'append';

		return insertSpot;
	}

	if (card.fCost === 'Land') {

		insertSpot.howToInsert = 'after';
		insertSpot.spot = copyRows.last();
		
		return insertSpot;
	}

	copyRows.each(function(index) {

		var fCost = $(this).attr('data-card-f-cost');

		if (card.fCost > fCost) {

			insertSpot.howToInsert = 'after';
			insertSpot.spot = $(this);			
		}

		if (fCost == card.fCost) {

			insertSpot.howToInsert = 'after';
			insertSpot.spot = $(this);		

			return false;	
		}

		if (card.fCost < fCost) {

			insertSpot.howToInsert = 'before';
			insertSpot.spot = $(this);	

			return false;
		}

		if (card.fCost === 'Variable' && fCost === 'Land') {

			insertSpot.howToInsert = 'before';
			insertSpot.spot = $(this);		

			return false;				
		}
	});

	if (card.fCost === 'Variable' && insertSpot.spot === null)  {

		insertSpot.howToInsert = 'append';
	}

	return insertSpot;
}


/****************************************************************************************
ROLE
****************************************************************************************/

var getRole = function(anchorTag) {

	if (anchorTag.hasClass('md')) {

		return 'md';
	}

	if (anchorTag.hasClass('sb')) {

		return 'sb';
	}
}