@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

			<div class="col-lg-3">
				<?php $cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); ?>

				<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
			</div>

			<div class="col-lg-9">

				{!! Form::open(array('action' => ['CardsController@update', $card->id],'method' => 'PUT')) !!}
			
					<div class="form-group">
						{!! Form::label('f-cost', 'Functional Cost:') !!}
							{!! Form::text('f-cost', $card->f_cost, ['class' => 'form-control', 'style' => 'width: 25%']) !!}
					</div>

					<div class="form-group">
						{!! Form::label('tags', 'Tags:') !!}
							{!! Form::text('tags', $tags, ['class' => 'form-control', 'style' => 'width: 50%']) !!}
					</div>

					{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}

				{!! Form::close() !!}
			
			</div>

		{!!	Form::close() !!}

	</div>
@stop