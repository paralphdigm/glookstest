<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSizeTypeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_type_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('size_category_id');
            $table->string('size');
            $table->timestamps();

            $table->foreign('size_category_id')->references('id')->on('size_categories')
                ->onUpdate('cascade')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('size_type_categories');
    }
}
