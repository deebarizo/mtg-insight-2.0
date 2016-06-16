<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\MtgJsonParser;

use App\Models\Set;
use App\Models\Card;
use App\Models\SetCard;

class MtgJsonParserTest extends TestCase {

    use DatabaseTransactions;

    private $files = [

	    'invalid' => [

            'missingSet' => [

                'test.json' => '{"name":"Bob Set","code":"BOB","magicCardsInfoCode":"bob","releaseDate":"2015-03-27","border":"black","type":"expansion","block":"Bob Block"}'
            ]
        ],

        'valid' => [

        	'containsBasicLand' => [

        		'test.json' => '{"name":"Dragons of Tarkir","code":"DTK","magicCardsInfoCode":"dtk","releaseDate":"2015-03-27","border":"black","type":"expansion","block":"Khans of Tarkir","cards":[{"artist": "Sam Burley","colorIdentity": ["W"],"id": "2c9386d2979ada162160c9217c8e62a52c0320de","imageName": "plains1","layout": "normal","multiverseid": 394649,"name": "Plains","number": "250","rarity": "Basic Land","subtypes": ["Plains"],"supertypes": ["Basic"],"type": "Basic Land — Plains","types": ["Land"],"variations": [394650,394651]},{"artist": "Chase Stone","cmc": 5,"colorIdentity": ["W","U"],"colors": ["White","Blue"],"id": "7d1d4b62cba6d5805bbfaa3a1f97341274a1abb8","imageName": "dragonlord ojutai","layout": "normal","manaCost": "{3}{W}{U}","mciNumber": "219","multiverseid": 394549,"name": "Dragonlord Ojutai","number": "219","power": "5","rarity": "Mythic Rare","subtypes": ["Elder","Dragon"],"supertypes": ["Legendary"],"text": "Flying\nDragonlord Ojutai has hexproof as long as it\'s untapped.\nWhenever Dragonlord Ojutai deals combat damage to a player, look at the top three cards of your library. Put one of them into your hand and the rest on the bottom of your library in any order.","toughness": "4","type": "Legendary Creature — Elder Dragon","types": ["Creature"],"watermark": "Ojutai"}]}'
			],

            'containsExistingCard' => [

                'test.json' => '{"name":"Dragons of Tarkir","code":"DTK","magicCardsInfoCode":"dtk","releaseDate":"2015-03-27","border":"black","type":"expansion","block":"Khans of Tarkir","cards":[{"artist": "Sam Burley","colorIdentity": ["W"],"id": "2c9386d2979ada162160c9217c8e62a52c0320de","imageName": "plains1","layout": "normal","multiverseid": 394649,"name": "Plains","number": "250","rarity": "Basic Land","subtypes": ["Plains"],"supertypes": ["Basic"],"type": "Basic Land — Plains","types": ["Land"],"variations": [394650,394651]},{"artist": "Chase Stone","cmc": 5,"colorIdentity": ["W","U"],"colors": ["White","Blue"],"id": "7d1d4b62cba6d5805bbfaa3a1f97341274a1abb8","imageName": "dragonlord ojutai","layout": "normal","manaCost": "{3}{W}{U}","mciNumber": "219","multiverseid": 394549,"name": "Dragonlord Ojutai","number": "219","power": "5","rarity": "Mythic Rare","subtypes": ["Elder","Dragon"],"supertypes": ["Legendary"],"text": "Flying\nDragonlord Ojutai has hexproof as long as it\'s untapped.\nWhenever Dragonlord Ojutai deals combat damage to a player, look at the top three cards of your library. Put one of them into your hand and the rest on the bottom of your library in any order.","toughness": "4","type": "Legendary Creature — Elder Dragon","types": ["Creature"],"watermark": "Ojutai"}]}'
            ]            
        ]
    ];

    private function setUpFile($file) {

        $root = vfsStream::setup('root', null, $file);

        $this->assertTrue($root->hasChild('test.json'));

        return $root;
    }

    /** @test */
    public function validates_missing_set() {

        $root = $this->setUpFile($this->files['invalid']['missingSet']);

        $mtgJsonParser = new MtgJsonParser; 
        
        $results = $mtgJsonParser->parseJson($root->url().'/test.json');

        $this->assertContains($results->message, 'The set "BOB" is missing from the database. Please add it manually to the database.');
    }

    /** @test */
    public function skips_basic_land() {

        $root = $root = $this->setUpFile($this->files['valid']['containsBasicLand']);

        $mtgJsonParser = new MtgJsonParser; 

        $results = $mtgJsonParser->parseJson($root->url().'/test.json');
        
        $this->assertContains((string)$results->basicLandCount, '1');
    }

    /** @test */
    public function checks_for_existing_card() {

        factory(Card::class)->create([

            'name' => 'Dragonlord Ojutai'
        ]);

        $root = $root = $this->setUpFile($this->files['valid']['containsExistingCard']);

        $mtgJsonParser = new MtgJsonParser; 

        $results = $mtgJsonParser->parseJson($root->url().'/test.json');
        
        $this->assertContains((string)$results->cardExistsCount, '1');
    }

    /** @test */
    public function stores_new_card() {    

        $root = $root = $this->setUpFile($this->files['valid']['containsExistingCard']);

        $mtgJsonParser = new MtgJsonParser; 

        $results = $mtgJsonParser->parseJson($root->url().'/test.json');

        $card = Card::where('name', 'Dragonlord Ojutai')->first();

        $this->assertContains($card->mana_cost, '{3}{W}{U}');
        $this->assertContains((string)$card->cmc, '5');
        $this->assertContains($card->middle_text, 'Legendary Creature — Elder Dragon');
        $this->assertContains($card->rules_text, "Flying\nDragonlord Ojutai has hexproof as long as it's untapped.\nWhenever Dragonlord Ojutai deals combat damage to a player, look at the top three cards of your library. Put one of them into your hand and the rest on the bottom of your library in any order.");
        $this->assertContains((string)$card->power, '5');
        $this->assertContains((string)$card->toughness, '4');
        $this->assertContains((string)$card->f_cost, '5');
        $this->assertContains($card->layout, 'normal');

        $setCard = SetCard::where('set_id', 3)->where('card_id', $card->id)->first();

        $this->assertContains($setCard->rarity, 'Mythic Rare');
        $this->assertContains((string)$setCard->multiverseid, '394549');
    }

}