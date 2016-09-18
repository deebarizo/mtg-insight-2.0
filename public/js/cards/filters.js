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

	$('select.f-cost-filter').on('change', function() {

		var value = $('select.f-cost-filter').val();

		localStorage.setItem('fCostFilter', value);

		var filter = new Filter('Functional Cost', 2, value, null);

		filter.execute();
	});


	/****************************************************************************************
	RATING FILTER
	****************************************************************************************/

	$('select.rating-filter').on('change', function() {

		var value = $('select.rating-filter').val();

		localStorage.setItem('ratingFilter', value);

		var filter = new Filter('Rating', 9, value, null);

		filter.execute();
	});


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

		var columnIndex = 8;

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
	CACHED FILTERS
	****************************************************************************************/

	var fCostFilter = localStorage.getItem('fCostFilter');

	if (fCostFilter !== null) {

		$('select.f-cost-filter').val(fCostFilter);

		var filter = new Filter('Functional Cost', 2, fCostFilter, null);

		filter.execute();
	}

});