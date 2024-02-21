<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DapilDpr;
use App\Models\Partai;

class dapildpr_partai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dapildpr';

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
        // self::dapil();
        self::partai();
    }

    // dapil
    public function dapil()
    {
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdpr/dapil_dpr.json";
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        foreach ($data as $key => $value) {
            $cek = DapilDpr::where('id', $value['id'])->first();
            if($cek){
                $cek->nama = $value['nama'];
                $cek->kode = $value['kode'];
                $cek->save();
                continue;
            }
            $mDapilDpr = new DapilDpr();
            $mDapilDpr->id = $value['id'];
            $mDapilDpr->nama = $value['nama'];
            $mDapilDpr->kode = $value['kode'];
            $mDapilDpr->save();
        }
    }

    // partai
    public function partai()
    {
        $url = "https://sirekap-obj-data.kpu.go.id/pemilu/partai.json";
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        foreach ($data as $key => $value) {
            $cek = Partai::where('id_partai', $value['id_partai'])->first();
            if($cek){
                $cek->nama = $value['nama'];
                $cek->nama_lengkap = $value['nama_lengkap'];
                $cek->nomor_urut = $value['nomor_urut'];
                $cek->warna = $value['warna'];
                $cek->is_aceh = $value['is_aceh'];
                $cek->id_pilihan = $value['id_pilihan'];
                $cek->id_partai = $value['id_partai'];
                $cek->ts = $value['ts'];
                $cek->save();
                continue;
            }
            $mPartai = new Partai();
            $mPartai->nama = $value['nama'];
            $mPartai->nama_lengkap = $value['nama_lengkap'];
            $mPartai->nomor_urut = $value['nomor_urut'];
            $mPartai->warna = $value['warna'];
            $mPartai->is_aceh = $value['is_aceh'];
            $mPartai->id_pilihan = $value['id_pilihan'];
            $mPartai->id_partai = $value['id_partai'];
            $mPartai->ts = $value['ts'];
            $mPartai->save();
        }
    }

    // all dapil
    public function all_dapil()
    {
        $dapil = DapilDpr::all();
        foreach ($dapil as $key => $value) {
            self::dapil_per_dapil($value->kode);
        }
    }

    // dapil per dapil
    public function dapil_per_dapil($dapil_kode)
    {
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdpr/dapil_dpr/" . $dapil_kode . ".json";
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        foreach ($data as $key => $value) {
            $cek = DapilDpr::where('id', $value['id'])->first();
            if($cek){
                $cek->nama = $value['nama'];
                $cek->kode = $value['kode'];
                $cek->save();
                continue;
            }
            $mDapilDpr = new DapilDpr();
            $mDapilDpr->id = $value['id'];
            $mDapilDpr->nama = $value['nama'];
            $mDapilDpr->kode = $value['kode'];
            $mDapilDpr->save();
        }
    }
}
