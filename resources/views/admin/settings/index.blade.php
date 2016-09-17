@extends('master')

@section('content')
	
	@include('_form_heading')

	<div class="row">

		<?php 

			if (Cache::get('card_images') === null) {

				Cache::forever('card_images', 'Hide');
			}

			$cardImagesSetting = Cache::get('card_images');
		?>

		{!! Form::open(array('url' => 'admin/settings')) !!}

			<div class="col-lg-12"> 
				<div class="form-group">

					{!! Form::label('card-images', 'Card Images:') !!}
					<select name='card-images' class="form-control" style="width: 10%">
						<option value="Hide" <?php echo ($cardImagesSetting === 'Hide' ? 'selected' : ''); ?>>Hide</option>
						<option value="Show" <?php echo ($cardImagesSetting === 'Show' ? 'selected' : ''); ?>>Show</option>
					</select>	
				</div>
			</div>

			<div class="col-lg-12"> 
				{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
			</div>

		{!!	Form::close() !!}

	</div>
@stop