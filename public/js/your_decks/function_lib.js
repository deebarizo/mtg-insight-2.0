/****************************************************************************************
GET INSERT SPOT FOR COPY ROW
****************************************************************************************/

var getInsertSpotForCopyRow = function(card, role) {

	var insertSpot = {

		spot: null,

		howToInsert: null
	};

	var copyTable = $('table#'+role+' tbody');

	var hasAtLeastOneCopyRow = copyTable.find('tr.copy-row').length;

	if (!hasAtLeastOneCopyRow) {

		insertSpot.howToInsert = 'append';

		return insertSpot;
	}

	if (card.fCost === 'Land') {

		insertSpot.spot = copyTable.find('tr.copy-row').last();
		insertSpot.howToInsert = 'after';

		return insertSpot;
	}

	
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