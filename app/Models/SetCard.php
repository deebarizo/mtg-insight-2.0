<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetCard extends Model {
    
    protected $table = 'sets_cards';

    public function set() {

    	return $this->belongsTo(Set::class);
    }

    public function card() {

    	return $this->belongsTo(Card::class);
    }
}