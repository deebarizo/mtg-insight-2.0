<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDeck extends Model {
    
    public function event() {

    	return $this->belongsTo(Event::class);
    }
}