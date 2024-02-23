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
        Schema::create('hasil_pdpr_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('ts');
            $table->string('dapil_kode')->nullable();
            $table->integer('jml_suara_total')->nullable();
            $table->integer('jml_suara_partai')->nullable();
            $table->timestamps();
            $table->integer('partai_id')->nullable();
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
};
