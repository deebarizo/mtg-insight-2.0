@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div id="spinner-container" class="col-lg-12"><img src="/files/images/loading.gif"></div>

		<div id="content-container" class="col-lg-12" style="margin-bottom: 150px">

			<p><a href="/cards/create">Create Card</a> | Latest Card Metagame Date: {{ $latestDateForCardMetagame }} | Latest Card Prices Date: {{ $latestDateForCardPrices }}</p>

			{!! Form::open(array('url' => '/cards/update_stars', 'class' => 'form-inline', 'style' => 'margin: 0 0 0 0' )) !!}

				<label>FCs</label>
				<select class="form-control f-cost-filter" style="width: 10%; margin-right: 20px">
					<option value="All">All</option>
					<option value="Nonland">Nonland</option>
				  	@foreach ($fCosts as $fCost)
					  	<option value="{{ $fCost }}">{{ $fCost }}</option>
				  	@endforeach
				</select>	

				<label>Colors</label>
				@foreach ($colors as $key => $color)
					<button type="button" 
							class="btn btn-default color active live"
							data-color-abbr="{{ $color['abbr'] }}"
							data-toggle="button" 
							aria-pressed="true" 
							autocomplete="off"
							<?php echo ($key === 5 ? 'style="margin-right: 20px"': ''); ?>>
								<i class="mi mi-mana mi-shadow mi-{{ $color['abbr'] }}"></i>
					</button>
				@endforeach

				<label>Ratings</label>
				<select class="form-control rating-filter" style="width: 10%">
					<option value="All">All</option>
					<option value="0">0</option>
					<option value="1+">1+</option>
				</select>	

			{!!	Form::close() !!}

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
						<th>Code</th> <!-- hidden-->
						<th>F MC</th> <!-- hidden-->
					</tr>
				</thead>

				<?php $cardImagesSetting = Cache::get('card_images', 'Hide'); ?>

				<tbody>
					@foreach ($cards as $card)
						<tr data-card-id="{{ $card->id }}">
							<?php 
								$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 

								$manaCost = getManaSymbols($card->mana_cost);

								$fManaCost = getManaSymbols($card->f_mana_cost);

								$colorAbbrs = getColorAbbrs($card->f_mana_cost);

								$tags = createTagsString($card->card_tags);
							?>

							<td>
								@if ($cardImagesSetting === 'Hide')
									<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
									<div style="display: none" class="tool-tip-card-image">
										<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
									</div>
								@elseif ($cardImagesSetting === 'Show') 
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								@endif
							</td>
							<td>
								<a target="_blank" class="card-edit" href="/cards/{{ $card->id }}/edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>{{ $card->f_cost }}</td>
							<td>{!! $manaCost !!}</td>
							<td>{{ $card->md_percentage }}%</td>
							<td>{{ $card->sb_percentage }}%</td>
							<td>{{ $card->total_percentage }}%</td>
							<td class="stars">{!! $card->stars_html !!}</td>
							<td>{{ $card->price }}</td>
							<td>{{ $tags }}</td> <!-- hidden-->
							<td>{{ $colorAbbrs }}</td> <!-- hidden-->
							<td>{{ $card->code }}</td> <!-- hidden-->
							<td>{{ $fManaCost }}</td> <!-- hidden-->
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">

		/****************************************************************************************
		GLOBAL VARIABLES
		****************************************************************************************/

		var baseUrl = '<?php echo url('/'); ?>';

		var columnIndexes = {

			editLink: 1,
			rating: 7,
			tags: 9,
			colorAbbrs: 10,
			setCode: 11,
			fManaCost: 12
		};

		var cardsTable = $('#cards').DataTable({ // https://datatables.net/examples/api/counter_columns.html#
			
			"bLengthChange": false,
			"pageLength": <?php echo ($cardImagesSetting === 'Hide' ? '30' : '10'); ?>,
			"order": [[columnIndexes.rating, "desc"]],
	        "columnDefs": [ 
	        	{
	            	"searchable": false,
	            	"orderable": false,
	            	"targets": columnIndexes.editLink
	        	},
	        	{
	            	"visible": false,
	            	"targets": columnIndexes.tags
	        	},
	        	{
	            	"visible": false,
	            	"targets": columnIndexes.colorAbbrs
	        	},	 
	        	{
	            	"visible": false,
	            	"targets": columnIndexes.setCode
	        	},
	        	{
	            	"visible": false,
	            	"targets": columnIndexes.fManaCost
	        	}	        	       	
	        ],
	        "aoColumns": [
	            null, // 0
	            null, // 1
	            { "orderSequence": [ "desc", "asc" ] }, // 2
	            null, // 3
	            { "orderSequence": [ "desc", "asc" ] }, // 4
	            { "orderSequence": [ "desc", "asc" ] }, // 5
	            { "orderSequence": [ "desc", "asc" ] }, // 6
	            { "orderSequence": [ "desc", "asc" ] }, // 7
	            { "orderSequence": [ "desc", "asc" ] }, // 8
	            { "orderSequence": [ "desc", "asc" ] }, // 9
	            { "orderSequence": [ "desc", "asc" ] }, // 10
	            null, // 11
	            null // 12
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


		/****************************************************************************************
		AJAX SETUP
		****************************************************************************************/

		$.ajaxSetup({ // http://stackoverflow.com/a/37663496/1946525
		    
		    headers: {
		        
		        'X-CSRF-Token': $('input[name="_token"]').val()
		    }
		});


		/****************************************************************************************
		STARS
		****************************************************************************************/

		var maxNumOfStars = 10;

		$('span.star').on('click', function(e) {

			e.preventDefault();

			var clickNumber = $(this).data('star');

			var stars = $(this).closest('td.stars');
			var tableRow = stars.closest('tr');

			var cardId = tableRow.data('card-id');

			var numOfActiveStarsOnClick = stars.find('span.star.glyphicon-star').length;

			if ($(this).hasClass('glyphicon-star')) {

				var userClickedActiveStar = true;

			} else if ($(this).hasClass('glyphicon-star-empty')) {

				var userClickedActiveStar = false;
			}

			if (userClickedActiveStar) {

				var numOfActiveStarsAfterClick = numOfActiveStarsOnClick - (numOfActiveStarsOnClick - clickNumber);
			}

			if (!userClickedActiveStar) {

				var numOfActiveStarsAfterClick = numOfActiveStarsOnClick + (clickNumber - numOfActiveStarsOnClick + 1);
			}

			$.ajax({

	            url: baseUrl+'/cards/update_stars',
	           	type: 'POST',
	           	data: { 
	           	
	           		numOfActiveStarsAfterClick: numOfActiveStarsAfterClick,
	           		cardId: cardId
	           	},
	            success: function() {
	            
					if (userClickedActiveStar) {

						for (var n = clickNumber; n < numOfActiveStarsOnClick; n++) {

							var star = stars.find('span.star').eq(n);

							star.removeClass('glyphicon-star').addClass('glyphicon-star-empty');
						}
					
					} else if (!userClickedActiveStar) {

						for (var n = 0; n < clickNumber + 1; n++) {

							var star = stars.find('span.star').eq(n);

							star.removeClass('glyphicon-star-empty').addClass('glyphicon-star');
						}
					}

					stars.find('span.num-of-stars').text(numOfActiveStarsAfterClick);
	            }
	        });
		});

	</script>

	<script src="/js/cards/tooltips.js"></script>

	<script src="/js/cards/filters.js"></script>

@stop