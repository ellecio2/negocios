<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users_test', function (Blueprint $table) {
            /*
             * Modificadores:
             * ->nullable() - Permite valores nulos
             * ->default() - Permite valores por defecto
             * ->unsigned() - Solo permite valores positivos
             * ->unique() - Permite valores unicos
             * */

            /*
             * id()
             * Crea un id autoincrementable
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->id();
            /*
             * string()
             * Crea campos varchar
             * Se puede pasar como parametro el nombre del campo y la longitud
             * */
            $table->string('provider', 255)->nullable();
            $table->string('provider_id', 50)->nullable()->unique();
            /*
             * text()
             * Crear campos de tipo text
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->text('refresh_token')->nullable();
            /*
             * longText()
             * Crear campos de tipo longText
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->longText('access_token')->nullable();
            $table->string('user_type', 20)->default('customer');
            $table->string('name', 200);
            $table->string('email', 191)->unique();
            /*
             * timestamp()
             * Crear campos de tipo timestamp
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('correo_verified_at')->nullable();
            $table->string('confirmation_code', 255)->nullable();
            $table->text('verification_code')->nullable();
            $table->text('new_email_verificiation_code')->nullable();
            $table->string('password', 191);
            $table->string('remember_token', 100)->nullable();
            $table->string('device_token', 255)->nullable();
            $table->string('avatar', 256)->nullable();
            $table->string('avatar_original', 256)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('country', 30)->nullable();
            $table->string('state', 30)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('posta_code', 20)->nullable();
            $table->string('phone', 20)->nullable();
            /*
             * double()
             * Crear campos de tipo double
             * Se puede pasar como parametro el nombre del campo, la cantidad de digitos y la cantidad de decimales
             * */
            $table->double('balance', 20, 2)->default(0.00);
            /*
             * boolean()
             * Crear campos de tipo TinyInt(1) acepta solo 0 o 1
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->boolean('banned')->default(0);
            $table->string('referral_code', 255)->nullable();
            /*
             * integer()
             * Crear campos de tipo int
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->integer('customer_package_id')->nullable();
            $table->integer('remaining_uploads')->nullable()->default(0);
            /*
             * unsignedBigInteger()
             * Crear campos de tipo bigInteger y le agrega el modificador unsigned()
             * Se puede pasar como parametro el nombre del campo
             * */
            $table->unsignedBigInteger('customer_translation_id');
            $table->string('add_user_type', 255)->nullable();

            /*
             * foreign()
             *
             * Recibe como parametro el nombre del campo debe estar creado anteriormente
             * de tipo unsignedBigInteger() o unsignedInteger()
             * como se ven en la linea 95
             *
             * Crea una llave foranea
             * Se usa cuando las llaves foraneas no coinciden con los estandares de laravel
             * y no se puede usar el ForeignId()
             *
             * ->references() - Indica el nombre de campo al que hace referencia
             * ->on() - Indica la tabla a la que hace referencia
             * */
            $table->foreign('category_translation_id')->references('id')->on('category_translations');
            /*
             * foreignId()
             *
             * Crea campos varchar, se puede pasar como parametro el nombre del campo y la longitud
             * Recibe como parametro el nombre del campo que tendra la llave foranea
             *
             * ->constrainted() - Indica la tabla a la que hace referencia
             *
             * Hay que tomar en cuenta que este tipo de declaracion de llave foranea
             * debe serguir los estandares de laravel es decir:
             * si le pasamos como parametro al foreignId('category_translation_id')
             * la llave primaria de la tabla 'category_translations' debe llamarse 'id'
             * y la tabla a la que hace referencia debe llamarse 'category_translations'
             * esto para que laravel haga el proceso en automatico
             *
             * NOTA: si los estandares no coinciden con los de laravel no es necesario modificar la BD
             * y hacer un infierno, solo usar el metodo que está arriba de este
             * */
            $table->foreignId('referred_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_test');
    }
};
