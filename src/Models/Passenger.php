<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model{
    protected $table = 'passenger';
    protected $fillable = [
        'firstname',
        'lastname',
        'passport_number',
        'mobile',
        'password',
        'address',
    ];

    protected $hidden = [
        'password'
    ];
}