<?php


/**
 * funcion para obtener el valor de una constante
 * @param string $key nombre de la constante
 * @return string retorna el contenido de la constante
 */
function constantes($key){
    $constantes = include 'config/constantes.php';
    return $constantes[$key];
}

function dominio($ruta){
    return constantes("DOMINIO").$ruta;
}

function ruta($ruta){
    return constantes("DOMINIO")."index.php/".$ruta;
}


/**
 * funcion para imprimir en pantalla un objeto
 * @param object $data objeto que se desea imprimir
 * @return void imprime en pantalla
 */
function imprimir($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

/**
 * Funcion para responder en formato json
 */
function response_json($status, $message, $data = false){
    header('Content-type: application/json');
    return json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
}