@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'admin/scrapers/mtg_goldfish')) !!}

			<div class="col-lg-12"> 
				{!! Form::submit('Run', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop