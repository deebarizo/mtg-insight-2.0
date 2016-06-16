<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model {
    
    public function sets_cards() {

    	return $this->hasMany(SetCard::class);
    }
}