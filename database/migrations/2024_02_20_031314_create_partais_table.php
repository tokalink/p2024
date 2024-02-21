<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // {
        //     "ts": "2024-02-17 16:00:02",
        //     "id_partai": 100001,
        //     "id_pilihan": 1,
        //     "is_aceh": false,
        //     "nama": "Partai Kebangkitan Bangsa",
        //     "nama_lengkap": "Partai Kebangkitan Bangsa",
        //     "nomor_urut": 1,
        //     "warna": "#00764A"
        //   }
        Schema::create('partais', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_lengkap');
            $table->integer('nomor_urut');
            $table->string('warna');
            $table->boolean('is_aceh');
            $table->integer('id_pilihan');
            $table->integer('id_partai');
            $table->timestamp('ts');
            $table->unique(['id_pilihan', 'id_partai'], 'partai_pilihan_partai_unique');
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
        Schema::dropIfExists('partais');
    }
}
