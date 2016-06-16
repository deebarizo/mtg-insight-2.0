$(document).ready(function() {

	$('a.add-dk-player-link').on('click', function(e) {

		e.preventDefault();

		var trDkPlayer = $(this).closest('tr.dk-player');

		if (trDkPlayer.hasClass('strikethrough') === false) {

			var isSecondPosition = false;

			if ($(this).hasClass('second-position')) {

				isSecondPosition = true;
			}

			var dkPlayer = new DkPlayer(trDkPlayer, isSecondPosition);

			updateLineupMetadata();
		}
	});

	$('a.remove-dk-lineup-player-link').on('click', function(e) {

		e.preventDefault();

		var dkPlayerId = $(this).closest('tr.dk-lineup-player').attr('data-dk-player-id');

		$('tr.dk-player[data-id="'+dkPlayerId+'"]').removeClass('strikethrough');

		var trLineupPlayer = $(this).closest('tr');

        trLineupPlayer.find('td.dk-lineup-player-name-dk').text('');
        trLineupPlayer.find('td.dk-lineup-player-team-name-dk').text('');
        trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk').text('');
        trLineupPlayer.find('td.dk-lineup-player-salary').text('');
        trLineupPlayer.find('td.dk-lineup-player-fpts').text('');

        trLineupPlayer.attr('data-player-pool-id', '');
        trLineupPlayer.attr('data-dk-player-id', '');
        trLineupPlayer.attr('data-dk-player-fpts', '');

        updateLineupMetadata();
	});
});

function updateLineupMetadata() {

	var totalSalary = 0;
	var emptyLineupSpots = 10;

	$('td.dk-lineup-player-salary').each(function() {

		var salary = Number($(this).text());

		if (salary !== 0) {

			emptyLineupSpots--;
		}

		totalSalary += salary;
	});

	var salaryLeft = 50000 - totalSalary;

	if (emptyLineupSpots !== 0) {

		var avgSalaryLeft = salaryLeft / emptyLineupSpots;
		avgSalaryLeft = avgSalaryLeft.toFixed(0);
	
	} else {

		var avgSalaryLeft = 'N/A';
	}

	var totalFpts = 0;

	$('td.dk-lineup-player-fpts').each(function() {

		var fpts = Number($(this).text());

		totalFpts += fpts;
	});

	totalFpts = totalFpts.toFixed(2);

	var trLineupMetadata = $('tr.lineup-metadata');

	trLineupMetadata.find('span.avg-salary-per-dk-lineup-player-left').text(avgSalaryLeft);
	trLineupMetadata.find('span.salary-left').text(salaryLeft);
	trLineupMetadata.find('span.dk-lineup-salary-total').text(totalSalary);
	trLineupMetadata.find('span.dk-lineup-fpts-total').text(totalFpts);
}

function DkPlayer(trDkPlayer, isSecondPosition) {

	this.id = trDkPlayer.attr('data-id');
	this.playerPoolId = trDkPlayer.attr('data-player-pool-id');
	this.nameDk = trDkPlayer.attr('data-name-dk');
	this.teamNameDk = trDkPlayer.attr('data-team-name-dk');
	this.oppTeamNameDk = trDkPlayer.attr('data-opp-team-name-dk');
	this.salary = trDkPlayer.attr('data-salary');
	this.fpts = trDkPlayer.attr('data-fpts');

	if (isSecondPosition) {

		this.position = trDkPlayer.attr('data-position').replace(/(\w+)(\/)(\w+)/, '$3');

	} else {

		this.position = trDkPlayer.attr('data-position').replace(/(\w+)(\/)(\w+)/, '$1');
	}

	var nameField = $('tr.dk-lineup-player[data-position="'+this.position+'"] td.dk-lineup-player-name-dk:empty:first');

	if (nameField.length > 0) {

		trDkPlayer.addClass('strikethrough');

		nameField.text(this.nameDk);
	
	} else {

		this.alert = true;

		return;
	}

	var trLineupPlayer = nameField.closest('tr');

	trLineupPlayer.find('td.dk-lineup-player-team-name-dk').text(this.teamNameDk);
	trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk').text(this.oppTeamNameDk);
	trLineupPlayer.find('td.dk-lineup-player-salary').text(this.salary);
	trLineupPlayer.find('td.dk-lineup-player-fpts').text(this.fpts);

	trLineupPlayer.attr('data-player-pool-id', this.playerPoolId);
	trLineupPlayer.attr('data-dk-player-id', this.id);
	trLineupPlayer.attr('data-dk-player-fpts', this.fpts);
}