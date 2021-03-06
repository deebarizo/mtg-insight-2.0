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
					{!! Form::label('f-mana-cost', 'Functional Mana Cost:') !!}
						{!! Form::text('f-mana-cost', $card->f_mana_cost, ['class' => 'form-control', 'style' => 'width: 25%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('tags', 'Tags:') !!}
						{!! Form::text('tags', $tags, ['class' => 'form-control', 'style' => 'width: 50%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('rating', 'Rating:') !!}
						{!! Form::text('rating', $card->rating, ['class' => 'form-control', 'style' => 'width: 10%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('mana-sources', 'Mana Sources:') !!}
						{!! Form::text('mana-sources', $card->mana_sources, ['class' => 'form-control', 'style' => 'width: 15%']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('note', 'Note:') !!}
						{!! Form::textarea('note', $card->note, ['class' => 'form-control']) !!}
				</div>

				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}

			{!! Form::close() !!}
		
		</div>
		
	</div>
@stop