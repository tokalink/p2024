<?php

namespace App\Console\Commands;

use App\Models\DataDpd;
use App\Models\HasilDpd;
use App\Models\HasilDpdDetail;
use App\Models\HasilDpdImage;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DPD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dpd {--t= : Tools Function }';

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
        echo "DPD 2024 \n";
        $tools = $this->option('t');
        if($tools == 'getCaleg'){
            self::getCaleg();
        }else{
            self::dpd();
        }
        return 0;
    }

    public function getCaleg(){
        $prov = DB::table('wilayah_2022')->select('kode','nama')->whereRaw('LENGTH(kode) = 2')->get();
        foreach($prov as $pr){
            $url = "https://sirekap-obj-data.kpu.go.id/pemilu/caleg/dpd/$pr->kode.json";
            $resp = self::httpGet($url);
            echo "url: " . $url ." Jumlah: ".count($resp). "\n";
            foreach($resp as $key => $res){
                echo "Provinsi: " . $pr->nama . " - " . $res['nama'] . "\n";
                $dpd = DataDpd::where('dpd_id', $key)->first();
                if(!$dpd){
                    $dpd = new DataDpd();
                }
                $dpd->dpd_id = $key;
                $dpd->nama = $res['nama'];
                $dpd->provinsi_kode = $pr->kode;
                $dpd->nomor_urut = $res['nomor_urut'];
                $dpd->jenis_kelamin = $res['jenis_kelamin'];
                $dpd->tempat_tinggal = $res['tempat_tinggal'];
                $dpd->save();
            }
        }
    }

    public function httpGet($url)
    {
        $url = $url;
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        return $data;
    }

    public function hasil_dpd($data, $provinsi, $kota, $kecamatan, $kelurahan, $tps_kode, $nama)
    {
        try{
            $pilDPD = HasilDpd::where('kode', $tps_kode)->first();
            if (!$pilDPD) {
                $pilDPD = new HasilDpd();
            }
            $pilDPD->provinsi_kode = $provinsi;
            $pilDPD->kota_kode = $kota;
            $pilDPD->kecamatan_kode = $kecamatan;
            $pilDPD->kelurahan_kode = $kelurahan;
            $pilDPD->nama = $nama;
            $pilDPD->kode = $tps_kode;
            $administrasi = $data['administrasi'] ?? null;
            if ($administrasi) {
                $pilDPD->suara_sah = $administrasi['suara_sah'] ?? 0;
                $pilDPD->suara_tidak_sah = $administrasi['suara_tidak_sah'] ?? 0;
                $pilDPD->suara_total = $administrasi['suara_total'] ?? 0;
                $pilDPD->pemilih_dpt_j = $administrasi['pemilih_dpt_j'] ?? 0;
                $pilDPD->pemilih_dpt_l = $administrasi['pemilih_dpt_l'] ?? 0;
                $pilDPD->pemilih_dpt_p = $administrasi['pemilih_dpt_p'] ?? 0;
                $pilDPD->pengguna_dpt_j = $administrasi['pengguna_dpt_j'] ?? 0;
                $pilDPD->pengguna_dpt_l = $administrasi['pengguna_dpt_l'] ?? 0;
                $pilDPD->pengguna_dpt_p = $administrasi['pengguna_dpt_p'] ?? 0;
                $pilDPD->pengguna_dptb_j = $administrasi['pengguna_dptb_j'] ?? 0;
                $pilDPD->pengguna_dptb_l = $administrasi['pengguna_dptb_l'] ?? 0;
                $pilDPD->pengguna_dptb_p = $administrasi['pengguna_dptb_p'] ?? 0;
                $pilDPD->pengguna_total_j = $administrasi['pengguna_total_j'] ?? 0;
                $pilDPD->pengguna_total_l = $administrasi['pengguna_total_l'] ?? 0;
                $pilDPD->pengguna_total_p = $administrasi['pengguna_total_p'] ?? 0;
                $pilDPD->pengguna_non_dpt_j = $administrasi['pengguna_non_dpt_j'] ?? 0;
                $pilDPD->pengguna_non_dpt_l = $administrasi['pengguna_non_dpt_l'] ?? 0;
                $pilDPD->pengguna_non_dpt_p = $administrasi['pengguna_non_dpt_p'] ?? 0;
            }
            $pilDPD->psu = $data['psu'];
            $pilDPD->ts = $data['ts'];
            $pilDPD->status_suara = $data['status_suara'];
            $pilDPD->status_adm = $data['status_adm'];
            $pilDPD->save();
        }catch(\Exception $e){
            echo "error hasil_dpd: " . $e->getMessage() . "\n";
        }
        return;
    }

    public function hasil_dpd_detail($data, $provinsi, $kota, $kecamatan, $kelurahan, $tps_kode, $nama)
    {
        try{
            $chatrs = $data['chart'] ?? null;
            if($chatrs){
                foreach($chatrs as $key => $value){
                    if($value){
                        $dpdDetail = HasilDpdDetail::where('kode', $tps_kode)->where('dpd_id',$key)->first();
                        if (!$dpdDetail) {
                            $dpdDetail = new HasilDpdDetail();
                        }
                        $dpdDetail->provinsi_kode = $provinsi;
                        $dpdDetail->kota_kode = $kota;
                        $dpdDetail->kecamatan_kode = $kecamatan;
                        $dpdDetail->kelurahan_kode = $kelurahan;
                        $dpdDetail->nama = $nama;
                        $dpdDetail->kode = $tps_kode;
                        $dpdDetail->dpd_id = $key;
                        $dpdDetail->suara = $value;
                        $dpdDetail->save();
                    }
                }
            }
        }catch(\Exception $e){
            echo "error hasil_dpd_detail: " . $e->getMessage() . "\n";
        }
        return;
    }

    public function hasil_dpd_image($data, $tps_kode)
    {
        try{
            $images = $data['images'] ?? null;
            if($images){
                foreach($images as $key => $value){
                    if($value){
                        $dpdImage = HasilDpdImage::where('kode', $tps_kode)->where('image',$value)->first();
                        if (!$dpdImage) {
                            $dpdImage = new HasilDpdImage();
                        }
                        $dpdImage->kode = $tps_kode;
                        $dpdImage->image = $value;
                        $dpdImage->save();
                    }
                }
            }
        }catch(\Exception $e){
            echo "error hasil_dpd_image: " . $e->getMessage() . "\n";
        }
        return;
    }

    public function getData($tps, $url2, $provinsi, $kota, $kecamatan, $kelurahan)
    {
        try{
            echo "jumlah tps: " . count($tps) . "\n";
            foreach($tps as $tp){
                $url = $url2 . '/' . $tp['kode'].".json";
                $resp = self::httpGet($url);
                self::hasil_dpd($resp, $provinsi, $kota, $kecamatan, $kelurahan, $tp['kode'], $tp['nama']);
                self::hasil_dpd_detail($resp, $provinsi, $kota, $kecamatan, $kelurahan, $tp['kode'], $tp['nama']);
                self::hasil_dpd_image($resp, $tp['kode']);
                echo "url: " . $url . "\n";
            }
        }catch(\Exception $e){
            echo "error getData: " . $e->getMessage() . "\n";
        }
    }

    public function dpd(){
        try{
            $kel = Kelurahan::inRandomOrder()->select('kode','id')->limit(100)->whereNull('ls_dpd')->get();
            if(!isset($kel[0])){
                echo "Data dpd habis!";
            }
            foreach($kel as $kelurahan){
                $kode = $kelurahan->kode;
                $provinsi_kode = substr($kode, 0, 2);
                $kota_kode = substr($kode, 0, 4);
                $kecamatan_kode = substr($kode, 0, 6);
                $kelurahan_kode = substr($kode, 0, 13);
                $url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/$provinsi_kode/$kota_kode/$kecamatan_kode/$kelurahan_kode.json";
                $url2 = "https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/pdpd/$provinsi_kode/$kota_kode/$kecamatan_kode/$kelurahan_kode";
                $tps = self::httpGet($url);
                self::getData($tps, $url2, $provinsi_kode, $kota_kode, $kecamatan_kode, $kelurahan_kode);
                DB::table('kelurahans')->where('id',$kelurahan->id)->update(['ls_dpd' => Carbon::now()]);
            }
            self::dpd();
        }catch(\Exception $e){
            echo "error dpd: " . $e->getMessage() . "\n";
        }
    }
}
