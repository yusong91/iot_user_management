<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
   
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 190)->nullable();
            $table->string('value', 190)->nullable();
            $table->string('link', 190)->nullable();
            $table->string('image', 190)->nullable();
            $table->string('color', 190)->nullable();
            $table->string('ordering', 11)->nullable();
            $table->string('active', 5)->nullable();
            $table->text('description')->nullable();
            $table->integer('parent_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('folders');
    }
}
