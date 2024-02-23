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
        Schema::create('xxxtps_pdpr_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tps_id');
            $table->string('partai_id');
            $table->string('caleg_id');
            $table->integer('suara');
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
        Schema::dropIfExists('xxxtps_pdpr_details');
    }
};
