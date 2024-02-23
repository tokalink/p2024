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
        Schema::create('dapil_dprs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_dapil');
            $table->string('id_dapil');
            $table->timestamps();
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
        Schema::dropIfExists('dapil_dprs');
    }
};
