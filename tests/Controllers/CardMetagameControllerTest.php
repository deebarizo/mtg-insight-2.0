<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Card;

use App\Models\CardMetagame;

class CardMetagameControllerTest extends TestCase {

    use DatabaseTransactions;

    private function setUpCards() {

        factory(Card::class)->create([

            'id' => 120, 
            'name' => 'Den Protector'
        ]);
    }

    private function setUpCardMetagame() {

    	factory(CardMetagame::class)->create([

    		'id' => 1,
    		'date' => '2016-06-20',
    		'card_id' => 120
    	]);
    }

    /** @test */
    public function validates_required_inputs() {

        $this->call('POST', '/card_metagame', [

            'date' => ''
        ]);

        $this->assertSessionHasErrors(['date']);
    }

    /** @test */
    public function validates_date_format() {

        $this->call('POST', '/card_metagame', [

            'date' => 'bob'
        ]);

        $this->assertSessionHasErrors(['date']);
    }

    /** @test */
    public function validates_duplicate_date() {

    	$this->setUpCards();
    	$this->setUpCardMetagame();

        $this->call('POST', '/card_metagame', [

        	'date' => '2016-06-20'
        ]);

        $this->assertSessionHasErrors(['date']);
    }

}