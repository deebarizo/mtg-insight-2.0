<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use org\bovigo\vfs\vfsStream; // http://blog.mauriziobonani.com/phpunit-test-file-system-with-vfsstream/

use App\UseCases\MtgJsonParser;

use App\Set;
use App\Card;
use App\SetCard;

class MtgJsonParserTest extends TestCase {

    use DatabaseTransactions;

    private function setUpSets() {

        factory(Set::class)->create([
        
        	'code' => 'DTK'
        ]);   
    }

    private $files = [

	    'invalid' => [

            'missingSet' => [

                'test.json' => '{"name":"Dragons of Tarkir","code":"BOB","magicCardsInfoCode":"dtk","releaseDate":"2015-03-27","border":"black","type":"expansion","block":"Khans of Tarkir"}'
            ]
        ],

        'valid' => [

        	'cardIsBasicLand' => [

        		'test.json' => '{"name":"Dragons of Tarkir","code":"BOB","magicCardsInfoCode":"dtk","releaseDate":"2015-03-27","border":"black","type":"expansion","block":"Khans of Tarkir","cards":[{"artist": "Sam Burley","colorIdentity": ["W"],"id": "2c9386d2979ada162160c9217c8e62a52c0320de","imageName": "plains1","layout": "normal","multiverseid": 394649,"name": "Plains","number": "250","rarity": "Basic Land","subtypes": ["Plains"],"supertypes": ["Basic"],"type": "Basic Land — Plains","types": ["Land"],"variations": [394650,394651]},{"artist": "Sam Burley","colorIdentity": ["W"],"id": "2c9386d2979ada162160c9217c8e62a52c0320de","imageName": "plains1","layout": "normal","multiverseid": 394649,"name": "Hangarback Walker","number": "250","rarity": "Basic Land","subtypes": ["Plains"],"supertypes": ["Basic"],"type": "Basic Land — Plains","types": ["Land"],"variations": [394650,394651]}]}'
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

    	$this->setUpSets();

        $root = $root = $this->setUpFile($this->files['invalid']['missingSet']);

        $mtgJsonParser = new MtgJsonParser; 
        
        $results = $mtgJsonParser->parseJson($root->url().'/test.json');

        $this->assertContains($results->message, 'The set "BOB" is missing from the database. Please add it manually to the database.');
    }

    /** @test */
    public function skips_basic_land() {

    	$this->setUpSets();

        $root = $root = $this->setUpFile($this->files['valid']['cardIsBasicLand']);

        $mtgJsonParser = new MtgJsonParser; 

        $results = $mtgJsonParser->parseJson($root->url().'/test.json');
        
        $cards = Card::where('name', 'Plains')->get();

        $this->assertCount(0, $cards);

        $cards = Card::where('name', 'Hangarback Walker')->get();

        $this->assertCount(1, $cards);
    }

}