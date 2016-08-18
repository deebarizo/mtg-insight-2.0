@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		<div class="col-lg-12">

			{!! Form::open(array('url' => 'cards', 'files' => true)) !!}
		
				<div class="form-group">
					{!! Form::label('name', 'Name:') !!}
						{!! Form::text('name', '', ['class' => 'form-control', 'style' => 'width: 25%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('set-code', 'Set:') !!}
						{!! Form::text('set-code', $latestSetCode, ['class' => 'form-control', 'style' => 'width: 10%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('mana-cost', 'Mana Cost:') !!}
						{!! Form::text('mana-cost', '{1}{W}{W}', ['class' => 'form-control', 'style' => 'width: 15%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('f-mana-cost', 'Functional Mana Cost:') !!}
						{!! Form::text('f-mana-cost', '{1}{W}{W}', ['class' => 'form-control', 'style' => 'width: 15%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('mana-sources', 'Mana Sources:') !!}
						{!! Form::text('mana-sources', '', ['class' => 'form-control', 'style' => 'width: 15%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('f-cost', 'Functional Cost:') !!}
						{!! Form::text('f-cost', '3', ['class' => 'form-control', 'style' => 'width: 10%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('rating', 'Rating:') !!}
						{!! Form::text('rating', '3', ['class' => 'form-control', 'style' => 'width: 10%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('rarity', 'Rarity:') !!}
						{!! Form::text('rarity', '', ['class' => 'form-control', 'style' => 'width: 10%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('png', 'Image:') !!}
					{!! Form::file('png', '', ['class' => 'form-control', 'style' => 'width: 50%']) !!}
				</div>

				{!! Form::submit('Submit', ['class' => 'btn btn-primary', 'style' => 'margin-top: 10px']) !!}

			{!! Form::close() !!}
		
		</div>

	</div>
@stop