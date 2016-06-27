@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-6">

			<p>Finish: {{ $deck->finish }} | <a href="/events/{{ $deck->event->id }}">{{ $deck->event->name }} {{ $deck->event->location }}</a> | {{ $deck->event->date }}</p>

			@foreach ($roles as $role)
				<table class="table table-striped table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th>Quantity</th>
							<th>Card</th>
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
							</tr>
						@endforeach
					</tbody>
				</table>
			@endforeach
		</div>
	</div>

	<script src="/js/decks/tooltips.js"></script>

@stop