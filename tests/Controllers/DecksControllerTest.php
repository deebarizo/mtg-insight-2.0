<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Event;
use App\Models\EventDeck;

class DecksControllerTest extends TestCase {

    use DatabaseTransactions;

    private function setUpEvent() {

        factory(Event::class)->create([
        
            'id' => 2,
            'name' => 'Grand Prix', 
            'location' => 'Minneapolis',
            'date' => '2016-05-28',
            'url' => 'http://magic.wizards.com/en/events/coverage/gpmin16'
        ]);
    }

    private function setUpDeck() {

    	factory(EventDeck::class)->create([

    		'id' => 1,
    		'player' => 'Bob Jones',
    		'finish' => 1,
    		'event_id' => 2
    	]);
    }

    /** @test */
    public function validates_duplicate_deck() {

    	$this->setUpEvent();
    	$this->setUpDeck();

        $this->call('POST', '/decks', [

            'player' => 'Bob Jones',
            'finish' => 1,
            'event-id' => 2,
            'decklist' => '4 Lightning Bolt'
        ]);

        $this->assertRedirectedTo('/decks/create');

        $this->followRedirects();

        $this->see('This deck already exists in the database.');
        $this->see('Bob Jones');
        $this->see('1');
        $this->see('Grand Prix - Minneapolis - 2016-05-28');
    }    

    /** @test */
    public function validates_invalid_card() {

        $this->setUpEvent();

        $this->call('POST', '/decks', [

            'player' => 'Bob Jones',
            'finish' => 1,
            'event-id' => 2,
            'decklist' => '4 Bob\'s Awesome Bolt'
        ]);

        $this->assertRedirectedTo('/decks/create');

        $this->followRedirects();

        $this->see('The card, Bob\'s Awesome Bolt, does not exist in the database.');

    }


}