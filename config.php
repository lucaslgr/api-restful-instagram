<?php
    require 'environment.php';

    global $config;
    if (ENVIRONMENT == 'development') {
        define('BASE_URL', 'http://127.0.0.1/webservices/Project-DevestagramAPI/');
        $config['dbname'] = 'project-api-devstagram'; //Usei essa tabela para exemplo
        $config['host'] = '127.0.0.1'; //ou 'localhost'
        $config['dbuser'] = 'root';
        $config['dbpass'] = '';
        $config['jwt_secret_key'] = 'abcdef123456';
    }
    else { //Se não => ENVIRONMENT = 'production'
        define('BASE_URL', 'www.meusite.com.br');
        $config['dbname'] = 'estrutura_mvc';
        $config['host'] = '127.0.0.1';
        $config['dbuser'] = 'root';
        $config['dbpass'] = '';
        $config['jwt_secret_key'] = 'abcdef123456';
    }

    global $db;

    try {
        $db = new PDO('mysql:dbname='.$config['dbname'].';host='.$config['host'],
                        $config['dbuser'],
                        $config['dbpass']);
    } catch(PDOException $e) {
        die($e->getMessage());
        exit();
    }

?>