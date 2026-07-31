<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'email',
        'company_email',
        'phone',
        'company_phone',
        'product',
        'company',
        'photo'
    ];
}