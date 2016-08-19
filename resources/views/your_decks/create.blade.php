@extends('master')

@section('content')
	
	@include('_form_heading')

	@include('your_decks._cards_table')

		<div class="col-lg-5 decklist">

			<h4>Decklist</h4>

			<form class="form-inline" role="form" style="margin-bottom: 20px">

				<div class="form-group inline">
					<label for="decklist-name">Name:</label>
					<input class="form-control inline"  style="width: 50%" name="decklist-name" type="text" value="" id="decklist-name">

					<label for="latest-set-code">Set:</label>
					<input class="form-control inline"  style="width: 15%" name="latest-set-code" type="text" value="{{ $latestSetCode }}" id="latest-set-code">

					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
				</div>
			</form>	

			<button style="width: 128px; margin-bottom: 20px" class="btn btn-primary submit-decklist">Submit</button>

			<p>Maindeck Cards: <span class="total-md-cards">0</span> | Nonlands: <span class="total-nonlands">0</span> | Lands: <span class="total-lands">0</span></p>

			<table id="md" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Q</th>
						<th>MC</th>
						<th>Card</th>
						<th>FC</th>
						<th>Edit</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>

			<p>Sideboard Cards: <span class="total-sb-cards">0</span></p>

			<table id="sb" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Q</th>
						<th>MC</th>
						<th>Card</th>
						<th>FC</th>
						<th>Edit</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>

		<div class="col-lg-4 charts">

			<h4>Mana Curve</h4>

			<div id="mana-curve" style="height: 200px; margin: 0 auto;"></div>

			<h4>Color Breakdown</h4>

			<div id="color-breakdown" style="height: 400px; margin: 0 auto;"></div>

		</div>
	</div>

	@include('your_decks._scripts')

@stop