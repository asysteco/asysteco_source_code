<?php

//Configuración del algoritmo de encriptación

//Debes cambiar esta cadena, debe ser larga y unica
//nadie mas debe conocerla
$clave  = $options['criptKey'];

//Metodo de encriptación
$method = 'aes-256-cbc';

if($options['autoQR'] == 1)
{
    $iv = date('YYmmdd');
}
else
{
    $iv = 2020202120202021;
}

/*
Encripta el contenido de la variable, enviada como parametro.
*/
$encriptar = function ($valor) use ($method, $clave, $iv) {
    return openssl_encrypt ($valor, $method, $clave, false, $iv);
};

/*
Desencripta el texto recibido
*/
$desencriptar = function ($valor) use ($method, $clave, $iv) {
    $encrypted_data = base64_decode($valor);
    return openssl_decrypt($valor, $method, $clave, false, $iv);
};
 

