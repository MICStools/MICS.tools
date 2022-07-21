<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionRecommendationPivotTable extends Migration
{
    public function up()
    {
        Schema::create('question_recommendation', function (Blueprint $table) {
            $table->unsignedBigInteger('recommendation_id');
            $table->foreign('recommendation_id', 'recommendation_id_fk_5167682')->references('id')->on('recommendations')->onDelete('cascade');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id', 'question_id_fk_5167682')->references('id')->on('questions')->onDelete('cascade');
        });
    }
}
