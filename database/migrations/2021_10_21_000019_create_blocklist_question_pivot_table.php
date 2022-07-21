<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocklistQuestionPivotTable extends Migration
{
    public function up()
    {
        Schema::create('blocklist_question', function (Blueprint $table) {
            $table->unsignedBigInteger('blocklist_id');
            $table->foreign('blocklist_id', 'blocklist_id_fk_5167690')->references('id')->on('blocklists')->onDelete('cascade');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id', 'question_id_fk_5167690')->references('id')->on('questions')->onDelete('cascade');
        });
    }
}
