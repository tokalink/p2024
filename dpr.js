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

async function main(){
    let url = "https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/pdpr/dapil_dpr.json";
    let resp = await Get(url);
    for(let dapil of resp){
        let url1 = `https://sirekap-obj-data.kpu.go.id/pemilu/hhcd/pdpr/${dapil.kode}.json`;
        let resp1 = await Get(url1);
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
        let cek = await kueri(`select * from hasil_pdprs where kode = '${dapil.kode}'`);
        if(cek.rows.length > 0){
            await updateDB('hasil_pdprs', dataInsert, {kode: dapil.kode});
            console.log('updated DPR RI Kode dapil: ' + dapil.kode);
        }else{
            await insertDB('hasil_pdprs', dataInsert);
            console.log('inserted DPR RI Kode dapil: ' + dapil.kode);
        }
    }
    return;
}

main();