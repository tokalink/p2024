<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW \"partai\" AS  SELECT partais.id_partai,
    partais.id_pilihan,
    partais.is_aceh,
    partais.nama,
    partais.nama_lengkap,
    partais.nomor_urut,
    partais.warna,
    partais.ts
   FROM partais;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS \"partai\"");
    }
};
