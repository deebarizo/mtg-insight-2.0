@extends('master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2>Admin</h2>

			<hr>

			<h4>Scrapers</h4>

			<ul>
				<li><a href="/admin/scrapers/mtg_goldfish">MTG Goldfish</a></li>
			</ul>

			<h4>Parsers</h4>

			<ul>
				<li><a href="/admin/parsers/mtg_json">MTG JSON</a></li>
				<li><a href="/admin/parsers/cockatrice_xml">Cockatrice XML</a></li>
			</ul>
		</div>
	</div>
@stop