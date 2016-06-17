@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'events'	)) !!}

			<div class="col-lg-3"> 
				<div class="form-group">
					{!! Form::label('name', 'Name:') !!}
						{!! Form::text('name', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('location', 'Location:') !!}
					{!! Form::text('location', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('date', 'Date:') !!}
					{!! Form::text('date', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-5"> 
				<div class="form-group">
					{!! Form::label('url', 'URL:') !!}
					{!! Form::text('url', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop