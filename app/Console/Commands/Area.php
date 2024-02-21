<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pilpres;
use App\Models\Provinsi;
use Illuminate\Console\Command;

class Area extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'a';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "Area 2024";
        // self::pilpres();
        self::provinsi();
        

    }

    // pilpres
    private function pilpres()
    {
        echo "Pilpres 2024\n";
        $url = "https://sirekap-obj-data.kpu.go.id/pemilu/ppwp.json";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $cek = Pilpres::where('pilpres_id', $key)->first();
            if ($cek) {
                $cek->ts = $value['ts'];
                $cek->nama = $value['nama'];
                $cek->warna = $value['warna'];
                $cek->nomor_urut = $value['nomor_urut'];
                $cek->save();
                continue;
            }
            $pilres = new Pilpres();
            $pilres->pilpres_id = $key;
            $pilres->ts = $value['ts'];
            $pilres->nama = $value['nama'];
            $pilres->warna = $value['warna'];
            $pilres->nomor_urut = $value['nomor_urut'];
            $pilres->save();
        }
    }

    // Provinsi
    private function provinsi()
    {
        echo "Provinsi\n";
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/0.json";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $cek = Provinsi::where('id', $value['id'])->first();
            echo "Kota".$value['nama']."\n";
            self::kota($value['kode']);
            if ($cek) {
                $cek->nama = $value['nama'];
                $cek->kode = $value['kode'];                
                $cek->tingkat = $value['tingkat'];
                $cek->save();
                continue;
            }
            $provinsi = new Provinsi();
            $provinsi->id = $value['id'];
            $provinsi->nama = $value['nama'];
            $provinsi->kode = $value['kode'];
            $provinsi->tingkat = $value['tingkat'];
            $provinsi->save();            
        }
    }

    // kota
    private function kota($pro_id)
    {
        echo "Kota";
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/".$pro_id.".json";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $cek = Kota::where('id', $value['id'])->first();
            self::kecamatan($pro_id,$value['kode']);
            if ($cek) {
                $cek->nama = $value['nama'];
                $cek->kode = $value['kode'];                
                $cek->tingkat = $value['tingkat'];
                $cek->save();
                continue;
            }
            $provinsi = new Kota();
            $provinsi->id = $value['id'];
            $provinsi->nama = $value['nama'];
            $provinsi->kode = $value['kode'];
            $provinsi->tingkat = $value['tingkat'];
            $provinsi->save();
        }
    }

    // Kecamatan
    private function kecamatan($prov_id,$kode)
    {
        echo "Kecamatan\n";
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/".$prov_id."/".$kode.".json";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $cek = Kecamatan::where('id', $value['id'])->first();
            self::kelurahan($prov_id,$kode,$value['kode']);
            if ($cek) {
                $cek->nama = $value['nama'];
                $cek->kode = $value['kode'];                
                $cek->tingkat = $value['tingkat'];
                $cek->save();
                continue;
            }
            $provinsi = new Kecamatan();
            $provinsi->id = $value['id'];
            $provinsi->nama = $value['nama'];
            $provinsi->kode = $value['kode'];
            $provinsi->tingkat = $value['tingkat'];
            $provinsi->save();            
        }
    }

    // kelurahan
    private function kelurahan($pro_id,$kota,$kec)
    {
        echo "Kelurahan\n";
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/".$pro_id."/".$kota."/".$kec.".json";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $cek = Kelurahan::where('id', $value['id'])->first();
            if ($cek) {
                $cek->nama = $value['nama'];
                $cek->kode = $value['kode'];                
                $cek->tingkat = $value['tingkat'];
                $cek->save();
                continue;
            }
            $provinsi = new Kelurahan();
            $provinsi->id = $value['id'];
            $provinsi->nama = $value['nama'];
            $provinsi->kode = $value['kode'];
            $provinsi->tingkat = $value['tingkat'];
            $provinsi->save();            
        }
    }
}
