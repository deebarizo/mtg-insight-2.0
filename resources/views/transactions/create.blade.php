@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		{!! Form::open(array('url' => 'transactions')) !!}

			<div class="col-lg-2"> 
				<div class="form-group">

					{!! Form::label('type', 'Type:') !!}
					<select name='type' class="form-control">
						<option value="Buy">Buy</option>
						<option value="Sell">Sell</option>
						<option value="Deposit">Deposit</option>
						<option value="Withdraw">Withdraw</option>
					</select>	
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('quantity', 'Quantity (Optional):') !!}
					{!! Form::text('quantity', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-4"> 
				<div class="form-group">
					{!! Form::label('card', 'Card (Optional):') !!}
					{!! Form::text('card', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-2"> 
				<div class="form-group">
					{!! Form::label('tix', 'Tix:') !!}
					{!! Form::text('tix', '', ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-12" style="margin-top: 15px"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop