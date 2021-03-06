<?php

namespace Models;

use Core\Model;

class Jwt extends Model
{
    public function __construct(){}

    //Criando um JWT com criptografia HS256 
    public function create(array $data_payload): string
    {
        global $config;

        $header = json_encode(array(
            "type" => "JWT",
            "alg" => "HS256"
        ));

        $payload = json_encode($data_payload);

        //HASH do header
        $base_header = $this->base64url_encode($header);
        $base_payload = $this->base64url_encode($payload);

        //Tipo da criptografia alg->HS256->sha256
        /**
         * O ultimo parametro como true é para ele manter letras maiúsculas e minusculas
        */
        $signature = hash_hmac("sha256",$base_header.'.'.$base_payload, $config['jwt_secret_key'], true);
        $base_signature = $this->base64url_encode($signature);

        //Montando o JWT
        $jwt = $base_header.'.'.$base_payload.'.'.$base_signature;

        return $jwt;
    }

    public function validate(string $token)
    {
        global $config;

        // Passo 1: Verificar se o TOKEN tem 3 partes: HEADER, PAYLOAD e SIGNATURE
        // Passo 2: Bater a assinatura com os dados
        
        $response = array();

        $jwt_split = explode('.', $token);
        //Conferindo passo 1
        if (count($jwt_split) == 3) {
            //Gerando a assinatura com as informações do HEADER($jwt_split[0]) e do PAYLOAD($jwt_split[1]) do token
            $signature = hash_hmac("sha256", $jwt_split[0].'.'.$jwt_split[1], $config['jwt_secret_key'], true);
            
            $base_signature = $this->base64url_encode($signature);
            //Conferindo o passo 2
            if ($base_signature == $jwt_split[2]) {
                
                //Recuperando os dados do PAYLOAD com decode
                $response = json_decode($this->base64url_decode($jwt_split[1]));
                return $response;
            }
        } else
            return false;
    }


    //https://www.php.net/manual/pt_BR/function.base64-encode.php
    //Funções para base64URL que não tem +, nem / e nem =
    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data),'+/','-_'),'=');
    }
    private function base64url_decode($data)
    {
        return base64_decode(strtr($data,'-_','+/').str_repeat('=',3-(3+strlen($data))%4));
    }
}