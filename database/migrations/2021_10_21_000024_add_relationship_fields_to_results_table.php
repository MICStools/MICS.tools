<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToResultsTable extends Migration
{
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->unsignedBigInteger('domain_id');
            $table->foreign('domain_id', 'domain_fk_5167696')->references('id')->on('domains');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id', 'project_fk_5167697')->references('id')->on('projects');
        });
    }
}
