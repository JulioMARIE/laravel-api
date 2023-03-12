<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function membres(){
        return $this->hasMany('App\Model\Membre');
    }

    public function reunions(){
        return $this->hasMany('App\Model\Reunion');
    }

    public function autrerecettes(){
        return $this->hasMany('App\Model\Autrerecette');
    }

    public function depenseousorties(){
        return $this->hasMany('App\Model\Depenseousortie');
    }
}
