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
        DB::statement("CREATE VIEW \"wilayah\" AS  SELECT provinsis.kode,
    provinsis.nama,
    provinsis.tingkat
   FROM provinsis
UNION
 SELECT kotas.kode,
    kotas.nama,
    kotas.tingkat
   FROM kotas
UNION
 SELECT kecamatans.kode,
    kecamatans.nama,
    kecamatans.tingkat
   FROM kecamatans
UNION
 SELECT kelurahans.kode,
    kelurahans.nama,
    kelurahans.tingkat
   FROM kelurahans
  ORDER BY 3, 1;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS \"wilayah\"");
    }
};
