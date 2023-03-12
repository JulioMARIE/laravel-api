<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MembreReunion extends Model
{
    const STATUTPRESENCE_PRES = 'présent';
    const STATUTPRESENCE_ABS = 'absent';
    const STATUTPRESENCE_PERMI = 'permissionnaire';

    protected $fillable = [
        'statutpresence', 'mtcot'
    ];
}
