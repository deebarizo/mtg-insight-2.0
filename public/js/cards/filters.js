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

			cardsTable.column(this.columnIndex).search('^'+this.value+'$', true, false, false); 
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

});