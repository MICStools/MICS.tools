<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order');
            $table->string('type');
            $table->string('title');
            $table->longText('text');
            $table->longText('help')->nullable();
            $table->longText('information')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
