@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'search')) !!}

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('type', 'Type:') !!}
						{!! Form::text('type', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-10"> 
				<div class="form-group">
					{!! Form::label('f-cost', 'Functional Cost:') !!}
					{!! Form::text('f-cost', '', ['class' => 'form-control', 'style' => 'width: 10%']) !!}
				</div>
			</div>

			<div class="col-lg-1"> 
				<div class="form-group">
					{!! Form::label('Power', 'Power:') !!}
					{!! Form::text('power', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-lg-11"> 
				<div class="form-group" style="width: 7.5%">
					<label for="power-comparison">Comparison:</label>
					<select name="power-comparison" class="form-control">
						<option value="=">=</option>
						<option value=">=">>=</option>
						<option value="<="><=</option>
						<option value=">">></option>
						<option value="<"><</option>
					</select>
				</div>
			</div>

			<div class="col-lg-1"> 
				<div class="form-group">
					{!! Form::label('Toughness', 'Toughness:') !!}
					{!! Form::text('toughness', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="col-lg-11"> 
				<div class="form-group" style="width: 7.5%">
					<label for="toughness-comparison">Comparison:</label>
					<select name="toughness-comparison" class="form-control">
						<option value="=">=</option>
						<option value=">=">>=</option>
						<option value="<="><=</option>
						<option value=">">></option>
						<option value="<"><</option>
					</select>
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop