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
        Schema::create('shipping_company_towns', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('latitud');
            $table->string('longitud');
            $table->string('dias_disponibles');
            $table->foreignId('shipping_company_zone_id')
                ->constrained('shipping_company_zones')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_company_towns');
    }
};

// o usar esto falta agregar los update_at
// -- Tabla de ciudades asociadas a una empresa de envío
// CREATE TABLE shipping_company_cities (
//     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     nombre VARCHAR(255) NOT NULL,
//     precio DECIMAL(10,2) NULL,
//     shipping_company_id BIGINT UNSIGNED NOT NULL,
//     FOREIGN KEY (shipping_company_id) REFERENCES shipping_companies(id)
//         ON DELETE CASCADE ON UPDATE CASCADE
// );

// -- Tabla de zonas asociadas a una ciudad de una empresa de envío
// CREATE TABLE shipping_company_zones (
//     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     nombre VARCHAR(255) NOT NULL,
//     latitud VARCHAR(255) NOT NULL,
//     longitud VARCHAR(255) NOT NULL,
//     shipping_company_city_id BIGINT UNSIGNED NOT NULL,
//     FOREIGN KEY (shipping_company_city_id) REFERENCES shipping_company_cities(id)
//         ON DELETE CASCADE ON UPDATE CASCADE
// );

// -- Tabla de pueblos asociados a una zona de una empresa de envío
// CREATE TABLE shipping_company_towns (
//     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     nombre VARCHAR(255) NOT NULL,
//     latitud VARCHAR(255) NOT NULL,
//     longitud VARCHAR(255) NOT NULL,
//     dias_disponibles VARCHAR(255) NOT NULL,
//     shipping_company_zone_id BIGINT UNSIGNED NOT NULL,
//     FOREIGN KEY (shipping_company_zone_id) REFERENCES shipping_company_zones(id)
//         ON DELETE CASCADE ON UPDATE CASCADE
// );