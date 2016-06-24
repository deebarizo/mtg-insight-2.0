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

		if (this.type === 'Functional Cost') {

			if (this.value !== 'All') {

				cardsTable.column(this.columnIndex).search('^'+this.value+'$', true, false, false); 
			}

			if (this.value === 'All') {

				cardsTable.column(this.columnIndex).search('.*', true, false, false); 
			}
		}

		cardsTable.draw();
	}

	/****************************************************************************************
	FUNCTIONAL COST FILTER
	****************************************************************************************/

	$('select.f-cost-filter').on('change', function() {

		var value = $('select.f-cost-filter').val();

		var filter = new Filter('Functional Cost', 2, value, null);

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

});