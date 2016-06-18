@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'decks')) !!}

			<div class="col-lg-3"> 
				<div class="form-group">
					{!! Form::label('player', 'Player:') !!}
						{!! Form::text('player', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('finish', 'Finish:') !!}
					{!! Form::text('finish', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-5"> 
				<div class="form-group">
					<label for="event-id">Event:</label>
					<select name="event-id" class="form-control">
						@foreach ($events as $event)
							<option value="{{ $event->id }}">{{ $event->name}} - {{ $event->location }} - {{ $event->date }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="col-lg-12"> 
				<div class="form-group">
					<label for="decklist">Decklist:</label>
					<textarea name="decklist" class="form-control" style="width: 50%" rows="25"></textarea>
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop