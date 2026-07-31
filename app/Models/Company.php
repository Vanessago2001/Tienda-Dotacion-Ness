<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'nit',
        'address',
        'phone',
        'email',
        'city',
        'logo'
    ];
}