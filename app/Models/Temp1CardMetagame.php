<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temp1CardMetagame extends Model {

	protected $table = 'temp1_card_metagames';
    
    public function card() {

    	return $this->belongsTo(Card::class);
    }
}