const { insertDB, kueri, updateDB } = require('./db');
require('events').EventEmitter.defaultMaxListeners = 0
const EventEmitter = require('events');
const axios = require('axios');
const args = require('minimist')(process.argv.slice(2));
var run = args['r'] || 10;
var myAntrian = [];

const sleep = (ms) => {
  return new Promise(resolve => setTimeout(resolve, ms));
}

const Get = async (url) => {
    const axios = require('axios');
    const response = await axios.get(url);
    return response.data;
}


const hasil_dpd = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_kode, tps_nama) => {
    let msg = 'hasil_dpd null';
    let dataInsert = {};
    dataInsert.provinsi_kode = provinsi_kode;
    dataInsert.kota_kode = kota_kode;
    dataInsert.kecamatan_kode = kecamatan_kode;
    dataInsert.kelurahan_kode = kelurahan_kode;
    dataInsert.nama = tps_nama;
    dataInsert.kode = tps_kode;
    let administrasi = resp.administrasi ?? null;
    if(administrasi){
        dataInsert.suara_sah = administrasi.suara_sah ?? 0;
        dataInsert.suara_tidak_sah = administrasi.suara_tidak_sah ?? 0;
        dataInsert.suara_total = administrasi.suara_total ?? 0;
        dataInsert.pemilih_dpt_j = administrasi.pemilih_dpt_j ?? 0;
        dataInsert.pemilih_dpt_l = administrasi.pemilih_dpt_l ?? 0;
        dataInsert.pemilih_dpt_p = administrasi.pemilih_dpt_p ?? 0;
        dataInsert.pengguna_dpt_j = administrasi.pengguna_dpt_j ?? 0;
        dataInsert.pengguna_dpt_l = administrasi.pengguna_dpt_l ?? 0;
        dataInsert.pengguna_dpt_p = administrasi.pengguna_dpt_p ?? 0;
        dataInsert.pengguna_dptb_j = administrasi.pengguna_dptb_j ?? 0;
        dataInsert.pengguna_dptb_l = administrasi.pengguna_dptb_l ?? 0;
        dataInsert.pengguna_dptb_p = administrasi.pengguna_dptb_p ?? 0;
        dataInsert.pengguna_total_j = administrasi.pengguna_total_j ?? 0;
        dataInsert.pengguna_total_l = administrasi.pengguna_total_l ?? 0;
        dataInsert.pengguna_total_p = administrasi.pengguna_total_p ?? 0;
        dataInsert.pengguna_non_dpt_j = administrasi.pengguna_non_dpt_j ?? 0;
        dataInsert.pengguna_non_dpt_l = administrasi.pengguna_non_dpt_l ?? 0;
        dataInsert.pengguna_non_dpt_p = administrasi.pengguna_non_dpt_p ?? 0;
    }
    dataInsert.psu = resp.psu;
    dataInsert.ts = resp.ts;
    dataInsert.status_suara = resp.status_suara;
    dataInsert.status_adm = resp.status_adm;
    dataInsert.created_at = new Date();
    //cek if exist
    let cek = await kueri(`SELECT * FROM "hasil_dpds" WHERE "kode" = '${tps_kode}'`);
    if(cek.rows.length > 0){
        msg = 'update hasil_dpd';
        await updateDB('hasil_dpds', dataInsert, {kode: tps_kode});
    }else{
        msg = 'insert hasil_dpd';
        await insertDB('hasil_dpds', dataInsert);
    }  
    console.log(msg);  
}

const hasil_dpd_detail = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_kode, tps_nama) => {
    let msg = 'hasil_dpd_detail null';
    let chart = resp.chart ?? null;
    if(chart){
        for(let [key, value] of Object.entries(chart)){
            if(value){
                let dataInsert = {};
                dataInsert.provinsi_kode = provinsi_kode;
                dataInsert.kota_kode = kota_kode;
                dataInsert.kecamatan_kode = kecamatan_kode;
                dataInsert.kelurahan_kode = kelurahan_kode;
                dataInsert.nama = tps_nama;
                dataInsert.kode = tps_kode;
                dataInsert.dpd_id = key;
                dataInsert.suara = value;
                dataInsert.created_at = new Date();
                let cek = await kueri(`SELECT * FROM "hasil_dpd_details" WHERE "kode" = '${tps_kode}' AND "dpd_id" = '${key}'`);
                if(cek.rows.length > 0){
                    msg = 'update hasil_dpd_details';
                    await updateDB('hasil_dpd_details', dataInsert, {kode: tps_kode, dpd_id: key});
                }else{
                    msg = 'insert hasil_dpd_details';
                    await insertDB('hasil_dpd_details', dataInsert);
                }  
            }
        }
    }
    console.log(msg);
}

const hasil_dpd_image = async(resp, tps_kode) =>{
    let msg = 'hasil_dpd_image null';
    let image = resp.images ?? null;
    if(image){
        for(let [key, value] of Object.entries(image)){
            if(value){
                let dataInsert = {};
                dataInsert.kode = tps_kode;
                dataInsert.image = value;
                dataInsert.created_at = new Date();
                let cek = await kueri(`SELECT * FROM "hasil_dpd_images" WHERE "kode" = '${tps_kode}' AND "image" = '${value}'`);
                if(cek.rows.length > 0){
                    msg = 'update hasil_dpd_images';
                    await updateDB('hasil_dpd_images', dataInsert, {kode: tps_kode, image: value});
                }else{
                    msg = 'insert hasil_dpd_images';
                    await insertDB('hasil_dpd_images', dataInsert);
                }  
            }
        }
    }
    console.log(msg);
}

const getData = async (tps, url2, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, kel_kode, kel_nama) => {
    console.log(`Jumlah tps di ${kel_nama} : ${tps.length}`);
    for(let tp of tps){
        let url = `${url2}/${tp.kode}.json`;
        let resp = await Get(url);
        await hasil_dpd(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.kode, tp.nama);
        await hasil_dpd_detail(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.kode, tp.nama);
        await hasil_dpd_image(resp, tp.kode);
        //update kelurahan ls_dpd
        console.log('done update kelurahan ls_dpd kelurahan : '+kelurahan_kode+' tps : '+tp.kode);
    }
    await updateDB('kelurahans', {ls_dpd: new Date()}, {kode: kel_kode});
    myAntrian[kel_kode].emit('done');
    main(1);
}

let is_first = true;
async function main(limit = null){
    console.log('limit multi scarp : '+limit);
    if(is_first){
        await sleep(5000);
        is_first = false;
    }
    let kel = await kueri("SELECT * FROM kelurahans WHERE kode NOT LIKE '99%' AND ls_dpd IS NULL ORDER BY RANDOM() LIMIT " + limit);
    for(let em of kel.rows){
        myAntrian[em.kode] = new EventEmitter();
        myAntrian[em.kode].on('start',async ()=>{
            let kode = em.kode;
            let provinsi_kode = kode.substring(0,2);
            let kota_kode = kode.substring(0,4);
            let kecamatan_kode = kode.substring(0,6);
            let kelurahan_kode = kode.substring(0,13);
            let url = `https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}.json`;
            let url2 = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/pdpd/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}`;
            let tps = await Get(url);
            getData(tps, url2, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, em.kode, em.nama);
        });
        myAntrian[em.kode].emit('start');
    }
}
main(run);