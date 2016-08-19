$(document).ready(function() {

	/****************************************************************************************
	ADD CARD FROM CARD ROW
	****************************************************************************************/

	$('tr.card-row').on('click', 'a.add-card', function(e) {
		
		e.preventDefault();

		var cardRow = $(this).closest('tr.card-row');

		var card = new Card(cardRow.data('card-id'), 
							cardRow.data('card-name'),
							cardRow.data('card-f-mana-cost'),
							cardRow.data('card-mana-cost-html'),
							cardRow.data('card-mana-sources'),
							cardRow.data('card-f-cost'),
							cardRow.data('card-img-source'));

		var role = getRole($(this));

		var copyRow = $('tr.copy-row[data-copy-card-id="'+card.id+'"].'+role);

		if (copyRow.length > 0) {

			var quantity = Number(copyRow.find('td.quantity').text());
			quantity++;
			copyRow.find('td.quantity').text(quantity);

			copyRow.attr('data-copy-quantity', quantity);

			updateDecklist();

			return false;
		} 

		var copy = new Copy(1, role, card); // 1 is quantity

		var insertSpot = getInsertSpotForCopyRow(card, role);

		var copyTable = $('table#'+role+' tbody');

		if (insertSpot.howToInsert === 'append') {

			copyTable.append(copy.html);
		}

		if (insertSpot.howToInsert === 'after') {

			insertSpot.spot.after(copy.html);
		}	

		if (insertSpot.howToInsert === 'before') {

			insertSpot.spot.before(copy.html);
		}	

		updateDecklist();


		/****************************************************************************************
		CREATE TOOLTIPS FOR DYNAMIC CONTENT
		****************************************************************************************/

	    $('table#md, table#sb').on('mouseenter', 'a.card-name', function(event) {
	        
	        $(this).qtip({

	            content: {
	        
	                text: $(this).next('.tool-tip-card-image')
				},

				position: {

					my: 'left center',
					at: 'top right',
					target: $(this)
				},

	            overwrite: false, // Don't overwrite tooltips already bound

	            show: {
	            	
	                event: event.type, // Use the same event type as above
	                ready: true // Show immediately - important!
	            }
	        });
	    });
	});


	/****************************************************************************************
	ADD CARD FROM COPY ROW
	****************************************************************************************/

	$('div.decklist').on('click', 'a.add-card', function(e) {

		e.preventDefault();

		var copyRow = $(this).closest('tr.copy-row');

		var quantity = Number(copyRow.find('td.quantity').text());
		quantity++;
		copyRow.find('td.quantity').text(quantity);

		copyRow.attr('data-copy-quantity', quantity);

		updateDecklist();
	});


	/****************************************************************************************
	REMOVE CARD FROM COPY ROW
	****************************************************************************************/

	$('div.decklist').on('click', 'a.remove-card', function(e) {

		e.preventDefault();

		var copyRow = $(this).closest('tr.copy-row');

		var quantity = Number(copyRow.find('td.quantity').text());
		quantity--;

		if (quantity > 0) {

			copyRow.find('td.quantity').text(quantity);

			copyRow.attr('data-copy-quantity', quantity);

			updateDecklist();

			return false;			
		}

		copyRow.remove();

		updateDecklist();
	});

	/****************************************************************************************
	SUBMIT DECKLIST
	****************************************************************************************/

	$('button.submit-decklist').on('click', function(e) {

		e.preventDefault();

		storeDecklist(); 
	});
});