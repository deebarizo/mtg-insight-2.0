<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetagameTimePeriod extends Model {
    
    public function set() {

    	return $this->belongsTo(Set::class);
    }
}