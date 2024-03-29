require('dotenv').config();
const { Client } = require('pg');
const fs = require('fs');
const axios = require('axios');
const path = require('path');
const client = new Client({
  host: '127.0.0.1',
  user: process.env.DB_USERNAME,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_DATABASE,
  charset : 'utf8'
});

client.connect();


//isert model
const insertDB = (table, data) => {
const columns = Object.keys(data).join(', ');
const values = Object.values(data);
const placeholders = values.map((_, i) => `$${i + 1}`).join(', ');
const query = `INSERT INTO ${table} (${columns}) VALUES (${placeholders}) RETURNING *`;
return new Promise((resolve, reject) => {
    client.query(query, values, (error, results) => {
    if (error) {
        return reject(error);
    }
    resolve(results.rows[0]);
    });
});
};

const updateDB = (table, data, where) => {
    const setColumns = Object.keys(data).map((key, i) => `${key}=$${i + 1}`).join(', ');
    const whereColumns = Object.keys(where).map((key, i) => `${key}=$${i + 1 + Object.keys(data).length}`).join(' AND ');
    const query = `UPDATE ${table} SET ${setColumns} WHERE ${whereColumns}`;
    const values = [...Object.values(data), ...Object.values(where)];
    return new Promise((resolve, reject) => {
      client.query(query, values, (error, results) => {
        if (error) {
          return reject(error);
        }
        resolve(results);
      });
    });
};


const kueri = (sql) => {
    return new Promise((resolve, reject) => {
        client.query(sql, (error, results) => {
        if (error) {
          return reject(error);
        }
        resolve(results);
      });
    });
  };


//make function create folder if not exist 
const createFolder = (folder) => {
    if (!fs .existsSync(folder)) {
        fs.mkdirSync(folder, { recursive: true });
    }
}

//fuction cek file exist
const fileExist = (path) => {
    return fs.existsSync(path);
}

//function download image with axios
const downloadImage = (url, folder, filename) => {
  const path = `${folder}/${filename}`;
  const writer = fs.createWriteStream(path);
  return axios({
    method: 'get',
    url: url,
    responseType: 'stream',
  }).then(response => {
    response.data.pipe(writer);
    return new Promise((resolve, reject) => {
      writer.on('finish', resolve);
      writer.on('error', reject);
    });
  });
}
  

  module.exports={
    insertDB,
    kueri,
    updateDB,
    createFolder,
    downloadImage,
    fileExist
}