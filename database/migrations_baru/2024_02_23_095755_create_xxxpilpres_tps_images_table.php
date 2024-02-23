<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xxxpilpres_tps_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tps_id');
            $table->string('image')->unique('pilpres_tps_images_image_unique');
            $table->string('local_image');
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
        Schema::dropIfExists('xxxpilpres_tps_images');
    }
};
