<script type="text/javascript">
	
	var cardsTable = $('#cards').DataTable({ // https://datatables.net/examples/api/counter_columns.html#
		
		"order": [[0, "asc"]],
        "columnDefs": [ 
        	{
            	"searchable": false,
            	"orderable": false,
            	"targets": 1
        	}      	
        ],
        "aoColumns": [
            null,
            null
        ],
		"scrollY": "300px",
		"paging": false
	});

	cardsTable.draw();

</script>

<script type="text/javascript">

	/****************************************************************************************
	GLOBAL VARIABLES
	****************************************************************************************/	
	
	var manaCurve = [null, null, null, null, null, null, null, null];

	var colorStats = {

		symbols: [null, null, null, null, null, null],
		sources: [null, null, null, null, null, null]
	};

	var baseUrl = '<?php echo url("/"); ?>';

</script>

<script src="/js/decks/tooltips.js"></script>

<script src="/js/your_decks/classes.js"></script>

<script src="/js/your_decks/function_lib_single_cards.js"></script>

<script src="/js/your_decks/function_lib_update_decklist.js"></script>

<script src="/js/your_decks/function_lib_store_decklist.js"></script>

<script src="/js/your_decks/create.js"></script>

<script src="/js/decks/charts/mana_curve.js"></script>

<script src="/js/decks/charts/color_breakdown.js"></script>

<script src="/js/clipboardjs/dist/clipboard.min.js"></script>