<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignDeviceFeaturesTable extends Migration
{
    public function up()
    {
        Schema::create('asign_device_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->json('device_feature');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asign_device_features');
    }
}
