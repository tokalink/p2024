<?php

namespace App\Console\Commands;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\PilpresDetail;
use App\Models\PilpresTpsImage;
use App\Models\Provinsi;
use App\Models\Tps as ModelsTps;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Tps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't';

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
        echo "TPS 2024";
        $kelurahan = Kelurahan::take(50)->whereNull('ls')->get();
        foreach ($kelurahan as $key => $value) {
            $kode = $value->kode;
            $provinsi = substr($kode, 0, 2);
            $kota = substr($kode, 0, 4);
            $kecamatan = substr($kode, 0, 6);
            $kelurahan = substr($kode, 0, 13);
            echo $provinsi . "|" . $kota . "|" . $kecamatan . "|" . $kelurahan . "\n";
            // $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/11/1105/110507/1105072002.json";
            self::tps($provinsi, $kota, $kecamatan, $kelurahan);
            $value->ls = Carbon::now();
            $value->save();
        }
        // sleep(5), Proses ulang setiap 5 detik
        sleep(10);
        self::handle();
        
    }

    // TPS
    private function tps($provinsi, $kota, $kecamatan, $kelurahan)
    {
        echo "TPS 2024\n";
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/" . $provinsi . "/" . $kota . "/" . $kecamatan . "/" . $kelurahan . ".json";
        echo $url . "\n";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $cek = ModelsTps::where('id', $value['id'])->first();
            if (!$cek) {
                $tps = new ModelsTps();
                $tps->id = $value['id'];
                $tps->nama = $value['nama'];
                $tps->kode = $value['kode'];
                $tps->tingkat = $value['tingkat'];
                $tps->save();
            }
            self::detail($provinsi, $kota, $kecamatan, $kelurahan, $value['kode']);
        }
    }

    // detail
    private function detail($provinsi, $kota, $kecamatan, $kelurahan, $tps)
    {
        echo "Detail 2024 $tps\n";
        $url = "https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp/" . $provinsi . "/" . $kota . "/" . $kecamatan . "/" . $kelurahan . "/" . $tps . ".json";
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        echo "Data: " . $data . "\n";
        $charts = $json['chart'] ?? [];
        echo "Chart: " . json_encode($charts) . "\n";
        foreach ($charts as $key => $value) {
            $cek = PilpresDetail::where('tps_id', $tps)->where('pilpres_id', $key)->first();
            // jika key null maka skip
            if ($key == "null") {
                continue;
            }
            if ($cek) {
                $cek->suara = $value;
                $cek->save();
                continue;
            }
            $detail = new PilpresDetail();
            $detail->tps_id = $tps;
            $detail->pilpres_id = $key;
            $detail->suara = $value;
            $detail->save();
        }
        // images download
        $images = $json['images'] ?? [];
        echo "Images: " . json_encode($images) . "\n";
        // "images": [
        //     "https://sirekap-obj-formc.kpu.go.id/094b/pemilu/ppwp/11/05/07/20/02/1105072002002-20240214-203024--c023eb73-202b-4a05-b08e-9f28d1b48b0b.jpg",
        //     "https://sirekap-obj-formc.kpu.go.id/094b/pemilu/ppwp/11/05/07/20/02/1105072002002-20240214-203932--c73474ac-cdb9-4d2f-8239-16106bc03078.jpg",
        //     "https://sirekap-obj-formc.kpu.go.id/094b/pemilu/ppwp/11/05/07/20/02/1105072002002-20240214-204258--85cf368b-902f-48e8-92c9-5006e7110210.jpg"
        // ]
        // save images to storage
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
                $notps = 'TPS '.substr($tps, -3);
                $path = "public/images/PILPRES/$nama_provinsi/$nama_kota/$nama_kecamatan/$nama_kelurahan/" . $notps;
                $file = file_get_contents($value);
                // cek path jika tidak ada maka buat
                if (!file_exists(storage_path("app/" . $path))) {
                    mkdir(storage_path("app/" . $path), 0777, true);
                }

                file_put_contents(storage_path("app/" . $path . "/" . $x), $file);
                // save to database
                $c1 = PilpresTpsImage::where('tps_id', $tps)->where('image', $value)->first();
                if (!$c1) {
                    $c1 = new PilpresTpsImage();           
                }
                $c1->tps_id = $tps;
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
