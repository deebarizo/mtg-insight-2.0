describe('Creating a new DkPlayer object', function () {

    it('should create specific property values', function () {

        loadFixtures('lineups/create.html');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.dkPlayer = new DkPlayer(this.trDkPlayer);

        expect(this.dkPlayer.id).toBe('7482');
        expect(this.dkPlayer.playerPoolId).toBe('11');
        expect(this.dkPlayer.position).toBe('SP');
        expect(this.dkPlayer.nameDk).toBe('Bob Jones');
        expect(this.dkPlayer.teamNameDk).toBe('CWS');
        expect(this.dkPlayer.oppTeamNameDk).toBe('Cle');
        expect(this.dkPlayer.salary).toBe('13000');
        expect(this.dkPlayer.fpts).toBe('20.21');
    });  
});

describe('Clicking the "Add" link for your 1st pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');
    }); 

    it('should add the "strikethrough" class', function () {

        expect(this.trDkPlayer).toHaveClass('strikethrough');
    });

    it('should show player data on only the first pitcher lineup row', function () {

        expect($('tr.dk-lineup-player[data-position="SP"]').eq(0).find('td.dk-lineup-player-name-dk')).toHaveText('Bob Jones');
        expect($('tr.dk-lineup-player[data-position="SP"]').eq(1).find('td.dk-lineup-player-name-dk')).toHaveText('');
    });

    it('should show player data on lineup row', function () {

        var trLineupPlayer = $('tr.dk-lineup-player[data-position="SP"]').eq(0);

        expect(trLineupPlayer.find('td.dk-lineup-player-name-dk')).toHaveText('Bob Jones');
        expect(trLineupPlayer.find('td.dk-lineup-player-team-name-dk')).toHaveText('CWS');
        expect(trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk')).toHaveText('Cle');
        expect(trLineupPlayer.find('td.dk-lineup-player-salary')).toHaveText('13000');
        expect(trLineupPlayer.find('td.dk-lineup-player-fpts')).toHaveText('20.21');
    });

    it('should add player data to data elements in lineup row', function () {

        var trLineupPlayer = $('tr.dk-lineup-player[data-position="SP"]').eq(0);

        expect(trLineupPlayer.attr('data-player-pool-id')).toBe('11');
        expect(trLineupPlayer.attr('data-dk-player-id')).toBe('7482');
    }); 

    it('should show "Avg Left", "Total Left", total salary, and total fpts', function () {

        var trLineupMetadata = $('tr.lineup-metadata');

        expect(trLineupMetadata.find('span.avg-salary-per-dk-lineup-player-left').text()).toBe('4025');
        expect(trLineupMetadata.find('span.salary-left').text()).toBe('32200');
        expect(trLineupMetadata.find('span.dk-lineup-salary-total').text()).toBe('17800');
        expect(trLineupMetadata.find('span.dk-lineup-fpts-total').text()).toBe('32.18');
    });    
});

describe('Clicking the "Add" link for your 2nd pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        $('tr.dk-lineup-player[data-position="SP"]').eq(0).find('td.dk-lineup-player-name-dk').text('Donald Trump');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');
    }); 

    it('should add the "strikethrough" class', function () {

        expect(this.trDkPlayer).toHaveClass('strikethrough');
    });

    it('should show player data on both pitcher lineup rows', function () {

        expect($('tr.dk-lineup-player[data-position="SP"]').eq(0).find('td.dk-lineup-player-name-dk')).toHaveText('Donald Trump');
        expect($('tr.dk-lineup-player[data-position="SP"]').eq(1).find('td.dk-lineup-player-name-dk')).toHaveText('Bob Jones');
    });
});

describe('Clicking the "Add" link for your 3rd pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        $('tr.dk-lineup-player[data-position="SP"]').eq(0).find('td.dk-lineup-player-name-dk').text('Donald Trump');
        $('tr.dk-lineup-player[data-position="SP"]').eq(1).find('td.dk-lineup-player-name-dk').text('Bernie Sanders');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');
    }); 

    it('should not add the "strikethrough" class', function () {

        expect(this.trDkPlayer).not.toHaveClass('strikethrough');
    });

    it('should show player data on both pitcher lineup rows', function () {

        expect($('tr.dk-lineup-player[data-position="SP"]').eq(0).find('td.dk-lineup-player-name-dk')).toHaveText('Donald Trump');
        expect($('tr.dk-lineup-player[data-position="SP"]').eq(1).find('td.dk-lineup-player-name-dk')).toHaveText('Bernie Sanders');
    });

    it('should show an alert', function () {

        this.dkPlayer = new DkPlayer(this.trDkPlayer);

        expect(this.dkPlayer.alert).toBe(true);
    });
});

describe('Clicking the first "Add" link of a player with two positions', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Manny Machado"]');

        this.trDkPlayer.find('a.add-dk-player-link').eq(0).trigger('click');
    }); 

    it('should add the "strikethrough" class', function () {

        expect(this.trDkPlayer).toHaveClass('strikethrough');
    });

    it('should show player data on lineup row', function () {

        var trLineupPlayer = $('tr.dk-lineup-player[data-position="3B"]');

         expect(trLineupPlayer.find('td.dk-lineup-player-name-dk')).toHaveText('Manny Machado');
        expect(trLineupPlayer.find('td.dk-lineup-player-team-name-dk')).toHaveText('Bal');
        expect(trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk')).toHaveText('Cle');
        expect(trLineupPlayer.find('td.dk-lineup-player-salary')).toHaveText('4800');
        expect(trLineupPlayer.find('td.dk-lineup-player-fpts')).toHaveText('12.85');
    });

    it('should add player data to data elements in lineup row', function () {

        var trLineupPlayer = $('tr.dk-lineup-player[data-position="3B"]');

        expect(trLineupPlayer.attr('data-player-pool-id')).toBe('12');
        expect(trLineupPlayer.attr('data-dk-player-id')).toBe('9395');
    });    
});

describe('Clicking the second "Add" link of a player with two positions', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Manny Machado"]');

        this.trDkPlayer.find('a.add-dk-player-link.second-position').trigger('click');
    }); 

    it('should add the "strikethrough" class', function () {

        expect(this.trDkPlayer).toHaveClass('strikethrough');
    });

    it('should show player data on lineup row', function () {

        var trLineupPlayer = $('tr.dk-lineup-player[data-position="SS"]');

        expect(trLineupPlayer.find('td.dk-lineup-player-name-dk')).toHaveText('Manny Machado');
        expect(trLineupPlayer.find('td.dk-lineup-player-team-name-dk')).toHaveText('Bal');
        expect(trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk')).toHaveText('Cle');
        expect(trLineupPlayer.find('td.dk-lineup-player-salary')).toHaveText('4800');
        expect(trLineupPlayer.find('td.dk-lineup-player-fpts')).toHaveText('12.85');
    });

    it('should add player data to data elements in lineup row', function () {

        var trLineupPlayer = $('tr.dk-lineup-player[data-position="SS"]');

        expect(trLineupPlayer.attr('data-player-pool-id')).toBe('12');
        expect(trLineupPlayer.attr('data-dk-player-id')).toBe('9395');
        expect(trLineupPlayer.attr('data-dk-player-fpts')).toBe('12.85');
    });    
});

describe('Clicking the remove link of a player', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        this.trLineupPlayer = $('tr.dk-lineup-player[data-dk-player-id="9393"]');

        this.trLineupPlayer.find('a.remove-dk-lineup-player-link').trigger('click');
    }); 

    it('should remove the "strikethrough" class', function () {

        expect($('tr.dk-player[data-id="9393"]')).not.toHaveClass('strikethrough');
    });

    it('should remove the player data on lineup row', function () {

        expect(this.trLineupPlayer.find('td.dk-lineup-player-name-dk')).toHaveText('');
        expect(this.trLineupPlayer.find('td.dk-lineup-player-team-name-dk')).toHaveText('');
        expect(this.trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk')).toHaveText('');
        expect(this.trLineupPlayer.find('td.dk-lineup-player-salary')).toHaveText('');
        expect(this.trLineupPlayer.find('td.dk-lineup-player-fpts')).toHaveText('');
    });

    it('should blank out data elements in lineup row', function () {

        expect(this.trLineupPlayer.attr('data-player-pool-id')).toBe('');
        expect(this.trLineupPlayer.attr('data-dk-player-id')).toBe('');
        expect(this.trLineupPlayer.attr('data-dk-player-fpts')).toBe('');
    }); 
});

describe('Clicking the submit link with an empty lineup', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        this.trLineupPlayer = $('tr.dk-lineup-player[data-dk-player-id="9393"]');

        this.trLineupPlayer.find('td.dk-lineup-player-name-dk').text('');
        this.trLineupPlayer.find('td.dk-lineup-player-team-name-dk').text('');
        this.trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk').text('');
        this.trLineupPlayer.find('td.dk-lineup-player-salary').text('');
        this.trLineupPlayer.find('td.dk-lineup-player-fpts').text('');   

        $('button.submit-lineup').trigger('click');     
    }); 

    it('should show an alert', function () {

        var isEmptyLineup = true;

        $('td.dk-lineup-player-name-dk').each(function() {

            if ($(this).text() != '') {

                isEmptyLineup = false;

                return;
            }
        });

        expect(isEmptyLineup).toBe(true);
    });
});