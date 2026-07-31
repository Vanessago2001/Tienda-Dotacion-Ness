<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    public $timestamps = true;

    protected $fillable = [
        'lastname',
        'age',
        'email',
        'phone'
    ] ;
}
