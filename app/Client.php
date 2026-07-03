<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_1',
        'phone_2',
        'adresse',
        'region',
        'ville',
        'code_postale',
        'matricule',
        'source',
    ];
}
