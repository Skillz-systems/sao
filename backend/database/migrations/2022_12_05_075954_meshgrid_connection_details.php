<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MeshgridConnectionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('meshgrid_connection_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('product_type');
            $table->string('product_id');
            $table->string('order_id');
            $table->string('project_id');
            $table->string('client_id');
            $table->integer('status');
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
