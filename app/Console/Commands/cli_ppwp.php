<?php

namespace App\Console\Commands;

use App\Models\HasilPilpres;
use App\Models\HasilPilpresDetail;
use App\Models\HasilPilpresImage;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class cli_ppwp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // optional -p (kode_provinsi), -d download image to local
    protected $signature = 'ppwp {--p= : Kode Provinsi} {--d : Download Image}';

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
        echo "PILPRES 2024";
        $op_provinsi = $this->option('p');
        if($op_provinsi){
            $kelurahans = Kelurahan::where('kode', 'like', $op_provinsi . '%')->get();
        }else{
            // random 100 kelurahan
            $kelurahans = Kelurahan::inRandomOrder()->limit(10)->whereNull('ls_ppwp')->get();
        }
        
        foreach ($kelurahans as $key => $value) {
            $kode = $value->kode;
            $provinsi_kode = substr($kode, 0, 2);
            $kota_kode = substr($kode, 0, 4);
            $kecamatan_kode = substr($kode, 0, 6);
            $kelurahan_kode = substr($kode, 0, 13);
            echo $provinsi_kode . "|" . $kota_kode . "|" . $kecamatan_kode . "|" . $kelurahan_kode . "\n";
            self::ppwp_per_kelurahan($provinsi_kode, $kota_kode, $kecamatan_kode, $kelurahan_kode);
            $value->ls_ppwp = Carbon::now();
            $value->save();
        }
        // sleep(5), Proses ulang setiap 5 detik
        // sleep(10);
        // jika opsi -p tidak ada maka ulangi
        if(!$op_provinsi)        
        self::handle();
    }

    private function ppwp_per_kelurahan($provinsi_kode, $kota_kode, $kecamatan_kode, $kelurahan_kode)
    {
        echo "TPS 2024\n";
        $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/" . $provinsi_kode . "/" . $kota_kode . "/" . $kecamatan_kode . "/" . $kelurahan_kode . ".json";
        echo $url . "\n";
        // exit;
        $data = file_get_contents($url);
        $json = json_decode($data, true);
        foreach ($json as $key => $value) {
            $HasilPilpres = HasilPilpres::where('kode', $value['kode'])->first();
            if (!$HasilPilpres) {
                $HasilPilpres = new HasilPilpres();
            }
            $HasilPilpres->id = $value['id'];
            $HasilPilpres->nama = $value['nama'];
            $HasilPilpres->kode = $value['kode'];
            $HasilPilpres->tingkat = $value['tingkat'];
            $HasilPilpres->provinsi_kode = $provinsi_kode;
            $HasilPilpres->kota_kode = $kota_kode;
            $HasilPilpres->kecamatan_kode = $kecamatan_kode;
            $HasilPilpres->kelurahan_kode = $kelurahan_kode;            
            $HasilPilpres->save();
            self::detail($HasilPilpres,$provinsi_kode, $kota_kode, $kecamatan_kode, $kelurahan_kode, $value['kode'], $value['nama']);
        }
    }

    // detail
    private function detail($HasilPilpres,$provinsi_kode, $kota_kode, $kecamatan_kode, $kelurahan_kode, $kode,$nama_tps)
    {
        echo "Detail 2024 $kode\n";
        $op_download = $this->option('d');
        $url = "https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp/" . $provinsi_kode . "/" . $kota_kode . "/" . $kecamatan_kode . "/" . $kelurahan_kode . "/" . $kode . ".json";
        echo $url . "\n";

        $data = file_get_contents($url);
        $json = json_decode($data, true);

        $administrasi = $json['administrasi'] ?? null;
        if ($administrasi) {
            $HasilPilpres->suara_sah = $administrasi['suara_sah'] ?? 0;
            $HasilPilpres->suara_tidak_sah = $administrasi['suara_tidak_sah'] ?? 0;
            $HasilPilpres->suara_total = $administrasi['suara_total'] ?? 0;
            $HasilPilpres->pemilih_dpt_j = $administrasi['pemilih_dpt_j'] ?? 0;
            $HasilPilpres->pemilih_dpt_l = $administrasi['pemilih_dpt_l'] ?? 0;
            $HasilPilpres->pemilih_dpt_p = $administrasi['pemilih_dpt_p'] ?? 0;
            $HasilPilpres->pengguna_dpt_j = $administrasi['pengguna_dpt_j'] ?? 0;
            $HasilPilpres->pengguna_dpt_l = $administrasi['pengguna_dpt_l'] ?? 0;
            $HasilPilpres->pengguna_dpt_p = $administrasi['pengguna_dpt_p'] ?? 0;
            $HasilPilpres->pengguna_dptb_j = $administrasi['pengguna_dptb_j'] ?? 0;
            $HasilPilpres->pengguna_dptb_l = $administrasi['pengguna_dptb_l'] ?? 0;
            $HasilPilpres->pengguna_dptb_p = $administrasi['pengguna_dptb_p'] ?? 0;
            $HasilPilpres->pengguna_total_j = $administrasi['pengguna_total_j'] ?? 0;
            $HasilPilpres->pengguna_total_l = $administrasi['pengguna_total_l'] ?? 0;
            $HasilPilpres->pengguna_total_p = $administrasi['pengguna_total_p'] ?? 0;
            $HasilPilpres->pengguna_non_dpt_j = $administrasi['pengguna_non_dpt_j'] ?? 0;
            $HasilPilpres->pengguna_non_dpt_l = $administrasi['pengguna_non_dpt_l'] ?? 0;
            $HasilPilpres->pengguna_non_dpt_p = $administrasi['pengguna_non_dpt_p'] ?? 0;
        }
        $HasilPilpres->psu = $json['psu'];
        $HasilPilpres->ts = $json['ts'];
        $HasilPilpres->status_suara = $json['status_suara'];
        $HasilPilpres->status_adm = $json['status_adm'];
        $HasilPilpres->save();
        echo "Data: " . $data . "\n";
        $charts = $json['chart'] ?? [];
        echo "Chart: " . json_encode($charts) . "\n";
        foreach ($charts as $key => $value) {
            // jika key null maka skip
            if ($key == "null" || $key == null) {
                // delete hasil jika null
                HasilPilpresDetail::where('kode', $kode)->where('pilpres_id', $key)->delete();
                continue;
            }

            $detail = HasilPilpresDetail::where('kode', $kode)->where('pilpres_id', $key)->first();            
            if (!$detail) {
                $detail = new HasilPilpresDetail();    
            }
            $detail->provisi_kode = $provinsi_kode;
            $detail->kota_kode = $kota_kode;
            $detail->kecamatan_kode = $kecamatan_kode;
            $detail->kelurahan_kode = $kelurahan_kode;
            $detail->kode = $kode;
            $detail->nama = $nama_tps;
            $detail->pilpres_id = $key;
            $detail->suara = $value;
            $detail->save();
        }
        // images download
        $images = $json['images'] ?? [];
        echo "Images: " . json_encode($images) . "\n";       
        // save images to storage
        $nama_provinsi = Provinsi::where('kode', $provinsi_kode)->first()->nama ?? $provinsi_kode;
        // KOta
        $nama_kota = Kota::where('kode', $kota_kode)->first()->nama ?? $kota_kode;
        // kecamatan
        $nama_kecamatan = Kecamatan::where('kode', $kecamatan_kode)->first()->nama ?? $kecamatan_kode;
        // kelurahan
        $nama_kelurahan = Kelurahan::where('kode', $kelurahan_kode)->first()->nama ?? $kelurahan_kode;
        // tps ambil 3 digit terakhir
        $notps = $nama_tps;

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
               
                $path = "public/images/PILPRES/$nama_provinsi/$nama_kota/$nama_kecamatan/$nama_kelurahan/" . $notps;
                $file = file_get_contents($value);
                // cek path jika tidak ada maka buat
                $local_image = "unsave";
                if($op_download){
                    if (!file_exists(storage_path("app/" . $path))) {
                        mkdir(storage_path("app/" . $path), 0777, true);
                    }
                    file_put_contents(storage_path("app/" . $path . "/" . $x), $file);
                    $local_image = $path . "/" . $x;
                }
                // if (!file_exists(storage_path("app/" . $path))) {
                //     mkdir(storage_path("app/" . $path), 0777, true);
                // }

                // file_put_contents(storage_path("app/" . $path . "/" . $x), $file); // comment this line
                // save to database
                $c1 = HasilPilpresImage::where('kode', $kode)->where('image', $value)->first();
                if (!$c1) {
                    $c1 = new HasilPilpresImage();
                }
                $c1->kode = $kode;
                $c1->image = $value;
                $c1->local_image = $local_image;
                $c1->save();
            } catch (\Throwable $th) {
                //throw $th;
                $this->error($th->getMessage());
            }
        }
    }
}
