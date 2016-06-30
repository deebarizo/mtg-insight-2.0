@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		<div class="col-lg-3">

			<h4>Cards</h4>

			<table id="cards" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Add</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($cards as $card)
						<?php 
							$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 

							$manaCostHtml = getManaSymbols($card->mana_cost);
						?>

						<tr class="card-row"
							data-card-id="{{ $card->id }}"
							data-card-mana-cost="{{ $card->mana_cost }}"
							data-card-mana-cost-html='{!! $manaCostHtml !!}'
							data-card-name="{{ $card->name }}"
							data-card-f-cost="{{ $card->f_cost }}"
							data-card-img-source="/files/card_images/{{ $card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
							<td>
								<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>
								<a class="add-card md" href="" style="margin-right: 5px">
									<div class="icon plus md">
										<span class="glyphicon glyphicon-plus"></span>
									</div>
								</a>
								<a class="add-card sb" href="">
									<div class="icon plus sb">
										<span class="glyphicon glyphicon-plus"></span>
									</div>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

		</div>

		<div class="col-lg-5 decklist">

			<h4>Decklist</h4>

			<p>Maindeck Cards: 0 | Nonlands: 0 | Lands: 0</p>

			<table id="md" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Q</th>
						<th>MC</th>
						<th>Card</th>
						<th>FC</th>
						<th>Edit</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>

			<p>Sideboard Cards: 0</p>

			<table id="sb" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Q</th>
						<th>MC</th>
						<th>Card</th>
						<th>FC</th>
						<th>Edit</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>

		<div class="col-lg-4 charts">

			<h4>Mana Curve</h4>

			<div id="mana-curve" style="height: 200px; margin: 0 auto;"></div>

			<h4>Color Breakdown</h4>

			<div id="color-breakdown" style="height: 400px; margin: 0 auto;"></div>

		</div>
	</div>

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
		
		var manaCurve = [null, null, null, null, null, null, null, null];

		var colorStats = {

			symbols: [null, null, null, null, null, null],
			sources: [null, null, null, null, null, null]
		};

	</script>

	<script src="/js/decks/tooltips.js"></script>

	<script src="/js/your_decks/function_lib.js"></script>

	<script src="/js/your_decks/create_and_edit.js"></script>

	<script src="/js/decks/charts/mana_curve.js"></script>

	<script src="/js/decks/charts/color_breakdown.js"></script>

@stop