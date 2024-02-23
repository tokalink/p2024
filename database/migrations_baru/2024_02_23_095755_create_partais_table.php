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
        Schema::create('partais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('nama_lengkap');
            $table->integer('nomor_urut');
            $table->string('warna');
            $table->boolean('is_aceh');
            $table->integer('id_pilihan');
            $table->integer('id_partai');
            $table->timestamp('ts');
            $table->timestamps();

            $table->unique(['id_pilihan', 'id_partai'], 'partai_pilihan_partai_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partais');
    }
};
