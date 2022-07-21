<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerProjectPivotTable extends Migration
{
    public function up()
    {
        Schema::create('answer_project', function (Blueprint $table) {
            $table->unsignedBigInteger('answer_id');
            $table->foreign('answer_id', 'answer_id_fk_5167677')->references('id')->on('answers')->onDelete('cascade');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id', 'project_id_fk_5167677')->references('id')->on('projects')->onDelete('cascade');
        });
    }
}
