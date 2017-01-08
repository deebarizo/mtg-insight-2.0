$(document).ready(function() {

	/****************************************************************************************
	FILTERS
	****************************************************************************************/

	function Filter(type, columnIndex, value, modifier) {

		this.type = type;
		this.columnIndex = columnIndex;
		this.value = value;
		this.modifier = modifier;
	}

	Filter.prototype.execute = function() {

		// https://datatables.net/reference/api/column().search()

		switch(this.type) {

			case 'Functional Cost':

				if (this.value !== 'All') {

					if (this.value !== 'Nonland') {

						cardsTable.column(this.columnIndex).search('^'+this.value+'$', true, false, false); 
					}

					if (this.value === 'Nonland') {

						// http://stackoverflow.com/questions/1538512/how-can-i-invert-a-regular-expression-in-javascript
						cardsTable.column(this.columnIndex).search('^(?!.*Land)', true, false, false); 
					}
				}

				if (this.value === 'All') {

					cardsTable.column(this.columnIndex).search('.*', true, false, false); 
				}

				break;

			case 'Rating':

				if (this.value === 'All') {

					cardsTable.column(this.columnIndex).search('.*', true, false, false); 
				}

				if (this.value === '0') {

					cardsTable.column(this.columnIndex).search('^'+this.value+'$', true, false, false); 
				}		

				if (this.value === '1+') {

					// http://stackoverflow.com/questions/1538512/how-can-i-invert-a-regular-expression-in-javascript
					cardsTable.column(this.columnIndex).search('^(?!.*0)', true, false, false); 
				}		

				break;
		}

		cardsTable.draw();
	}


	/****************************************************************************************
	FUNCTIONAL COST FILTER
	****************************************************************************************/

	function runFunctionalCostFilter() {

		var value = $('select.f-cost-filter').val();

		localStorage.setItem('fCostFilter', value);

		var filter = new Filter('Functional Cost', 2, value, null);

		filter.execute();		
	}

	// CHANGE TRIGGER

	$('select.f-cost-filter').on('change', function() {

		runFunctionalCostFilter();
	});

	// ON PAGE LOAD

	var fCostFilter = localStorage.getItem('fCostFilter');

	if (fCostFilter === null) {

		localStorage.setItem('fCostFilter', 'All');
	}

	$('select.f-cost-filter').val(fCostFilter);

	runFunctionalCostFilter();


	/****************************************************************************************
	RATING FILTER
	****************************************************************************************/

	function runRatingFilter() {

		var value = $('select.rating-filter').val();

		localStorage.setItem('ratingFilter', value);

		var filter = new Filter('Rating', 9, value, null);

		filter.execute();		
	}

	// CHANGE TRIGGER

	$('select.rating-filter').on('change', function() {

		runRatingFilter();
	});

	// ON PAGE LOAD

	var ratingFilter = localStorage.getItem('ratingFilter');

	if (ratingFilter === null) {

		localStorage.setItem('ratingFilter', 'All');
	}

	$('select.rating-filter').val(ratingFilter);

	runRatingFilter();


	/****************************************************************************************
	COLOR FILTER
	****************************************************************************************/

	$('button.color').on('click', function() {

		$(this).toggleClass('live');

		var colorAbbrsToExclude = '';

		$('button.color').each(function() {

			var colorAbbr = $(this).data('color-abbr').toUpperCase();

			var live = $(this).hasClass('live');

			if (!live) {

				colorAbbrsToExclude += colorAbbr+'|';
			}
		})

		var columnIndex = columnIndexes.colorAbbrs;

		if (colorAbbrsToExclude == '') {

			cardsTable.column(columnIndex).search('.*', true, false, false); 

			cardsTable.draw();

			return;
		}

		colorAbbrsToExclude = colorAbbrsToExclude.slice(0, -1);
		
		cardsTable.column(columnIndex).search('^(?!.*('+colorAbbrsToExclude+'))', true, false, false); 

		cardsTable.draw();
	});


	/****************************************************************************************
	CACHED RATING FILTER
	****************************************************************************************/
	
	var ratingFilter = localStorage.getItem('ratingFilter');

	if (ratingFilter === null) {

		localStorage.setItem('ratingFilter', 'All');
	}

	$('select.rating-filter').val(ratingFilter);

	var filter = new Filter('Rating', 9, ratingFilter, null);

	filter.execute();

});