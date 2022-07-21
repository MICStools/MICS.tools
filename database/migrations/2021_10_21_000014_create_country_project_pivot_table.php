<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryProjectPivotTable extends Migration
{
    public function up()
    {
        Schema::create('country_project', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id', 'project_id_fk_5167668')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id', 'country_id_fk_5167668')->references('id')->on('countries')->onDelete('cascade');
        });
    }
}
