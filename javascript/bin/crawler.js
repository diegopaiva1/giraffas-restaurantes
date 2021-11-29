/**
 * @author Diego Paiva
 * @date   29/11/21
 *
 * Este script obtém todos os restaurantes da rede Giraffas, através
 * da API da empresa, e os insere em uma base dados própria MySQL.
 */

const axios = require('axios');
const mysql = require('mysql2/promise');

require('dotenv').config();

function main() {
  axios({
    method: 'post',
    url: 'https://www.giraffas.com.br/wp-json/v1/listar-restaurantes',
    data: {
      minLat: -90,
      maxLat: 90,
      minLng: -180,
      maxLng: 180
    }
  }).then(async (response) => {
    const conn = await mysql.createConnection({
      host: process.env.DB_HOST,
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_NAME,
      port: 3306
    });

    conn.query('\
      CREATE TABLE IF NOT EXISTS `restaurantes` (\
        `id` INT(11) NOT NULL AUTO_INCREMENT,\
        `nome` VARCHAR(255) NOT NULL,\
        `cep` VARCHAR(255) NOT NULL,\
        `cidade` VARCHAR(255) NOT NULL,\
        `logradouro` VARCHAR(255) NOT NULL,\
        `estado` VARCHAR(255) NOT NULL,\
        `telefone` VARCHAR(255) NOT NULL,\
        `latitude` FLOAT(10, 7) NOT NULL,\
        `longitude` FLOAT(10, 7) NOT NULL,\
        `regiao` VARCHAR(255) NOT NULL,\
        PRIMARY KEY (id)\
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_520_ci;\
    ');

    response.data.forEach(async (restaurant, id) => {
      console.log(`Inserindo restaurante ${id + 1} de ${response.data.length}\r`);

      const sql = '\
        INSERT INTO `restaurantes` (nome, cep, cidade, logradouro, estado, telefone, latitude, longitude, regiao)\
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)\
      ';

      const values = [
        restaurant.restaurante,
        restaurant.cep,
        restaurant.cidade,
        restaurant.end,
        restaurant.estado,
        restaurant.fone,
        restaurant.latitude,
        restaurant.longitude,
        restaurant.regiao
      ];

      await conn.query(sql, values);
    });

    await conn.end();

    console.log('\n... Feito!');
  }).catch(err => {
    console.error(err);
  });
}

main();
