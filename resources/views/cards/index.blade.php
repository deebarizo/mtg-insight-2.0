@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div id="spinner-container" class="col-lg-12"><img src="/files/images/loading.gif"></div>

		<div id="content-container" class="col-lg-12" style="margin-bottom: 150px">

			<p><a href="/cards/create">Create Card</a> | Latest Card Metagame Date: {{ $latestDateForCardMetagame }} | Latest Card Prices Date: {{ $latestDateForCardPrices }}</p>

			<form class="form-inline" style="margin: 0 0 0 0">

				<label>FCs</label>
				<select class="form-control f-cost-filter" style="width: 10%; margin-right: 20px">
					<option value="All">All</option>
				  	@foreach ($fCosts as $fCost)
					  	<option value="{{ $fCost }}">{{ $fCost }}</option>
				  	@endforeach
				</select>	

				<label>Colors</label>
				@foreach ($colors as $color)
					<button type="button" 
							class="btn btn-default color active live"
							data-color-abbr="{{ $color['abbr'] }}"
							data-toggle="button" 
							aria-pressed="true" 
							autocomplete="off">
								<i class="mi mi-mana mi-shadow mi-{{ $color['abbr'] }}"></i>
					</button>
				@endforeach

				<label>Sets</label>
				<select class="form-control set-filter" style="width: 10%; margin-left: 20px">
					<option value="All">All</option>
				  	@foreach ($sets as $set)
					  	<option value="{{ $set }}">{{ $set }}</option>
				  	@endforeach
				</select>	

			</form>

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
						<th>Color Abbrs</th> <!-- hidden-->
						<th>Rating</th>
						<th>Price</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($cards as $card)
						<tr>
							<?php 
								$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 

								$manaCost = getManaSymbols($card->mana_cost);

								$colorAbbrs = getColorAbbrs($card->mana_cost);

								$tags = createTagsString($card->card_tags);
							?>

							<td>
								<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>
								<a class="card-edit" href="/cards/{{ $card->id }}/edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>{{ $card->f_cost }}</td>
							<td>{!! $manaCost !!}</td>
							<td>{{ $card->md_percentage }}%</td>
							<td>{{ $card->sb_percentage }}%</td>
							<td>{{ $card->total_percentage }}%</td>
							<td>{{ $tags }}</td> <!-- hidden-->
							<td>{{ $colorAbbrs }}</td> <!-- hidden-->
							<td>{{ $card->rating }}</td>
							<td>{{ $card->price }}</td>
							<td>{{ $card->code }}</td> <!-- hidden-->
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
			"order": [[6, "desc"]],
	        "columnDefs": [ 
	        	{
	            	"searchable": false,
	            	"orderable": false,
	            	"targets": 1
	        	},
	        	{
	            	"visible": false,
	            	"targets": 7
	        	},
	        	{
	            	"visible": false,
	            	"targets": 8
	        	},	 
	        	{
	            	"visible": false,
	            	"targets": 11
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
	            null,
	            null,
	            { "orderSequence": [ "desc", "asc" ] },
	            { "orderSequence": [ "desc", "asc" ] },
	            null
	        ]
		});

		cardsTable.draw();

		$('.btn').on('mouseup', function() { // http://stackoverflow.com/a/30119360/1946525

			$(this).blur();
		});	 

		$('select.f-cost-filter').on('change', function() {

			$(this).blur();
		});

		$(document).ready(function() {

			$("#spinner-container").hide();

			$("#content-container").css('visibility', 'visible');
		});

	</script>

	<script src="/js/cards/tooltips.js"></script>

	<script src="/js/cards/filters.js"></script>

@stop