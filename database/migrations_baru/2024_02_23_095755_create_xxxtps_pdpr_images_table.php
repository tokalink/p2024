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
        Schema::create('xxxtps_pdpr_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tps_id');
            $table->string('image')->unique('tps_pdpr_images_image_unique');
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
        Schema::dropIfExists('xxxtps_pdpr_images');
    }
};
