<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataDpdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_dpds', function (Blueprint $table) {
            $table->id();
            $table->string('dpd_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('nomor_urut')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_tinggal')->nullable();
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
        Schema::dropIfExists('data_dpds');
    }
}
