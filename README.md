# Extração de restaurantes da rede Giraffas

Este repositório contém dois *scripts* (um em **PHP** e outro em **JavaScript/Node**) para consumir a API do Giraffas e inserir os restaurantes em um banco de dados MySQL próprio. 

Cada restaurante conta com os seguintes dados:

* `nome`
* `cep`
* `cidade`
* `logradouro`
* `estado`
* `telefone`
* `latitude`
* `longitude`
* `regiao`

**Obs**: os dois *scripts* realizam exatamente a mesma ação.

## Pré-requisitos

*  PHP >= 5.0
*  [Composer](https://getcomposer.org/)
*  [NodeJS/NPM](https://nodejs.org/en/)

## Configuração do banco de dados

Como os dados serão salvos no banco de dados local da máquina em que o *script* for executado, é necessário informar alguns parâmetros, a saber:

* `DB_HOST`: endereço da máquina, tipicamente `localhost`.
* `DB_NAME`: nome do banco de dados em que será criada a tabela dentro do MySQL.
* `DB_USER`: usuário MySQL.
* `DB_PASSWORD`: senha do usuário MySQL.
  
Estas configurações devem ser carregadas em um arquivo `.env` na raíz do projeto. Renomeie o arquivo `.env.example` para `.env` e preencha as informações corretamente, de acordo com seu ambiente de execução.

**Obs**: os *scripts* irão criar uma tabela `restaurantes` (caso não exista) na base de dados informada.

## Executando o *script* PHP

Antes de executar o *script*, instale a(s) dependência(s) do projeto:

```
cd php
composer install
```

Execute o *script* a partir da **raíz do projeto**, conforme segue:

```
php php/bin/crawler.php
```

A saída deve ser algo semelhante a:


```
Inserindo restaurante 1 de 394
Inserindo restaurante 2 de 394
...
Inserindo restaurante 393 de 394
Inserindo restaurante 394 de 394

... Feito!
```

## Executando o *script* NodeJS

O processo é semelhante ao do *script* PHP. Instale a(s) dependências do projeto:

```
cd javascript
npm install
```

Execute o *script* a partir da **raíz do projeto**, conforme segue:

```
node javascript/bin/crawler.js
```

A saída deve ser semelhante ao do *script* PHP.