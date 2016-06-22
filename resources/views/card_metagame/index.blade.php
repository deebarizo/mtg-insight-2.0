@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p><a href="/card_metagame/create">Create Card Metagame</a> | Last Updated: {{ $latestDate }}</p>

			<table id="card-metagame" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th class="no-sort">Rank</th>
						<th>Name</th>
						<th>Main Deck</th>
						<th>Sideboard</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($cards as $key => $card)
						<tr>
							<?php 
								$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 
							?>

							<td>{{ $key + 1 }}</td>
							<td>
								<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>{{ $card->md_percentage }}%</td>
							<td>{{ $card->sb_percentage }}%</td>
							<td>{{ $card->total_percentage }}%</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">
		
		var cardMetagameTable = $('#card-metagame').DataTable({ // https://datatables.net/examples/api/counter_columns.html#
			
			"bLengthChange": false,
			"pageLength": 30,
			"order": [[2, "desc"]],
	        "columnDefs": [ 
	        	{
	            	"searchable": false,
	            	"orderable": false,
	            	"targets": 0
	        	}
	        ],
	        "aoColumns": [
	            null,
	            null,
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] }
	        ]
		});

	    cardMetagameTable.on( 'order.dt search.dt', function () { // https://datatables.net/examples/api/counter_columns.html#

	        cardMetagameTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            
	            cell.innerHTML = i+1;
	        });
	    
	    }).draw();

	</script>

	<script src="/js/cards/tooltips.js"></script>

@stop