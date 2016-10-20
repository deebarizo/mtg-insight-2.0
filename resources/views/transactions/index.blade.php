@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p><a href="/transactions/create">Create Transaction</a> | Latest Card Prices Date: {{ $overview['latestDateForCardPrices'] }}</p>

			<p><strong>Tix Available: </strong> {{ numFormat($overview['tixAvailable'], 0) }} ({{ $overview['tixAvailable'] }})</p>

			<p><strong>Tix in Cards: </strong> {{ numFormat($overview['tixInCards'], 0) }} ({{ $overview['tixInCards'] }})</p>

			<p><strong>Total Revenue: </strong> {{ numFormat($overview['totalRevenue'], 2) }}</p>

			<p><strong>Total Profit: </strong> {{ numFormat($overview['totalProfit'], 2) }}</p>

			<p><strong>Total Profit Percentage: </strong> {{ numFormat($overview['totalProfitPercentage'], 2) }}%</p>

			<table id="cards" class="table table-striped table-bordered table-hover table-condensed">

				<thead>
					<tr>
						<th>Name</th>
						<th>Quantity</th>
						<th>Links</th>
						<th>Avg Price</th>
						<th>Current Avg Price</th>
						<th>Total</th>
						<th>Current Total</th>
						<th>Profit</th>
						<th>Profit %</th>
						<th>Own %</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($overview['cards'] as $card)

					<?php 

						$urlCardName = $card['name'];

			        	
			        	if (strpos($urlCardName, "'") !== false) {

			        		$urlCardName = preg_replace("/'/", '', $urlCardName);
			        	}

			        	if (strpos($urlCardName, ",") !== false) {

			        		$urlCardName = preg_replace("/,/", '', $urlCardName);
			        	}

			        	$urlGoldfishCardName = $urlCardName;
			        	
			        	$goldfishLink = 'https://www.mtggoldfish.com/price/'.$card['set_name'].'/'.$urlGoldfishCardName.'#online';

			        	$urlWikipriceCardName = preg_replace("/\s/", '_', $urlCardName);
			        	
			        	$wikiPriceLink = 'https://www.mtgowikiprice.com/card/'.$card['set_code'].'/'.$card['wikiprice_card_number'].'/'.$urlWikipriceCardName;
					?>
						<tr>
							<td>{{ $card['name'] }}</td>
							<td>{{ $card['quantity'] }}</td>
							<td><a target="_blank" href="{{ $goldfishLink }}">G</a> | <a target="_blank" href="{{ $wikiPriceLink }}">W</a></td>
							<td>{{ numFormat($card['price_per_copy'], 2) }}</td>
							<td>{{ numFormat($card['mtg_goldfish_price'], 2) }}</td>
							<td>{{ numFormat($card['tix'], 2) }}</td>
							<td>{{ numFormat($card['current_total_price'], 2) }}</td>
							<td>{{ numFormat($card['profit'], 2) }}</td>
							<td>{{ numFormat($card['profit_percentage'], 2) }}</td>
							<td>{{ numFormat($card['ownership_percentage'], 2) }}</td>
						</tr>
					@endforeach
				</tbody>

			</table>

		</div>
	</div>

	<script type="text/javascript">

		var cardsTable = $('#cards').DataTable({ // https://datatables.net/examples/api/counter_columns.html#
			
			"paging": false,
			"order": [[5, "desc"]]
		});

		$('#cards_filter').hide();

	</script>
@stop