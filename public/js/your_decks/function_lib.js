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