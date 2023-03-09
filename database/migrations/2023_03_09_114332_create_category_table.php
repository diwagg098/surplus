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
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->boolean('enable');
            $table->timestamps();
        });

        Schema::create('products', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('description');
            $table->boolean('enable');
            $table->timestamps();
        });

        Schema::create('category_products', function(Blueprint $table){
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });

        Schema::create('images', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('file');
            $table->boolean('enable');
            $table->timestamps();
        });

        Schema::create('product_images', function(Blueprint $table){
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('image_id');
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('images');
        Schema::dropIfExists('category_products');
        Schema::dropIfExists('product_images');
    }
};
