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
        DB::statement("CREATE VIEW \"vpilpres_images\" AS  SELECT hasil_pilpres_images.kode,
    array_to_string(array_agg(hasil_pilpres_images.image), ','::text) AS images
   FROM hasil_pilpres_images
  GROUP BY hasil_pilpres_images.kode;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS \"vpilpres_images\"");
    }
};
