<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProjectMeshGrid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_mesh_grid', function (Blueprint $table) {
            $table->id();
            
            $table->string('productname')->nullable();
            $table->integer("project_id")->nullable();
            $table->integer("product_id")->nullable();
            $table->integer("maxmum_connection")->nullable();
            $table->string("mesh_grid_price")->nullable();
            $table->string("pod_installation_price")->nullable();
            $table->integer("deployment_address_id")->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExixts("project_mesh_grid");
    }
}
