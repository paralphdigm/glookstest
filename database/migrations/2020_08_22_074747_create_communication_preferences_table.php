<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communication_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('preference_category_id')->unsigned();;
            $table->string('name');
            $table->timestamps();

            $table->foreign('preference_category_id')->references('id')->on('preference_categories')
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
        Schema::dropIfExists('communication_preferences');
    }
}
