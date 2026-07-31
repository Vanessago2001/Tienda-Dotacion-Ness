<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'description',
        'amount',
        'price',
        'state',
        'customer',
        'date'
    ];

    // Casteamos la fecha para poder usarla fácilmente en las vistas
    protected $casts = [
        'date' => 'datetime',
    ];
}