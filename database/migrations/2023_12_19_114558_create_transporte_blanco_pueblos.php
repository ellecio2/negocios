<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transporte_blanco_pueblos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('latitud');
            $table->string('longitud');
            $table->string('dias_disponibles');
            $table->foreignId('transporte_blanco_zona_id')
                ->constrained('transporte_blanco_zonas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transporte_blanco_pueblos');
    }
};
