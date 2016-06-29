$(document).ready(function() {

	/****************************************************************************************
	CARD
	****************************************************************************************/

	function Card(id, name, manaCost, manaCostHtml, fCost, imgSource) {

		this.id = id;
		this.name = name;
		this.manaCost = manaCost;
		this.manaCostHtml = manaCostHtml;
		this.fCost = fCost;
		this.imgSource = imgSource;
	}


	/****************************************************************************************
	COPY
	****************************************************************************************/

	function Copy(quantity, role, card) {

		this.quantity = quantity;
		this.role = role;
		this.card = card;

		this.html = '<tr class="copy-row '+role+'" data-copy-quantity="'+this.quantity+'" data-copy-card-id="'+this.card.id+'" data-copy-mana-cost="'+this.card.manaCost+' "data-copy-role="'+this.role+'"><td class="quantity">'+this.quantity+'<td class="copy-mana-cost-html">'+card.manaCostHtml+'</td><td class="card-name"><a class="card-name" target="_blank" href="/cards/'+this.card.id+'">'+this.card.name+'</a><div style="display: none" class="tool-tip-card-image"><img width="223" height="311" src="'+this.card.imgSource+'"></td><td class="copy-f-cost">'+card.fCost+'</td><td><a class="remove-card '+this.role+'" href=""><div class="icon minus"><span class="glyphicon glyphicon-minus"></span></div></a></td></tr>';
	}

	/****************************************************************************************
	ADD CARD
	****************************************************************************************/

	$('a.add-card').on('click', function(e) {
		
		e.preventDefault();

		var cardRow = $(this).closest('tr.card-row');

		var card = new Card(cardRow.data('card-id'), 
							cardRow.data('card-name'),
							cardRow.data('card-mana-cost'),
							cardRow.data('card-mana-cost-html'),
							cardRow.data('card-f-cost'),
							cardRow.data('card-img-source'));

		var role = getRole($(this));

		var copyRow = $('tr.copy-row[data-card-id="'+card['id']+'"]');

		card.numCopyRows = copyRow.length; // 0 = not yet in decklist, 1 = in maindeck OR sideboard but not BOTH, 2 = in maindeck AND sideboard

		if (card.numCopyRows > 0) {

			return;
		} 

		var copy = new Copy(1, role, card); // 1 is quantity

		$('table#'+role+' tbody').append(copy.html);


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

});