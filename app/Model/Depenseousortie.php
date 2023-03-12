<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Depenseousortie extends Model
{
    protected $guarded = [];

    public function association() {
        return $this->belongsTo('App\Model\Association');
    }
}
