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
        Schema::create('hasil_pilpres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('provinsi_kode');
            $table->string('kota_kode');
            $table->string('kecamatan_kode');
            $table->string('kelurahan_kode');
            $table->string('nama');
            $table->string('kode');
            $table->integer('tingkat');
            $table->integer('suara_sah')->nullable();
            $table->integer('suara_total')->nullable();
            $table->integer('pemilih_dpt_j')->nullable();
            $table->integer('pemilih_dpt_l')->nullable();
            $table->integer('pemilih_dpt_p')->nullable();
            $table->integer('pengguna_dpt_j')->nullable();
            $table->integer('pengguna_dpt_l')->nullable();
            $table->integer('pengguna_dpt_p')->nullable();
            $table->integer('pengguna_dptb_j')->nullable();
            $table->integer('pengguna_dptb_l')->nullable();
            $table->integer('pengguna_dptb_p')->nullable();
            $table->integer('suara_tidak_sah')->nullable();
            $table->integer('pengguna_total_j')->nullable();
            $table->integer('pengguna_total_l')->nullable();
            $table->integer('pengguna_total_p')->nullable();
            $table->integer('pengguna_non_dpt_j')->nullable();
            $table->integer('pengguna_non_dpt_l')->nullable();
            $table->integer('pengguna_non_dpt_p')->nullable();
            $table->string('psu')->nullable();
            $table->string('ts')->nullable();
            $table->boolean('status_suara')->nullable();
            $table->boolean('status_adm')->nullable();
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
        Schema::dropIfExists('hasil_pilpres');
    }
};
