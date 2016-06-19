<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model {

    public function sets_cards() {

    	return $this->hasMany(SetCard::class);
    }    

    public function event_deck_copies() {

    	return $this->hasMany(EventDeckCopy::class);
    }
}