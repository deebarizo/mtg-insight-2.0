$(document).ready(function() {

	/****************************************************************************************
	TOOLTIPS (CARD IMAGES)
	****************************************************************************************/

	var anchorTextPhrases = ['name', 'edit'];

	for (var i = 0; i < anchorTextPhrases.length; i++) {
		
		$('a.card-'+anchorTextPhrases[i]).each(function() {

	        $(this).qtip({
	        
	            content: {
	        
	                text: $(this).next('.tool-tip-card-image')
				},

				position: {

					my: 'left center',
					at: 'center right',
					target: $(this)
				}
	        });
		});
	}

});