<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('security_question_users', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('security_question_id');
            $table->string('answer');
            $table->primary(['user_id', 'security_question_id']);

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('security_question_id')->references('id')->on('security_questions')
                ->onUpdate('cascade')->onDelete('cascade');
                
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
        Schema::dropIfExists('security_questions');
        Schema::dropIfExists('security_question_users');
    }
}
