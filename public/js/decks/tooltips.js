$(document).ready(function() {

	/****************************************************************************************
	TOOLTIPS (CARD IMAGES)
	****************************************************************************************/

	$('a.card-name').on('mouseenter', function(event) {

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

});