<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Teléfonos y NITs largos no caben en un integer: se guardan como texto.
        Schema::table('companies', function (Blueprint $table) {
            $table->string('nit', 30)->change();
            $table->string('phone', 30)->change();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('nit')->change();
            $table->integer('phone')->change();
        });
    }
};
