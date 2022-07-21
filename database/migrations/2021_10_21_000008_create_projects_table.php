<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('shortname')->unique();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->longText('shortdescription')->nullable();
            $table->boolean('featured')->default(0)->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->string('contact')->nullable();
            $table->string('contactdetails')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->decimal('funding', 15, 2)->nullable();
            $table->string('uri')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
