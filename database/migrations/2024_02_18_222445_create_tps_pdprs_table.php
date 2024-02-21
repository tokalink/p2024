<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpsPdprsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tps_pdprs', function (Blueprint $table) {
            $table->id();
            $table->string('kelurahan_kode')->unique();
            $table->string('chart')->nullable(); //json
            $table->string('kode_dapil')->nullable();
            $table->string('images')->nullable(); //json array
            $table->string('caleg')->nullable(); //json
            $table->string('administrasi')->nullable(); //json
            $table->string('psu')->nullable(); 
            $table->dateTime('ts')->nullable();
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
        Schema::dropIfExists('tps_pdprs');
    }
}
