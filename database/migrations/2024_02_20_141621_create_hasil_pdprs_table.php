<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilPdprsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ts": "2024-02-20 21:00:00",        
        // "chart": {
        //     "1": 109998,
        //     "2": 32350,
        //     "3": 43327,
        //     "4": 100174,
        //     "5": 54182,
        //     "6": 4355,
        //     "7": 6516,
        //     "8": 39246,
        //     "9": 2412,
        //     "10": 6828,
        //     "11": 2832,
        //     "12": 68594,
        //     "13": 6968,
        //     "14": 45631,
        //     "15": 4838,
        //     "16": 2734,
        //     "17": 45199,
        //     "24": 6528,
        //     "persen": 35.27
        // },
        // "progres": {
        //     "total": 8478,
        //     "progres": 4417
        //   }
        Schema::create('hasil_pdprs', function (Blueprint $table) {
            $table->id();       
            $table->timestamp('ts');
            $table->integer('chart1')->nullable();
            $table->integer('chart2')->nullable();
            $table->integer('chart3')->nullable();
            $table->integer('chart4')->nullable();
            $table->integer('chart5')->nullable();
            $table->integer('chart6')->nullable();
            $table->integer('chart7')->nullable();
            $table->integer('chart8')->nullable();
            $table->integer('chart9')->nullable();
            $table->integer('chart10')->nullable();
            $table->integer('chart11')->nullable();
            $table->integer('chart12')->nullable();
            $table->integer('chart13')->nullable();
            $table->integer('chart14')->nullable();
            $table->integer('chart15')->nullable();
            $table->integer('chart16')->nullable();
            $table->integer('chart17')->nullable();
            $table->integer('chart24')->nullable();
            $table->integer('persen')->nullable();     
            $table->integer('progres_total')->nullable();
            $table->integer('progres')->nullable();
            $table->json('table')->nullable();
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
        Schema::dropIfExists('hasil_pdprs');
    }
}
