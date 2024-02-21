<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\TpsPdprDetail;
use App\Models\TpsPdprImage;
use Illuminate\Console\Command;

class Pdpr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdpr';

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
        // $kelursahan = Kelurahan::take(1)->whereNull('ls_pdpr')->get();
        self::pdpr();
    }

    private function pdpr()
    {
        echo "PDPR 2024\n";
        $url = "https://sirekap-obj-data.kpu.go.id/pemilu/caleg/dpd/11.json";
        // tps_id, ambil dari url terakhir 1112082012001
        $tpsid = explode("/", $url);
        $tps_id = $tpsid[count($tpsid) - 1]; //hapus .json
        $tps_id = preg_replace('/\D/', '', $tps_id);
        $provinsi = substr($tps_id, 0, 2);
        $kota = substr($tps_id, 0, 4);
        $kecamatan = substr($tps_id, 0, 6);
        $kelurahan = substr($tps_id, 0, 10);
        echo $provinsi . "|" . $kota . "|" . $kecamatan . "|" . $kelurahan . "|" . $tps_id . "\n";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        $detail = $json['caleg'] ?? [];
        foreach ($detail as $partai_id => $calegs) {
            foreach ($calegs as $caleg_id => $suara) {
                try {
                    // jika $caleg_id null maka skip
                    if ($caleg_id == null || $caleg_id == "" || $caleg_id == "null") {
                        continue;
                    }
                    $TpsPdprDetail = TpsPdprDetail::where('tps_id', $tps_id)->where('partai_id', $partai_id)->where('caleg_id', $caleg_id)->first();
                    echo $tps_id . "|" . $partai_id . "|" . $caleg_id . "|" . $suara . "\n";
                    if (!$TpsPdprDetail) {
                        $TpsPdprDetail = new TpsPdprDetail();
                    }
                    $TpsPdprDetail->tps_id = $tps_id;
                    $TpsPdprDetail->partai_id = $partai_id;
                    $TpsPdprDetail->caleg_id = $caleg_id;
                    $TpsPdprDetail->suara = $suara;
                    $TpsPdprDetail->save();
                } catch (\Throwable $th) {
                    //throw $th;
                    $this->error($th->getMessage()." ".$th->getLine());
                }
            }
        }

        // images 
        $images = $json['images'] ?? [];
        foreach ($images as $key => $value) {
            try {
                // jika value null maka skip
                if ($value == "null"  || $value == null) {
                    continue;
                }
                // ambil 1105072002002-20240214-204258--85cf368b-902f-48e8-92c9-5006e7110210.jpg dari url
                $x = explode("/", $value);
                $x = end($x);
                //   Nama Provinsi
                $nama_provinsi = Provinsi::where('kode', $provinsi)->first()->nama ?? $provinsi;
                // KOta
                $nama_kota = Kota::where('kode', $kota)->first()->nama ?? $kota;
                // kecamatan
                $nama_kecamatan = Kecamatan::where('kode', $kecamatan)->first()->nama ?? $kecamatan;
                // kelurahan
                $nama_kelurahan = Kelurahan::where('kode', $kelurahan)->first()->nama ?? $kelurahan;
                // tps ambil 3 digit terakhir
                $notps = 'TPS ' . substr($tps_id, -3);
                $path = "public/images/DPR RI/$nama_provinsi/$nama_kota/$nama_kecamatan/$nama_kelurahan/" . $notps;
                $file = file_get_contents($value);
                // cek path jika tidak ada maka buat
                if (!file_exists(storage_path("app/" . $path))) {
                    mkdir(storage_path("app/" . $path), 0777, true);
                }

                file_put_contents(storage_path("app/" . $path . "/" . $x), $file);
                // save to database
                $c1 = TpsPdprImage::where('tps_id', $tps_id)->where('image', $value)->first();
                if (!$c1) {
                    $c1 = new TpsPdprImage();
                }
                $c1->tps_id = $tps_id;
                $c1->image = $value;
                $c1->local_image = $path . "/" . $x;
                $c1->save();
            } catch (\Throwable $th) {
                //throw $th;
                $this->error($th->getMessage());
            }
        }
    }
}
