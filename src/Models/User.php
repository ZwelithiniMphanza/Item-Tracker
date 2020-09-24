<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    protected $fillable = [
        'firsname',
        'lastname',
        'employee_id',
        'address',
        'password',
    ];

    protected $hidden = [
        'password'
    ];
}