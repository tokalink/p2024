const { insertDB, kueri, updateDB, createFolder, fileExist, downloadImage } = require('./db');



function main(){
    let folder = 'public/images/DPD/ACEH/ACEH BESAR/BLANG CUT/TPS 005';
    let url = 'https://sirekap-obj-formc.kpu.go.id/46e6/pemilu/ppwp/11/05/12/20/07/1105122007001-20240216-131817--a6c897d6-4a2c-4802-9ace-49f7efd2f79c.jpg';
    let filename = url.split('/').pop();
    createFolder(folder);
    if(!fileExist(folder+'/'+filename)){
        console.log('file not exist');
        downloadImage(url, folder, filename);

    }else{
        console.log('file exist');
    }
    console.log(fileExist(folder+'/'+filename));
    // createFolder(folder);
}

main()