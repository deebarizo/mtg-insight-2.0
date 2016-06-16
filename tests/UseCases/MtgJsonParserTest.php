<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\MtgJsonParser;

use App\Models\Set;
use App\Models\Card;
use App\Models\SetCard;
use App\Models\CardColorIdentity;
use App\Models\CardColor;
use App\Models\CardName;
use App\Models\CardSubtype;
use App\Models\CardSupertype;

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
            ],

            'containsNewCard' => [

                'test.json' => '{"name":"Dragons of Tarkir","code":"DTK","magicCardsInfoCode":"dtk","releaseDate":"2015-03-27","border":"black","type":"expansion","block":"Khans of Tarkir","cards":[{"artist": "Sam Burley","colorIdentity": ["W"],"id": "2c9386d2979ada162160c9217c8e62a52c0320de","imageName": "plains1","layout": "normal","multiverseid": 394649,"name": "Plains","number": "250","rarity": "Basic Land","subtypes": ["Plains"],"supertypes": ["Basic"],"type": "Basic Land — Plains","types": ["Land"],"variations": [394650,394651]},{"artist": "Chase Stone","cmc": 5,"colorIdentity": ["W","U"],"colors": ["White","Blue"],"id": "7d1d4b62cba6d5805bbfaa3a1f97341274a1abb8","imageName": "dragonlord ojutai","layout": "normal","loyalty": 6,"manaCost": "{3}{W}{U}","mciNumber": "219","multiverseid": 394549,"name": "Dragonlord Ojutai","names": ["Archangel Avacyn","Avacyn, the Purifier"],"number": "219","power": "5","rarity": "Mythic Rare","subtypes": ["Elder","Dragon"],"supertypes": ["Legendary","Basic"],"text": "Flying\nDragonlord Ojutai has hexproof as long as it\'s untapped.\nWhenever Dragonlord Ojutai deals combat damage to a player, look at the top three cards of your library. Put one of them into your hand and the rest on the bottom of your library in any order.","toughness": "4","type": "Legendary Creature — Elder Dragon","types": ["Creature"],"watermark": "Ojutai"}]}'
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

        $root = $root = $this->setUpFile($this->files['valid']['containsNewCard']);

        $mtgJsonParser = new MtgJsonParser; 

        $results = $mtgJsonParser->parseJson($root->url().'/test.json');

        $card = Card::where('name', 'Dragonlord Ojutai')->first();

        $this->assertContains($card->mana_cost, '{3}{W}{U}');
        $this->assertContains((string)$card->cmc, '5');
        $this->assertContains($card->middle_text, 'Legendary Creature — Elder Dragon');
        $this->assertContains($card->rules_text, "Flying\nDragonlord Ojutai has hexproof as long as it's untapped.\nWhenever Dragonlord Ojutai deals combat damage to a player, look at the top three cards of your library. Put one of them into your hand and the rest on the bottom of your library in any order.");
        $this->assertContains((string)$card->power, '5');
        $this->assertContains((string)$card->toughness, '4');
        $this->assertContains((string)$card->loyalty, '6');
        $this->assertContains((string)$card->f_cost, '5');
        $this->assertContains($card->layout, 'normal');

        $setCard = SetCard::where('set_id', 3)->where('card_id', $card->id)->first();

        $this->assertContains($setCard->rarity, 'Mythic Rare');
        $this->assertContains((string)$setCard->multiverseid, '394549');

        $cardColorIdentities = CardColorIdentity::where('card_id', $card->id)->get();

        $this->assertCount(2, $cardColorIdentities);

        foreach ($cardColorIdentities as $key => $cardColorIdentity) {
            
            if ($key === 0) {

                $this->assertContains($cardColorIdentity->color_identity, 'White');
            }

            if ($key === 1) {

                $this->assertContains($cardColorIdentity->color_identity, 'Blue');
            }
        }

        $cardColors = CardColor::where('card_id', $card->id)->get();

        $this->assertCount(2, $cardColors);

        foreach ($cardColors as $key => $cardColor) {
            
            if ($key === 0) {

                $this->assertContains($cardColor->color, 'White');
            }

            if ($key === 1) {

                $this->assertContains($cardColor->color, 'Blue');
            }
        }

        $cardNames = CardName::where('card_id', $card->id)->get();

        $this->assertCount(2, $cardNames);

        foreach ($cardNames as $key => $cardName) {
            
            if ($key === 0) {

                $this->assertContains($cardName->name, 'Archangel Avacyn');
            }

            if ($key === 1) {

                $this->assertContains($cardName->name, 'Avacyn, the Purifier');
            }            
        }

        $cardSubtypes = CardSubtype::where('card_id', $card->id)->get();

        $this->assertCount(2, $cardSubtypes);

        foreach ($cardSubtypes as $key => $cardSubtype) {

            if ($key === 0) {

                $this->assertContains($cardSubtype->subtype, 'Elder');
            }

            if ($key === 1) {

                $this->assertContains($cardSubtype->subtype, 'Dragon');
            }   
        }

        $cardSupertypes = CardSupertype::where('card_id', $card->id)->get();

        $this->assertCount(2, $cardSupertypes);

        foreach ($cardSupertypes as $key => $cardSupertype) {

            if ($key === 0) {

                $this->assertContains($cardSupertype->supertype, 'Legendary');
            }

            if ($key === 1) {

                $this->assertContains($cardSupertype->supertype, 'Basic');
            }
        }
    }

}