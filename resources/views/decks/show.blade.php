@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		<div class="col-lg-6">

			<h4>Finish: {{ $deck->finish }} | <a href="/events/{{ $deck->event->id }}">{{ $deck->event->name }} {{ $deck->event->location }}</a> | {{ $deck->event->date }} <button class="btn btn-clipboard" data-clipboard-action="copy" data-clipboard-target="#decklist-for-clipboard">Copy to Clipboard</button></h4>

			@foreach ($roles as $role)

				@if ($role === 'md')
					<p>Maindeck Cards: {{ $metadata['numMdCards'] }} | Nonlands: {{ $metadata['numNonlandCards'] }} | Lands: {{ $metadata['numLandCards'] }}
				@endif

				@if ($role === 'sb')
					<p>Sideboard Cards: {{ $metadata['numSbCards'] }}
				@endif

				<table class="table table-striped table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th>Q</th>
							<th>MC</th>
							<th>Card</th>
							<th>FC</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($copies[$role] as $copy)
							<tr>
								<?php 

									$cardNameNoApostrophe = preg_replace('/\'/', '', $copy->card->name); 

									$manaCost = getManaSymbols($copy->card->mana_cost);
								?>

								<td>{{ $copy->quantity }}</a></td>
								<td>{!! $manaCost !!}</td>
								<td>
									<a class="card-name" target="_blank" href="/cards/{{ $copy->card_id }}">{{ $copy->card->name }}</a>
									<div style="display: none" class="tool-tip-card-image">
										<img width="223" height="311" src="/files/card_images/{{ $copy->card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
									</div>
								</td>
								<td>{{ $copy->card->f_cost }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endforeach
		</div>

		<div class="col-lg-6 charts">

			<h4>Mana Curve</h4>

			<div id="mana-curve" style="height: 200px; margin: 0 auto;"></div>

			<h4>Color Breakdown</h4>

			<div id="color-breakdown" style="height: 400px; margin: 0 auto;"></div>

		</div>
	</div>

	<textarea id="decklist-for-clipboard">{{ $decklistForClipboard }}</textarea>

	<script type="text/javascript">
		
		var manaCurve = <?php echo $manaCurve; ?>;

		var colorStats = {

			symbols: <?php echo $colorStats['symbols']; ?>,
			sources: <?php echo $colorStats['sources']; ?>
		};

	</script>

	<script src="/js/decks/tooltips.js"></script>

	<script src="/js/decks/charts/mana_curve.js"></script>

	<script src="/js/decks/charts/color_breakdown.js"></script>

	<script src="/js/clipboardjs/dist/clipboard.min.js"></script>

	<script type="text/javascript">

		new Clipboard('.btn-clipboard');

	</script>

@stop