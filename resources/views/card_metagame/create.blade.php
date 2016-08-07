@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		<?php date_default_timezone_set('America/Chicago'); ?>

		{!! Form::open(array('url' => 'card_metagame'	)) !!}

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('date', 'Date:') !!}
					{!! Form::text('date', date("Y-m-d"), ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop