<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardColorIdentity extends Model {
    
    public function card() {

    	return $this->belongsTo(Card::class);
    }
}