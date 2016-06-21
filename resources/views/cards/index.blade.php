@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p>Last updated: {{ $latestDate }}.</p>

			<table id="card-metagame" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Edit</th>
						<th>FC</th>
						<th>Note</th>
						<th>MC</th>
						<th>MD%</th>
						<th>SB%</th>
						<th>Total%</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($cards as $card)
						<tr>
							<?php $cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); ?>

							<td>
								<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>
								<a class="card-edit" target="_blank" href="/cards/{{ $card->id }}/edit">
									<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
								</a>
								<div style="display: none" class="tool-tip-card-image">
									<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
								</div>
							</td>
							<td>{{ $card->f_cost }}</td>
							<td>{{ $card->note }}</td>
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

	<script src="/js/cards/tooltips.js"></script>

@stop