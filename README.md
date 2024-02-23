

# RUN DPD
Jika menggunakan pm2 
untuk merubah multi running silahkan edit ecosystem.config.js
pm2 start ecosystem.config.js --only=DPD_SCRAPPER 

Atau dengan command node
parameter -r adalah jumlah multi running yang dijalankan
node dpd -r 100


# RUN DPR RI
parameter -r adalah jumlah multi running yang dijalankan
node dpr -r 100