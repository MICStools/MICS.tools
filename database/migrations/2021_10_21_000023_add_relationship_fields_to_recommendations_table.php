<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRecommendationsTable extends Migration
{
    public function up()
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->unsignedBigInteger('domain_id');
            $table->foreign('domain_id', 'domain_fk_5167681')->references('id')->on('domains');
        });
    }
}
