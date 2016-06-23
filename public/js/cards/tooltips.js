$(document).ready(function() {

	/****************************************************************************************
	TOOLTIPS (CARD IMAGES)
	****************************************************************************************/

	var anchorTextPhrases = ['name', 'edit'];

	for (var i = 0; i < anchorTextPhrases.length; i++) {
		
		cardsTable.on('mouseenter', 'a.card-'+anchorTextPhrases[i], function(event) {

	        $(this).qtip({
	        
	            content: {
	        
	                text: $(this).next('.tool-tip-card-image')
				},

				position: {

					my: 'left center',
					at: 'center right',
					target: $(this)
				},
				overwrite: false,
	            show: {
	                event: event.type,
	                ready: true
	            }
	        });
		});
	}

});