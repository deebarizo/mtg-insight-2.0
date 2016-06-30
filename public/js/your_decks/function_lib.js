/****************************************************************************************
UPDATE DECKLIST
****************************************************************************************/

var updateDecklist = function() {

	decklist = new Decklist();

	console.log(decklist.totals.mana);

	$('span.total-md-cards').text(decklist.totals.md);
	$('span.total-nonlands').text(decklist.totals.nonlands);
	$('span.total-lands').text(decklist.totals.lands);
	$('span.total-sb-cards').text(decklist.totals.sb);

	for (var i = 0; i < decklist.totals.drops.length; i++) {
		
        manaCurveChart.series[0].data[i].update({
            
            y: decklist.totals.drops[i]
        }); 
	}

	for (var i = 0; i < decklist.totals.mana.length; i++) {

        colorBreakdownChart.series[0].data[i].update({ // series[0] is symbols
            
            y: decklist.totals.mana[i].symbols
        }); 

        colorBreakdownChart.series[1].data[i].update({ // series[1] is sources
            
            y: decklist.totals.mana[i].sources
        }); 	
	}
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

		mana: [

			{
				symbols: null,

				sources: null
			},
			{
				symbols: null,

				sources: null
			},
			{
				symbols: null,

				sources: null
			},
			{
				symbols: null,

				sources: null
			},
			{
				symbols: null,

				sources: null
			},
			{
				symbols: null,

				sources: null
			}
		],

		drops: [null, null, null, null, null, null, null, null] // for mana curve chart
	};

	var roles = ['md', 'sb'];

	var colors = ['W', 'U', 'B', 'R', 'G', 'C'];

	for (var i = 0; i < roles.length; i++) {
		
		$('table#'+roles[i]+' tr.copy-row').each(function(index) {

			var copyRow = $(this);

			var quantity = Number(copyRow.attr('data-copy-quantity'));

			decklistTotals[roles[i]] += quantity;

			var fCost = copyRow.attr('data-copy-f-cost');

			if (roles[i] === 'md') {

				if (fCost === 'Land') {

					decklistTotals.lands += quantity;

					if (copyRow.attr('data-copy-card-id') == 253) { // Evolving Wilds

						var manaSources = calculateSourcesForEvolvingWilds();

					} else {

						var manaSources = copyRow.attr('data-copy-mana-sources');
					}

					for (var n = 0; n < colors.length; n++) {

						var numSources = Number(occurrences(manaSources, colors[n])) * quantity;

						decklistTotals.mana[n].sources += numSources;

						if (decklistTotals.mana[n].sources === 0) {

							decklistTotals.mana[n].sources = null;
						}
					}
				
				} else {

					decklistTotals.nonlands += quantity;

					if (fCost === 'Variable') {

						decklistTotals.drops[7] += quantity;
					}

					if (isNaN(fCost) === false) {

						fCost = Number(fCost);

						if (fCost >= 7) {

							var index = 6;
						
						} else {

							var index = fCost - 1;
						}

						decklistTotals.drops[index] += quantity;
					}

					var manaSymbols = copyRow.attr('data-copy-mana-cost');

					for (var n = 0; n < colors.length; n++) {

						var numSymbols = Number(occurrences(manaSymbols, colors[n])) * quantity;

						decklistTotals.mana[n].symbols += numSymbols;

						if (decklistTotals.mana[n].symbols === 0) {

							decklistTotals.mana[n].symbols = null;
						}
					}
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

		var fCost = $(this).attr('data-copy-f-cost');

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

		if (fCost === 'Land') {

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


/****************************************************************************************
COUNT OCCURRENCES OF STRING
****************************************************************************************/

/** Function count the occurrences of substring in a string;
 * @param {String} string   Required. The string;
 * @param {String} subString    Required. The string to search for;
 * @param {Boolean} allowOverlapping    Optional. Default: false;
 * @author Vitim.us http://stackoverflow.com/questions/4009756/how-to-count-string-occurrence-in-string/7924240#7924240
 */
function occurrences(string, subString, allowOverlapping) {

    string += "";
    subString += "";
    if (subString.length <= 0) return (string.length + 1);

    var n = 0,
        pos = 0,
        step = allowOverlapping ? 1 : subString.length;

    while (true) {
        pos = string.indexOf(subString, pos);
        if (pos >= 0) {
            ++n;
            pos += step;
        } else break;
    }
    return n;
}