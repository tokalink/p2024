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
        Schema::create('xxxtps_pdprs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kelurahan_kode')->unique('tps_pdprs_kelurahan_kode_unique');
            $table->string('chart')->nullable();
            $table->string('kode_dapil')->nullable();
            $table->string('images')->nullable();
            $table->string('caleg')->nullable();
            $table->string('administrasi')->nullable();
            $table->string('psu')->nullable();
            $table->timestamp('ts')->nullable();
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
        Schema::dropIfExists('xxxtps_pdprs');
    }
};
