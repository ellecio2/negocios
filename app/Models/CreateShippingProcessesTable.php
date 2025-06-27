<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('shipping_company_id');
            $table->unsignedBigInteger('user_id');
            $table->string('pedidosya_tracking')->nullable();
            $table->string('shipping_tracking')->nullable();
            $table->string('pedidosya_status')->default('pending');
            $table->string('shipping_status')->default('pending');
            $table->dateTime('pickup_date')->nullable();
            $table->dateTime('estimated_branch_arrival')->nullable();
            $table->dateTime('estimated_delivery_date')->nullable();
            $table->text('pedidosya_data')->nullable();
            $table->text('shipping_data')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('shipping_company_id')->references('id')->on('shipping_companies');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_processes');
    }
}