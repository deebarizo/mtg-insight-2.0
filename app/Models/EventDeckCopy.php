<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDeckCopy extends Model {

	protected $table = 'event_deck_copies';
    
    public function event_deck() {

    	return $this->belongsTo(EventDeck::class);
    }

    public function card() {

    	return $this->belongsTo(Card::class);
    }
}