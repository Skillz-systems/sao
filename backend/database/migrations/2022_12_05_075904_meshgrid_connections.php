<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MeshgridConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('meshgrid_connections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('connection_number');
            $table->string('order_description');
            $table->string('meshgrid_id');
            $table->string('client_id');
            $table->string('address_id');
            $table->string('amount');
            $table->string('actual_amount');
            $table->string('discount');
            $table->integer('status');
            $table->integer('payment_status')->default(0);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
