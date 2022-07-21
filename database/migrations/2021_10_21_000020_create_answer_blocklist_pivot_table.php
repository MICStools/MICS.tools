<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerBlocklistPivotTable extends Migration
{
    public function up()
    {
        Schema::create('answer_blocklist', function (Blueprint $table) {
            $table->unsignedBigInteger('blocklist_id');
            $table->foreign('blocklist_id', 'blocklist_id_fk_5167691')->references('id')->on('blocklists')->onDelete('cascade');
            $table->unsignedBigInteger('answer_id');
            $table->foreign('answer_id', 'answer_id_fk_5167691')->references('id')->on('answers')->onDelete('cascade');
        });
    }
}
