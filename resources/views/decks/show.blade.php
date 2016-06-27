@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		<div class="col-lg-4">

			<h4>Finish: {{ $deck->finish }} | <a href="/events/{{ $deck->event->id }}">{{ $deck->event->name }} {{ $deck->event->location }}</a> | {{ $deck->event->date }}</h4>

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
							<th>Quantity</th>
							<th>Card</th>
							<th>FC</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($copies[$role] as $copy)
							<tr>
								<?php $cardNameNoApostrophe = preg_replace('/\'/', '', $copy->card->name); ?>

								<td>{{ $copy->quantity }}</a></td>
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

		<div class="col-lg-5 charts">

			<h4>Mana Curve</h4>

			<div id="mana-curve" style="height: 200px; margin: 0 auto;"></div>

		</div>
	</div>

	<script type="text/javascript">
		
		 var manaCurve = <?php echo $manaCurve; ?>;

	</script>

	<script src="/js/decks/tooltips.js"></script>

	<script src="/js/decks/charts/mana_curve.js"></script>

@stop