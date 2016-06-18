<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Event;

class EventsControllerTest extends TestCase {

    use DatabaseTransactions;

    private function setUpEvent() {

        factory(Event::class)->create([
        
            'id' => 1,
            'name' => 'SCG Standard Open', 
            'location' => 'Baltimore',
            'date' => '2016-04-09',
            'url' => 'http://sales.starcitygames.com/deckdatabase/deckshow.php?event_ID=19&t[event]=1&start_date=2016-04-09&end_date=2016-04-10&city=&order_1=finish&limit=8&t_num=1&action=Show+Decks'
        ]);
    }

    /** @test */
    public function validates_duplicate_event() {

    	$this->setUpEvent();

        $this->call('POST', '/events', [

            'name' => 'SCG Standard Open',
            'location' => 'Baltimore',
            'date' => '2016-04-09',
            'url' => 'http://sales.starcitygames.com/deckdatabase/deckshow.php?event_ID=19&t[event]=1&start_date=2016-04-09&end_date=2016-04-10&city=&order_1=finish&limit=8&t_num=1&action=Show+Decks'
        ]);

        $this->assertRedirectedTo('/events/create');

        $this->followRedirects();

        $this->see('This event already exists in the database.');
        $this->see('SCG Standard Open');
        $this->see('Baltimore');
        $this->see('2016-04-09');
        $this->see('http://sales.starcitygames.com/deckdatabase/deckshow.php?event_ID=19&t[event]=1&start_date=2016-04-09&end_date=2016-04-10&city=&order_1=finish&limit=8&t_num=1&action=Show+Decks');
    }


}