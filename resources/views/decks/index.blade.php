@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p><a href="/decks/create">Create Deck</a></p>

			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Id</th>
						<th>Player</th>
						<th>Finish</th>
						<th>Event</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($decks as $deck)
						<tr>
							<td><a href="/decks/{{ $deck->id }}">{{ $deck->id }}</a></td>
							<td>{{ $deck->player }}</td>
							<td>{{ $deck->finish }}</td>
							<td><a href="/events/{{ $deck->event_id }}">{{ $deck->event_name }} - {{ $deck->event_location }}</a></td>
							<td>{{ $deck->event_date }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop