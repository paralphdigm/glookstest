<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComPrefUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('com_pref_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();;
            $table->integer('communication_preference_id')->unsigned();;
            $table->primary(['user_id', 'communication_preference_id']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('communication_preference_id','com_pref_id')->references('id')->on('communication_preferences')
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
        Schema::dropIfExists('com_pref_users');
    }
}
