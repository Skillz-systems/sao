<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subitems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('itemid');
            $table->string('name');
            $table->string('trashed')->default(0);
            $table->string('hasb3')->nullable();
            $table->string('userid')->nullable();
            $table->string('referencenumber')->nullable();
            $table->string('status')->default(0);
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
        Schema::dropIfExists('subitems');
    }
}
