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
						<th>Tags</th> <!-- hidden-->
					</tr>
				</thead>
				<tbody>
					@foreach ($cards as $card)
						<tr>
							<?php 
								$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 

								if ($card->card_metagames->isEmpty()) {

									$mdPercentage = NumFormat(0, 2);
									$sbPercentage = NumFormat(0, 2);
									$totalPercentage = NumFormat(0, 2);
									
								} else {

									if (!isset($card->card_metagames[0]->md_percentage)) {

										$mdPercentage = NumFormat(0, 2);
									
									} else {

										$mdPercentage = $card->card_metagames[0]->md_percentage;
									}

									if (!isset($card->card_metagames[0]->sb_percentage)) {

										$sbPercentage = NumFormat(0, 2);
									
									} else {

										$sbPercentage = $card->card_metagames[0]->sb_percentage;
									}

									if (!isset($card->card_metagames[0]->total_percentage)) {

										$totalPercentage = NumFormat(0, 2);
									
									} else {

										$totalPercentage = $card->card_metagames[0]->total_percentage;
									}
								}

								$card->mana_cost = getManaSymbols($card->mana_cost);

								$tags = createTagsString($card->card_tags);
							?>

							<td>
								<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>
								<a class="card-edit" href="/cards/{{ $card->id }}/edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>{{ $card->f_cost }}</td>
							<td>{!! $card->mana_cost !!}</td>
							<td>{{ $mdPercentage }}%</td>
							<td>{{ $sbPercentage }}%</td>
							<td>{{ $totalPercentage }}%</td>
							<td>{{ $tags }}</td> <!-- hidden-->
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
	        	},
	        	{
	            	"visible": false,
	            	"targets": 7
	        	}
	        ],
	        "aoColumns": [
	            null,
	            null,
	            null,
	            null,
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            null
	        ]
		});

		cardsTable.column(7).search('^(?!.*non-spell-land)', true, false, false); 

		cardsTable.draw();

	</script>

	<script src="/js/cards/tooltips.js"></script>

@stop