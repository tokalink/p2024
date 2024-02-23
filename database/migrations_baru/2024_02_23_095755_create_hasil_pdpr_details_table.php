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
        Schema::create('hasil_pdpr_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provinsi_kode')->nullable();
            $table->string('kota_kode')->nullable();
            $table->string('kecamatan_kode')->nullable();
            $table->string('kelurahan_kode')->nullable();
            $table->string('nama')->nullable();
            $table->string('kode')->nullable();
            $table->string('dpr_id')->nullable();
            $table->string('suara')->nullable();
            $table->timestamps();
            $table->integer('partai_id')->nullable();
            $table->string('kode_dapil')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hasil_pdpr_details');
    }
};
