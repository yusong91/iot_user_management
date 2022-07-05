<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProjectFeatures extends Migration
{
    
    public function up()
    {
        Schema::create('project_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->json('feature');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('project_features');
    }
}
