<!doctype html>

<html lang="en">

	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="/css/mtg-font-master/css/magic-font.css" /> <!-- Mana Symbols -->
		<link rel="stylesheet" href="/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="/css/jquery.qtip.min.css">
		<link rel="stylesheet" href="/css/style.css">

		<script src="/js/jquery-1.11.3.min.js"></script>
		<script src="/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/jquery.dataTables.min.js"></script>
		<script src="/js/jquery.qtip.min.js"></script>
		<script src="/js/highcharts.js"></script>

		<?php $siteName = 'MTG Insight'; ?>

		<title>{{ $titleTag }}{{ $siteName }}</title>
	</head>

	<body>
		<div class="navbar navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="/">{{ $siteName }}</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="{!! setActive('card_metagame*') !!}"><a href="/card_metagame">Card Metagame</a></li>
						<li class="{!! setActive('cards*') !!}"><a href="/cards">Cards</a></li>
						<li class="{!! setActive('events*') !!}"><a href="/events">Events</a></li>
						<li class="{!! setActive('decks*') !!}"><a href="/decks">Decks</a></li>
						<li class="{!! setActive('your_decks*') !!}"><a href="/your_decks">Your Decks</a></li>
						<li class="{!! setActive('admin*') !!}"><a href="/admin">Admin</a></li>
					</ul>
				</div>
			</div>
	    </div>

		<div class="container">
			@yield('content')
		</div>
	</body>

</html>