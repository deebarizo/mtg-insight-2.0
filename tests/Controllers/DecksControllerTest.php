<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Event;
use App\Models\EventDeck;
use App\Models\EventDeckCopy;

use App\Models\Card;

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

    private function setUpCards() {

        factory(Card::class)->create([

            'id' => 120, 
            'name' => 'Den Protector'
        ]);

        factory(Card::class)->create([

            'id' => 121, 
            'name' => 'Hangarback Walker'
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

    /** @test */ 
    public function validates_md_count_less_than_60() {

        $this->setUpEvent();

        $this->setUpCards();

        $this->call('POST', '/decks', [

            'player' => 'Bob Jones',
            'finish' => 1,
            'event-id' => 2,
            'decklist' => "4 Den Protector\n55 Forest\n\n\n2 Hangarback Walker"
        ]);

        $this->assertRedirectedTo('/decks/create');

        $this->followRedirects();

        $this->see('This deck only has 59 main deck cards.');
    }

    /** @test */ 
    public function validates_sb_count_more_than_15() {

        $this->setUpEvent();

        $this->setUpCards();

        $this->call('POST', '/decks', [

            'player' => 'Bob Jones',
            'finish' => 1,
            'event-id' => 2,
            'decklist' => "4 Den Protector\n56 Forest\n\n\n16 Plains"
        ]);

        $this->assertRedirectedTo('/decks/create');

        $this->followRedirects();

        $this->see('This deck has 16 sideboard cards.');
    }

    /** @test */
    public function stores_valid_deck() {

        $this->setUpEvent();

        $this->setUpCards();

        $this->call('POST', '/decks', [

            'player' => 'Bob Jones',
            'finish' => 1,
            'event-id' => 2,
            'decklist' => "61 Den Protector\n\n\n2 Hangarback Walker"
        ]);

        $this->assertRedirectedTo('/decks');

        $this->followRedirects();

        $this->see('Success!');

        $deck = EventDeck::where('player', 'Bob Jones')
                          ->where('finish', 1)
                          ->where('event_id', 2)
                          ->get();

        $this->assertCount(1, $deck);

        $copy = EventDeckCopy::where('card_id', 120)
                                ->where('quantity', 61)
                                ->where('role', 'md')
                                ->get();

        $this->assertCount(1, $copy);

        $copy = EventDeckCopy::where('card_id', 121)
                                ->where('quantity', 2)
                                ->where('role', 'sb')
                                ->get();

        $this->assertCount(1, $copy);
    }

}