@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'admin/parsers/cockatrice_xml', 'files' => true)) !!}

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('csv', 'CSV:') !!}
					{!! Form::file('csv', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop