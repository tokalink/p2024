<?php

namespace App\Console\Commands;

use App\Models\DapilDpr;
use App\Models\HasilPdpr;
use App\Models\Kota;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DPR_RI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dpr';

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
        self::DPR();
        // self::getDapilRi();
        // self::getDapilProvinsi();
        // self::getDapilKota();
        return 0;
    }

    public function httpGet($url)
    {
        $url = $url;
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        return $data;
    }

    public function DPR(){
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdpr/dapil_dpr.json";
        $resp = self::httpGet($url);
        foreach($resp as $res){
            echo "DAPIL RI : ".$res['nama']."\n";
            $url = "https://sirekap-obj-data.kpu.go.id/pemilu/hhcd/pdpr/".$res['kode'].".json";
            $suara = self::httpGet($url);
            $pdpr = new HasilPdpr();
            $pdpr->ts = $suara['ts'];
            $pdpr->chart1 = $suara['chart']['1'];
            $pdpr->chart2 = $suara['chart']['2'];
            $pdpr->chart3 = $suara['chart']['3'];
            $pdpr->chart4 = $suara['chart']['4'];
            $pdpr->chart5 = $suara['chart']['5'];
            $pdpr->chart6 = $suara['chart']['6'];
            $pdpr->chart7 = $suara['chart']['7'];
            $pdpr->chart8 = $suara['chart']['8'];
            $pdpr->chart9 = $suara['chart']['9'];
            $pdpr->chart10 = $suara['chart']['10'];
            $pdpr->chart11 = $suara['chart']['11'];
            $pdpr->chart12 = $suara['chart']['12'];
            $pdpr->chart13 = $suara['chart']['13'];
            $pdpr->chart14 = $suara['chart']['14'];
            $pdpr->chart15 = $suara['chart']['15'];
            $pdpr->chart16 = $suara['chart']['16'];
            $pdpr->chart17 = $suara['chart']['17'];
            $pdpr->chart24 = $suara['chart']['24'];
            $pdpr->persen = $suara['chart']['persen'];
            $pdpr->progres_total = $suara['progres']['total'];
            $pdpr->progres = $suara['progres']['progres'];
            $pdpr->kode = $res['kode'];
            $pdpr->save();
        }
        return 0;
    }

    public function getDapilKota()
    {
        $prov = Kota::whereRaw('LENGTH(kode) = 4')->where('kode','NOT LIKE','99%')->get();
        foreach($prov as $p){
            $provinsi_kode = substr($p->kode, 0, 2);
            $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdprdk/$provinsi_kode/$p->kode.json";
            $resp = self::httpGet($url);
            echo "Kota : $p->nama Jumlah Dapil: ".count($resp)."\n";
            foreach($resp as $res){
                $dapil = DapilDpr::where('id_dapil', $res['id_dapil'])->where('kode_dapil',$res['kode_dapil'])->where('nama_dapil',$res['nama_dapil'])->first();
                if(!$dapil){
                    $dapil = new DapilDpr();
                }
                $dapil->nama_dapil = $res['nama_dapil'];
                $dapil->id_dapil = $res['id_dapil'];
                $dapil->kode_dapil = $res['kode_dapil'];
                $dapil->save();
            }
        }
        return 0;
    }

    public function getDapilProvinsi()
    {
        $prov = DB::table('wilayah_2022')->whereRaw('LENGTH(kode) = 2')->where('kode','<>','99')->get();
        foreach($prov as $p){
            $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdprdp/$p->kode.json";
            $resp = self::httpGet($url);
            echo "Provinsi : $p->nama Jumlah Dapil: ".count($resp)."\n";
            foreach($resp as $res){
                $dapil = DapilDpr::where('id_dapil', $res['id_dapil'])->where('kode_dapil',$res['kode_dapil'])->where('nama_dapil',$res['nama_dapil'])->first();
                if(!$dapil){
                    $dapil = new DapilDpr();
                }
                $dapil->nama_dapil = $res['nama_dapil'];
                $dapil->id_dapil = $res['id_dapil'];
                $dapil->kode_dapil = $res['kode_dapil'];
                $dapil->save();
            }
        }
        return 0;
    }

    public function getDapilRi()
    {
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdpr/dapil_dpr.json";
        $resp = self::httpGet($url);
        foreach($resp as $res){
            echo "DAPIL RI : ".$res['nama']."\n";
            $dapil = DapilDpr::where('id_dapil', $res['id'])->where('kode_dapil',$res['kode'])->where('nama_dapil',$res['nama'])->first();
            if(!$dapil){
                $dapil = new DapilDpr();
            }
            $dapil->nama_dapil = $res['nama'];
            $dapil->id_dapil = $res['id'];
            $dapil->kode_dapil = $res['kode'];
            $dapil->save();
        }   
        return 0;
    }


}
