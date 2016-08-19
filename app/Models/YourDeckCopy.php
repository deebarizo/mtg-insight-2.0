<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YourDeckCopy extends Model {

	protected $table = 'your_deck_copies';
    
    public function your_deck() {

    	return $this->belongsTo(YourDeck::class);
    }

    public function card() {

    	return $this->belongsTo(Card::class);
    }
}