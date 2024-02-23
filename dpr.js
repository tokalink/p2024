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

const hasil_pdprs_off = async (resp) =>{
    let url1 = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcd/pdpr/${dapil.kode}.json`;
    let resp1 = await Get(url1);
    //insert in hasil_pdprs
    let dataInsert = {};
    dataInsert.ts = resp1.ts;
    if(resp1.chart){
        let ch = resp1.chart;
        dataInsert.chart1 = ch[1];
        dataInsert.chart2 = ch[2];
        dataInsert.chart3 = ch[3];
        dataInsert.chart4 = ch[4];
        dataInsert.chart5 = ch[5];
        dataInsert.chart6 = ch[6];
        dataInsert.chart7 = ch[7];
        dataInsert.chart8 = ch[8];
        dataInsert.chart9 = ch[9];
        dataInsert.chart10 = ch[10];
        dataInsert.chart11 = ch[11];
        dataInsert.chart12 = ch[12];
        dataInsert.chart13 = ch[13];
        dataInsert.chart14 = ch[14];
        dataInsert.chart15 = ch[15];
        dataInsert.chart16 = ch[16];
        dataInsert.chart17 = ch[17];
        dataInsert.chart24 = ch[24];
        dataInsert.persen = ch.persen;
    }   
    if(resp1.progres){
        dataInsert.progres_total = resp1.progres.total;
        dataInsert.progres = resp1.progres.progres;
    }
    dataInsert.kode = dapil.kode;
    dataInsert.created_at = new Date();
    // let cek = await kueri(`select * from hasil_pdprs where kode = '${dapil.kode}'`);
    // if(cek.rows.length > 0){
    //     await updateDB('hasil_pdprs', dataInsert, {kode: dapil.kode});
    //     console.log('updated DPR RI Kode dapil: ' + dapil.kode);
    // }else{
    //     await insertDB('hasil_pdprs', dataInsert);
    //     console.log('inserted DPR RI Kode dapil: ' + dapil.kode);
    // }

    //insert in hasil_pdpr_tables
    let tables = resp1.table ?? null;
    if(tables){
        for(let [key, value] of Object.entries(tables)){
            console.log('key : '+key);
            console.log(value);
        }
    }
    // let dataTable = {};
    // dataTable.ts = resp1.ts;
}

const hasil_pdprs = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_nama, tps_kode) =>{
    let msg = 'hasil_pdprs null';
    let hasil_pdprs = {};
    hasil_pdprs.provinsi_kode = provinsi_kode;
    hasil_pdprs.kota_kode = kota_kode;
    hasil_pdprs.kecamatan_kode = kecamatan_kode;
    hasil_pdprs.kelurahan_kode = kelurahan_kode;
    hasil_pdprs.nama = tps_nama;
    hasil_pdprs.kode = tps_kode;
    let adm = resp.administrasi ?? null;
    if(adm){
        hasil_pdprs.suara_sah = adm.suara_sah ?? 0;
        hasil_pdprs.suara_total = adm.suara_total ?? 0;
        hasil_pdprs.pemilih_dpt_j = adm.pemilih_dpt_j ?? 0;
        hasil_pdprs.pemilih_dpt_l = adm.pemilih_dpt_l ?? 0;
        hasil_pdprs.pemilih_dpt_p = adm.pemilih_dpt_p ?? 0;
        hasil_pdprs.pengguna_dpt_j = adm.pengguna_dpt_j ?? 0;
        hasil_pdprs.pengguna_dpt_l = adm.pengguna_dpt_l ?? 0;
        hasil_pdprs.pengguna_dpt_p = adm.pengguna_dpt_p ?? 0;
        hasil_pdprs.pengguna_dptb_j = adm.pengguna_dptb_j ?? 0;
        hasil_pdprs.pengguna_dptb_l = adm.pengguna_dptb_l ?? 0;
        hasil_pdprs.pengguna_dptb_p = adm.pengguna_dptb_p ?? 0;
        hasil_pdprs.suara_tidak_sah = adm.suara_tidak_sah ?? 0;
        hasil_pdprs.pengguna_total_j = adm.pengguna_total_j ?? 0;
        hasil_pdprs.pengguna_total_l = adm.pengguna_total_l ?? 0;
        hasil_pdprs.pengguna_total_p = adm.pengguna_total_p ?? 0;
        hasil_pdprs.pengguna_non_dpt_j = adm.pengguna_non_dpt_j ?? 0;
        hasil_pdprs.pengguna_non_dpt_l = adm.pengguna_non_dpt_l ?? 0;
        hasil_pdprs.pengguna_non_dpt_p = adm.pengguna_non_dpt_p ?? 0;
    }
    hasil_pdprs.psu = resp.psu ?? null;
    hasil_pdprs.ts = resp.ts ?? null;
    hasil_pdprs.status_suara = resp.status_suara ?? null;
    hasil_pdprs.status_adm = resp.status_adm ?? null;
    hasil_pdprs.kode_dapil = resp.kode_dapil ?? null;

    let cek = await kueri(`SELECT * FROM "hasil_pdprs" WHERE "kode" = '${tps_kode}'`);
    if(cek.rows.length > 0){
        msg = 'update hasil_pdprs';
        hasil_pdprs.updated_at = new Date();
        await updateDB('hasil_pdprs', hasil_pdprs, {kode: tps_kode});
    }else{
        msg = 'insert hasil_pdprs';
        hasil_pdprs.created_at = new Date();
        await insertDB('hasil_pdprs', hasil_pdprs);
    }  
    console.log(msg);
}

const hasil_pdpr_details = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_nama, tps_kode) =>{
    let msg = 'hasil_pdpr_details null';
    let caleg = resp.caleg ?? null;
    if(caleg){
        let dataDetail = {};
        dataDetail.provinsi_kode = provinsi_kode;
        dataDetail.kota_kode = kota_kode;
        dataDetail.kecamatan_kode = kecamatan_kode;
        dataDetail.kelurahan_kode = kelurahan_kode;
        dataDetail.kode_dapil = resp.kode_dapil ?? null;
        dataDetail.nama = tps_nama;
        dataDetail.kode = tps_kode;
        for(let [key, value] of Object.entries(caleg)){
            if(value){
                for(let [key1, value1] of Object.entries(value)){
                    if(value1){
                        dataDetail.dpr_id = key1;
                        dataDetail.partai_id = key;
                        dataDetail.suara = value1;
                    }
                }
            }
        }
        let cek = await kueri(`SELECT * FROM "hasil_pdpr_details" WHERE "kode" = '${tps_kode}'`);
        if(cek.rows.length > 0){
            msg = 'update hasil_pdpr_details';
            dataDetail.updated_at = new Date();
            await updateDB('hasil_pdpr_details', dataDetail, {kode: tps_kode});
        }else{
            msg = 'insert hasil_pdpr_details';
            dataDetail.created_at = new Date();
            await insertDB('hasil_pdpr_details', dataDetail);
        }
    }
    console.log(msg);
}

const hasil_pdpr_images = async (resp, tps_kode) =>{
    let msg = 'hasil_pdpr_images null';
    let image = resp.images ?? null;
    if(image){
        for(let value of image){
            if(value){
                let dataImage = {};
                dataImage.kode = tps_kode;
                dataImage.image = value;
                let cek = await kueri(`SELECT * FROM "hasil_pdpr_images" WHERE "kode" = '${tps_kode}' AND "image" = '${value}'`);
                if(cek.rows.length > 0){
                    msg = 'update hasil_pdpr_images';
                    dataImage.updated_at = new Date();
                    await updateDB('hasil_pdpr_images', dataImage, {kode: tps_kode, image: value});
                }else{
                    msg = 'insert hasil_pdpr_images';
                    dataImage.created_at = new Date();
                    await insertDB('hasil_pdpr_images', dataImage);
                }
            }
        }
    }
    console.log(msg);
}

const getData = async (tps, url, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, kel_nama, kel_kode) =>{
    console.log(`Jumlah tps di ${kel_nama} : ${tps.length}`);
    for(let tp of tps){
        let resp = await Get(`${url}/${tp.kode}.json`);
        // let resp = await Get(`${url}`);
        // if(resp.chart){
        //     console.log(resp);
        //     break;
        // }
        await hasil_pdprs(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode);
        await hasil_pdpr_details(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode);
        await hasil_pdpr_images(resp, tp.kode);
    }
    // update kelurahan
    await kueri(`UPDATE kelurahans SET ls_pdpr = now() WHERE kode = '${kel_kode}'`);
    main(1);
}

async function main(run){
    let kel = await kueri("SELECT kode,nama FROM kelurahans WHERE ls_dpd IS NULL ORDER BY RANDOM() LIMIT " + run);
    for(let ke of kel.rows){
        let kode = ke.kode;
        let provinsi_kode = kode.substring(0,2);
        let kota_kode = kode.substring(0,4);
        let kecamatan_kode = kode.substring(0,6);
        let kelurahan_kode = kode.substring(0,13);
    
        let url_tps = `https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}.json`;
        let url = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/pdpr/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}`;
        let tps = await Get(url_tps);
        getData(tps, url, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, ke.nama, ke.kode);
        // break;
    }
}
main(run);