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
        Schema::create('pilpres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pilpres_id');
            $table->string('ts');
            $table->string('nama');
            $table->string('warna');
            $table->string('nomor_urut');
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
        Schema::dropIfExists('pilpres');
    }
};
