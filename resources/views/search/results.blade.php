@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>{{ $h2Tag }}</h2>

			<hr>
		</div>

		@foreach ($cards as $card)

			<?php $cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); ?>

			<div class="col-lg-3" style="margin-bottom: 50px">
				<img width="223" height="311" src="/files/card_images/{{ $card->code }}/{{ $cardNameNoApostrophe }}.png">
			</div>
	
		@endforeach

	
	</div>
@stop