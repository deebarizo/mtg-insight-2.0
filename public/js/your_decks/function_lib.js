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

		if (fCost == card.fCost) {

			insertSpot.howToInsert = 'after';
			insertSpot.spot = $(this);		

			return false;	
		}
	});

	return insertSpot;
/*
	var fCosts = copyRows.map(function(){

		var fCost = $(this).attr('data-card-f-cost');

		if (fCost === 'Land' || fCost === 'Variable') {

			return fCost;
		}
               
        return Number(fCost);
    
    }).get();

    var matchingIndex = fCosts.indexOf(card.fCost);

	if (matchingIndex > -1) {

		insertSpot.spot = copyRows.find('td.copy-f-cost')
	}

	return insertSpot; */
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