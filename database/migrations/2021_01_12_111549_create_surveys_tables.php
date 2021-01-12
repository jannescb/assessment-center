<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->boolean('active');
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_translations', function (Blueprint $table) {
            $table->translates('surveys');

            // Set all columns that are translated here.
            // Set them to fillable in the translation Model.

            $table->string('title')->nullable();
            $table->string('subject')->nullable();

            $table->string('slug')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_translations');
        Schema::dropIfExists('surveys');
    }
}
