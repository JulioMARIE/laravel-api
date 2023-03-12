<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $guarded = [];

    public function membres(){
        return $this->belongsToMany('App\Model\Membre')->withPivot(['statutpresence','mtcot']);
    }
    public function association(){
        return $this->belongsTo('App\Model\Association');
    }
}
