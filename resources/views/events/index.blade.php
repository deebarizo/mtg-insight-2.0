@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p><a href="/events/create">Create Event</a></p>

			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Location</th>
						<th>Date</th>
						<th>Source</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($events as $event)
						<tr>
							<td><a href="/events/{{ $event->id }}">{{ $event->name }}</a></td>
							<td>{{ $event->location }}</td>
							<td>{{ $event->date }}</td>
							<td><a href="{!! $event->url !!}">Source</a></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop