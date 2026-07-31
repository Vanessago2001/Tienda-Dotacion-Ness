<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'type',
        'date',
        'state',
        'invoice',
        'cashier'
    ];

    // Esto ayuda a que Laravel trate el campo como un objeto Carbon automáticamente
    protected $casts = [
        'date' => 'datetime',
    ];
}