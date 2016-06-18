<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    public function event_decks() {

    	return $this->hasMany(EventDeck::class);
    }    
}