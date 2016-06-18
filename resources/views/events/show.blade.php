@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p><a href="{{ $event->url }}">Source</a></p>

			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Id</th>
						<th>Player</th>
						<th>Finish</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($event->event_decks as $deck)
						<tr>
							<td><a href="/decks/{{ $deck->id }}">{{ $deck->id }}</a></td>
							<td>{{ $deck->player }}</td>
							<td>{{ $deck->finish }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop