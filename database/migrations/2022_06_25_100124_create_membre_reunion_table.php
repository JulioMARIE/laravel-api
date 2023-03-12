<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembreReunionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membre_reunion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('membre_id')->nullable();
            $table->unsignedBigInteger('reunion_id');
            $table->string('statutpresence');
            $table->bigInteger('mtcot');
            $table->unique(['membre_id', 'reunion_id']);
            $table->foreign('membre_id')->references('id')->on('membres')->onDelete('set null');
            $table->foreign('reunion_id')->references('id')->on('reunions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membre_reunions');
    }
}
