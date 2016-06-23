@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12" style="margin-bottom: 150px">

			<p>Last updated: {{ $latestDate }}</p>

			<table id="cards" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Edit</th>
						<th>FC</th>
						<th>MC</th>
						<th>MD%</th>
						<th>SB%</th>
						<th>Total%</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($cards as $card)
						<tr>
							<?php 
								$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 

								if ($card->md_percentage === null) {

									$card->md_percentage = NumFormat(0, 2);
								}

								if ($card->sb_percentage === null) {

									$card->sb_percentage = NumFormat(0, 2);
								}

								if ($card->total_percentage === null) {

									$card->total_percentage = NumFormat(0, 2);
								}

								$card->mana_cost = getManaSymbols($card->mana_cost);
							?>

							<td>
								<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>
								<a class="card-edit" href="/cards/{{ $card->id }}/edit">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>{{ $card->f_cost }}</td>
							<td>{!! $card->mana_cost !!}</td>
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
		
		var cardsTable = $('#cards').DataTable({ // https://datatables.net/examples/api/counter_columns.html#
			
			"bLengthChange": false,
			"pageLength": 30,
			"order": [[4, "desc"]],
	        "columnDefs": [ 
	        	{
	            	"searchable": false,
	            	"orderable": false,
	            	"targets": 1
	        	}
	        ],
	        "aoColumns": [
	            null,
	            null,
	            null,
	            null,
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] }
	        ]
		});

	</script>

	<script src="/js/cards/tooltips.js"></script>

@stop