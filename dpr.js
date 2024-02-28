const { insertDB, kueri, updateDB, createFolder, downloadImage, fileExist } = require('./db');
require('events').EventEmitter.defaultMaxListeners = 0
const EventEmitter = require('events');
const axios = require('axios');
const args = require('minimist')(process.argv.slice(2));
const readline = require('readline');
var run = args['r'] || 10;
var local_image = args['l'] || 'n';
var filter_prov = args['f'] || 'n';
var myAntrian = [];

const sleep = (ms) => {
  return new Promise(resolve => setTimeout(resolve, ms));
}

const Get = async (url) => {
    const axios = require('axios');
    const response = await axios.get(url);
    return response.data;
}

const hasil_pdprs = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_nama, tps_kode, tingkat) =>{
    let msg = 'hasil_pdprs null';
    let hasil_pdprs = {};
    hasil_pdprs.tingkat = tingkat;
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
    // console.log(msg);
}

const hasil_pdpr_details = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_nama, tps_kode, tingkat) =>{
    let msg = 'hasil_pdpr_details null';
    let caleg = resp.caleg ?? null;
    if(caleg){
        let dataDetail = {};
        dataDetail.tingkat = tingkat;
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
    // console.log(msg);
}

const hasil_pdpr_images = async (resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tps_nama, tps_kode, tingkat) =>{
    let msg = 'hasil_pdpr_images null';
    let image = resp.images ?? null;
    if(image){
        for(let value of image){
            if(value){
                let dataImage = {};
                dataImage.tingkat = tingkat;
                dataImage.kode = tps_kode;
                dataImage.image = value;
                dataImage.kode_dapil = resp.kode_dapil ?? null;

                //if use local image
                if(local_image == 'y'){
                    let sub_folder = tingkat == 2 ? 'DPR RI' : tingkat == 3 ? 'DPRD Provinsi' : 'DPRD Kabupaten';
                    let prov_nama = await kueri(`SELECT nama FROM "provinsis" WHERE "kode" = '${provinsi_kode}'`);
                    let kota_nama = await kueri(`SELECT nama FROM "kotas" WHERE "kode" = '${kota_kode}'`);
                    let kec_nama = await kueri(`SELECT nama FROM "kecamatans" WHERE "kode" = '${kecamatan_kode}'`);
                    let kel_nama = await kueri(`SELECT nama FROM "kelurahans" WHERE "kode" = '${kelurahan_kode}'`);
                    prov_nama = prov_nama.rows[0].nama.replace(/\//g, '-');
                    kota_nama = kota_nama.rows[0].nama.replace(/\//g, '-');
                    kec_nama = kec_nama.rows[0].nama.replace(/\//g, '-');
                    kel_nama = kel_nama.rows[0].nama.replace(/\//g, '-');
                    let folder = `public/images/${sub_folder}/${prov_nama}/${kota_nama}/${kec_nama}/${kel_nama}/${tps_nama}`;
                    createFolder(folder);
                    let url = value;
                    let filename = url.split('/').pop();
                    if(!fileExist(folder+'/'+filename)){
                        await downloadImage(url, folder, filename);
                        dataImage.local_image = folder+'/'+filename;
                    }
                }

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
    // console.log(msg);
}

const ifDoneAll = async (kel_nama, kel_kode, filter) => {
    if(myAntrian[kel_kode] == 3){
        myAntrian[kel_kode] = 0;
        await kueri(`UPDATE kelurahans SET ls_pdpr = now() WHERE kode = '${kel_kode}'`);
        main(1, filter);
        console.log('done update kelurahan ls_pdpr kelurahan : '+kel_nama);
    }
}

const getData = async (tps, url, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, kel_nama, kel_kode, filter) =>{
    console.log(`Jumlah tps di ${kel_nama} : ${tps.length}`);
    for(let tp of tps){
        let resp = await Get(`${url}/${tp.kode}.json`);
        // let resp = await Get(`${url}`);
        // if(resp.chart){
        //     console.log(resp);
        //     break;
        // }
        await hasil_pdprs(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 2);
        await hasil_pdpr_details(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 2);
        await hasil_pdpr_images(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 2);
    }
    myAntrian[kel_kode] = myAntrian[kel_kode] + 1 || 1;
    ifDoneAll(kel_nama, kel_kode, filter);
}

const dataDPR_prov = async (tps, url, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, kel_nama, kel_kode, filter) =>{
    for(let tp of tps){
        let resp = await Get(`${url}/${tp.kode}.json`);
        await hasil_pdprs(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 3);
        await hasil_pdpr_details(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 3);
        await hasil_pdpr_images(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 3);
    }
    myAntrian[kel_kode] = myAntrian[kel_kode] + 1 || 1;
    ifDoneAll(kel_nama, kel_kode, filter);
}

const dataDPR_kab = async (tps, url, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, kel_nama, kel_kode, filter) =>{
    for(let tp of tps){
        let resp = await Get(`${url}/${tp.kode}.json`);
        await hasil_pdprs(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 4);
        await hasil_pdpr_details(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 4);
        await hasil_pdpr_images(resp, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, tp.nama, tp.kode, 4);
    }
    myAntrian[kel_kode] = myAntrian[kel_kode] + 1 || 1;
    ifDoneAll(kel_nama, kel_kode, filter);
}
 
async function main(run, filter = null){
    let kel = null;
    if(filter){
        kel = await kueri("SELECT kode,nama FROM kelurahans WHERE kode LIKE '"+filter+"%' AND ls_pdpr IS NULL ORDER BY RANDOM() LIMIT " + run);
    }else{
        kel = await kueri("SELECT kode,nama FROM kelurahans WHERE ls_pdpr IS NULL ORDER BY RANDOM() LIMIT " + run);
    }
    if(kel.rows.length == 0){
        console.log('no data');
        return;
    }
    for(let ke of kel.rows){
        let kode = ke.kode;
        let provinsi_kode = kode.substring(0,2);
        let kota_kode = kode.substring(0,4);
        let kecamatan_kode = kode.substring(0,6);
        let kelurahan_kode = kode.substring(0,13);
    
        let url_tps = `https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}.json`;
        let tps = await Get(url_tps);

        // DPR RI
        let url = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/pdpr/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}`;
        getData(tps, url, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, ke.nama, ke.kode, filter);

        // DPRD Provinsi
        let url_prov = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/pdprdp/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}`;
        dataDPR_prov(tps, url_prov, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, ke.nama, ke.kode, filter);

        // DPRD Kabupaten
        let url_kab = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/pdprdk/${provinsi_kode}/${kota_kode}/${kecamatan_kode}/${kelurahan_kode}`;
        dataDPR_kab(tps, url_kab, provinsi_kode, kota_kode, kecamatan_kode, kelurahan_kode, ke.nama, ke.kode, filter);
    }
}


if(filter_prov == 'y'){
const provList = async () => {
    let prov = await kueri("SELECT * FROM provinsis WHERE kode NOT LIKE '99%'");
    for(let em of prov.rows){
        console.log(em.kode+' - '+em.nama);
    }
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout
    });
        
    // Menggunakan rl.question untuk membuat pertanyaan
    rl.question('Masukan kode provinsi: ', (kode) => {
        main(run, kode);
        rl.close();
    });
}
provList();
}else{
    main(run);
}
    
    