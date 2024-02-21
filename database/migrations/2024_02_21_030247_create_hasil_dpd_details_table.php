<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilDpdDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_dpd_details', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi_kode')->nullable();
            $table->string('kota_kode')->nullable();
            $table->string('kecamatan_kode')->nullable();
            $table->string('kelurahan_kode')->nullable();
            $table->string('nama')->nullable();
            $table->string('kode')->nullable();
            $table->string('dpd_id')->nullable();
            $table->string('suara')->nullable();
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
        Schema::dropIfExists('hasil_dpd_details');
    }
}
