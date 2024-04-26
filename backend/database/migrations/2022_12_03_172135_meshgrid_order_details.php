<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MeshgridOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meshgrid_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('meshgrid_project_link_id');
            $table->string('project_id');
            $table->string('client_id');
            $table->string('product_type');
            $table->integer('status');
            $table->timestamps();
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
