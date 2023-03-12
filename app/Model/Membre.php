<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'addresse',
        'contact',
        'mdp',
        'datenaissance',
        'photoprofil',
        'profession',
        'situationmatri',
    ];

    public function association(){
        return $this->belongsTo('App\Model\Association');
    }

    public function reunions(){
        return $this->belongsToMany('App\Model\Reunion')->withPivot(['statutpresence','mtcot']);
    }
}
