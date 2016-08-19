/****************************************************************************************
STORE DECKLIST
****************************************************************************************/

var storeDecklist = function() {

	var decklistIsValid = validateDecklist();

	if (!decklistIsValid) {

		return false;
	}

	$('button.submit-decklist').html('<img src="/files/images/ajax-loader.gif">');	

	var decklist = {

		id: null, 

		latestSetCode: $('#latest-set-code').val(),
		
		name: $('input#decklist-name').val(),

		mdCount: $('span.total-md-cards').text(),

		sbCount: $('span.total-sb-cards').text(),
		
		copies: []
	};

	$('tr.copy-row').each(function() {

		var copy = {

			quantity: $(this).attr('data-copy-quantity'),
			
			cardId: $(this).attr('data-copy-card-id'),
			
			role: $(this).attr('data-copy-role')
		};

		decklist.copies.push(copy);
	});	

	// console.log(decklist);

	// CSRF protection
	$.ajaxSetup({

	    headers: {
	        
	        'X-CSRF-Token': $('input[name="_token"]').val()
	    }
	});

	$.ajax({

        url: '/your_decks/store/',
       	
       	type: 'POST',
       	
       	data: { 

       		decklist: decklist
       	},
        
        success: function() {

        	$('button.submit-decklist').html('Submit');	
        }
    }); 
}


/****************************************************************************************
VALIDATE DECKLIST
****************************************************************************************/

var validateDecklist = function() {

	var copyRows = $('tr.copy-row');

	if (copyRows.length === 0) {

		alert('This decklist has 0 cards!');

		return false;
	}

	var decklist = {

		name: $('input#decklist-name').val()
	};

	if (decklist.name == '') {

		alert('Please enter a decklist name.');

		return false;
	}

	var cardWithInvalidQuantity = validateTotalQuantity(copyRows);

	if (cardWithInvalidQuantity) {

		alert('The card, '+cardWithInvalidQuantity+', has more than 4 copies between main deck and sideboard.');

		return false;
	}

	return true;
}


/****************************************************************************************
VALIDATE TOTAL QUANTITY
****************************************************************************************/

var validateTotalQuantity = function(copyRows) {

	var cardWithInvalidQuantity = false;

	copyRows.each(function(index) {

		var card = {

			id: Number($(this).attr('data-copy-card-id')),

			name: $(this).attr('data-copy-name'),

			copyRows: null
		};

		card.copyRows = $('tr.copy-row[data-copy-card-id="'+card.id+'"]');
		
		var totalQuantity = getTotalQuantity(card.copyRows);

		if (card.id <= 5) {

			var cardIsBasicLand = true;
		
		} else {

			var cardIsBasicLand = false;
		}

		if (totalQuantity > 4 && !cardIsBasicLand) {

			cardWithInvalidQuantity = card.name;

			return false;
		}
	});

	return cardWithInvalidQuantity;
}

var getTotalQuantity = function(cardCopyRows) {

	var totalQuantity = 0;

	cardCopyRows.each(function(index) {

		totalQuantity += Number($(this).attr('data-copy-quantity'));
	});

	return totalQuantity;
}