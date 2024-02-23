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
        DB::statement("CREATE VIEW \"presiden\" AS  SELECT b.kode AS kode_wilayah_provinsi,
    b.nama AS nama_provinsi,
    c.kode AS kode_wilayah_kabupatenkota,
    c.nama AS nama_kabupatenkota,
    d.kode AS kode_wilayah_kecamatan,
    d.nama AS nama_kecamatan,
    e.kode AS kode_wilayah_kelurahan,
    e.nama AS nama_kelurahan,
    a.kode AS kode_wilayah_tps,
    a.nama AS tps,
    sum(
        CASE
            WHEN ((f.pilpres_id)::text = '100025'::text) THEN f.suara
            ELSE 0
        END) AS suara_paslon_1,
    sum(
        CASE
            WHEN ((f.pilpres_id)::text = '100026'::text) THEN f.suara
            ELSE 0
        END) AS suara_paslon_2,
    sum(
        CASE
            WHEN ((f.pilpres_id)::text = '100027'::text) THEN f.suara
            ELSE 0
        END) AS suara_paslon_3,
    g.images,
    a.pemilih_dpt_j,
    a.pemilih_dpt_l,
    a.pemilih_dpt_p,
    a.pengguna_dpt_j,
    a.pengguna_dpt_l,
    a.pengguna_dpt_p,
    a.pengguna_dptb_j,
    a.pengguna_dptb_l,
    a.pengguna_dptb_p,
    a.pengguna_non_dpt_j,
    a.pengguna_non_dpt_l,
    a.pengguna_non_dpt_p,
    a.pengguna_total_j,
    a.pengguna_total_l,
    a.pengguna_total_p,
    a.suara_sah,
    a.suara_tidak_sah,
    a.suara_total,
    a.psu,
    a.status_adm,
    a.status_suara,
    a.ts
   FROM ((((((hasil_pilpres a
     LEFT JOIN provinsis b ON (((a.provinsi_kode)::text = (b.kode)::text)))
     LEFT JOIN kotas c ON (((a.kota_kode)::text = (c.kode)::text)))
     LEFT JOIN kecamatans d ON (((a.kecamatan_kode)::text = (d.kode)::text)))
     LEFT JOIN kelurahans e ON (((a.kelurahan_kode)::text = (e.kode)::text)))
     LEFT JOIN hasil_pilpres_details f ON (((a.kode)::text = (f.kode)::text)))
     LEFT JOIN vpilpres_images g ON (((a.kode)::text = (g.kode)::text)))
  GROUP BY b.kode, b.nama, c.kode, c.nama, d.kode, d.nama, e.kode, e.nama, a.kode, a.nama, a.pemilih_dpt_j, a.pemilih_dpt_l, a.pemilih_dpt_p, a.pengguna_dpt_j, a.pengguna_dpt_l, a.pengguna_dpt_p, a.pengguna_dptb_j, a.pengguna_dptb_l, a.pengguna_dptb_p, a.pengguna_non_dpt_j, a.pengguna_non_dpt_l, a.pengguna_non_dpt_p, a.pengguna_total_j, a.pengguna_total_l, a.pengguna_total_p, a.suara_sah, a.suara_tidak_sah, a.suara_total, a.psu, a.status_adm, a.status_suara, a.ts, g.images;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS \"presiden\"");
    }
};
