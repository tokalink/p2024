<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilPilpresDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_pilpres_details', function (Blueprint $table) {
            $table->id();
            $table->string('provisi_kode');
            $table->string('kota_kode');
            $table->string('kecamatan_kode');
            $table->string('kelurahan_kode');
            $table->string('nama');
            $table->string('kode');
            $table->string('pilpres_id');            
            $table->integer('suara');
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
        Schema::dropIfExists('hasil_pilpres_details');
    }
}
