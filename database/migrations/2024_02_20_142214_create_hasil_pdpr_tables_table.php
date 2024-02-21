<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilPdprTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // "1": {
        //     "100121": 56932,
        //     "100122": 20783,
        //     "100123": 2404,
        //     "100124": 4092,
        //     "100125": 20887,
        //     "100126": 3812,
        //     "100127": 8344,
        //     "jml_suara_total": 109998,
        //     "jml_suara_partai": 8295
        //    },      
        Schema::create('hasil_pdpr_tables', function (Blueprint $table) {
            $table->id();
            $table->timestamp('ts');
            $table->integer('nomor_urut')->nullable(); //nomor urut partai
            $table->string('dapil_kode')->nullable(); //kode dapil
            $table->integer('jml_suara_total')->nullable();
            $table->integer('jml_suara_partai')->nullable();

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
        Schema::dropIfExists('hasil_pdpr_tables');
    }
}
