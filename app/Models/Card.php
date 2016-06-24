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

    public function temp1_card_metagames() {

    	return $this->hasMany(Temp1CardMetagame::class);
    }

    public function card_metagames() {

    	return $this->hasMany(CardMetagame::class);
    }

    public function card_tags() {

        return $this->hasMany(CardTag::class);
    }

    public function card_types() {

        return $this->hasMany(CardType::class);
    }
}