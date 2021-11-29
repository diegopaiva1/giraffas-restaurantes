<?php

/**
 * @author Diego Paiva
 * @date   29/11/21
 *
 * Este script obtém todos os restaurantes da rede Giraffas, através
 * da API da empresa, e os insere em uma base dados própria MySQL.
 */

require_once __DIR__ . "/../vendor/autoload.php";

function main()
{
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../..");
  $dotenv->load();

  try {
    $conn = new PDO(
      "mysql:host=" . $_ENV["DB_HOST"] . ";dbname=" . $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully to " . $_ENV["DB_HOST"] . "@" . $_ENV["DB_NAME"] . PHP_EOL;

    // set support to utf8
    $conn->exec("SET NAMES UTF8");

    $conn->query("
      CREATE TABLE IF NOT EXISTS `restaurantes` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `nome` VARCHAR(255) NOT NULL,
        `cep` VARCHAR(255) NOT NULL,
        `cidade` VARCHAR(255) NOT NULL,
        `logradouro` VARCHAR(255) NOT NULL,
        `estado` VARCHAR(255) NOT NULL,
        `telefone` VARCHAR(255) NOT NULL,
        `latitude` FLOAT(10, 7) NOT NULL,
        `longitude` FLOAT(10, 7) NOT NULL,
        `regiao` VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_520_ci;
    ");

    $response = postRequest(
      "https://www.giraffas.com.br/wp-json/v1/listar-restaurantes",
      "maxLat=90&maxLng=180&minLat=-90&minLng=-180"
    );

    $response = json_decode($response);

    foreach ($response as $id => $item) {
      echo "Inserindo restaurante " . ($id + 1) . " de " . count($response) . PHP_EOL;

      $conn->query("
        INSERT INTO `restaurantes` (nome, cep, cidade, logradouro, estado, telefone, latitude, longitude, regiao)
        VALUES (
          '$item->restaurante',
          '$item->cep',
          '$item->cidade',
          '$item->end',
          '$item->estado',
          '$item->fone',
          '$item->latitude',
          '$item->longitude',
          '$item->regiao'
        )
      ");
    }

    echo "\n... Feito!\n";
  } catch(PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage() . PHP_EOL;
  }
}

function postRequest($url, $params)
{
  $options = [
    "http" => [
      "header"  => "Content-type: application/x-www-form-urlencoded; charset=utf-8",
      "method"  => "POST",
      "content" => $params
    ]
  ];

  return file_get_contents($url, false, stream_context_create($options));
}

main();
