
#nodejs

npm install

# RUN DPD
Jika menggunakan pm2 
untuk merubah multi running silahkan edit ecosystem.config.js
pm2 start ecosystem.config.js --only=DPD_SCRAPPER 

Atau dengan command node
parameter -r adalah jumlah multi running yang dijalankan
parameter -l adalah local_file jika ingin download file images ke local (y/n)
parameter -f adalah filter untuk memfilter provinsi yg ingin di scrap (y/n)

node dpd -r 100


# RUN DPR
parameter -r adalah jumlah multi running yang dijalankan
parameter -l adalah local_file jika ingin download file images ke local (y/n)
parameter -f adalah filter untuk memfilter provinsi yg ingin di scrap (y/n)

node dpr -r 100