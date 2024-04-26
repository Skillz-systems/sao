<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('productname');
            $table->integer('numberofpanels')->nullable();
            $table->integer('numberofbatteries')->nullable();
            $table->integer('numberofinverters')->nullable();
            $table->text('description')->nullable();
            $table->integer('trashed')->default(0)->comment('0 for not trashed, 1 for trashed');
            $table->string('addedby')->nullable()->comment('userid of who added');
            $table->string('inverter_type')->nullable()->comment('userid of who added');
            $table->string('panel_type')->nullable()->comment('userid of who added');
            $table->string('product_type')->nullable()->comment('userid of who added');
            $table->string('light_type')->nullable()->comment('userid of who added');
            $table->string('numberoflight')->nullable()->comment('userid of who added');
            $table->integer('batteries_type')->default(0)->comment('batteries_type to hold the subitem of a battery');
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
        Schema::dropIfExists('products');
    }
}
