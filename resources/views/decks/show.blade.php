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
								<td>{{ $copy->quantity }}</a></td>
								<td>{{ $copy->name }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endforeach
		</div>
	</div>

@stop