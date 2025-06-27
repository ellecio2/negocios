<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shipping_company_zones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('latitud');
            $table->string('longitud');
            $table->foreignId('shipping_company_city_id')
                ->constrained('shipping_company_cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_company_zones');
    }
};
