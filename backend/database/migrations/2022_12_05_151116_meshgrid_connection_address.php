<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MeshgridConnectionAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('meshgrid_connection_address', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('meshgrid_id');
            $table->string('client_id');
            $table->string('log');
            $table->string('lat');
            $table->string('address_description');
            $table->string('states_id');
            $table->string('lgas_id');
            $table->integer('status')->default(1);
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
