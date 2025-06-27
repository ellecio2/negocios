<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transporte_blanco_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('precio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transporte_blanco_categorias');
    }
};
