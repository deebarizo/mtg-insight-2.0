<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YourDeck extends Model {

    public function your_deck_copies() {

    	return $this->hasMany(YourDeckCopy::class);
    }  
} 