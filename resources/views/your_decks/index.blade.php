@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">
		<div class="col-lg-12">

			<p><a href="/your_decks/create">Create Deck</a></p>

			<table class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Last Updated</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($yourDecks as $yourDeck)
						<tr>
							<td><a href="/your_decks/{{ $yourDeck->set_code }}/{{ $yourDeck->slug }}">{{ $yourDeck->name }}</a></td>
							<td>{{ $yourDeck->saved_at }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@stop