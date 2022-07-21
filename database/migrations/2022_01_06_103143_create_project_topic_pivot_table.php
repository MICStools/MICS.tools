<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTopicPivotTable extends Migration
{
    public function up()
    {
        Schema::create('project_topic', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id', 'project_id_fk_5167699')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id', 'topic_id_fk_5167699')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_topic');
    }
}
