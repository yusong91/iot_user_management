<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableAssignFolder extends Migration
{
   
    public function up()
    {
        Schema::create('asign_folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->integer('parent_id')->unsigned();
            $table->json('folder');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asign_folders');
    }
}
