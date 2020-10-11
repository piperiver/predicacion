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
    // return constantes("DOMINIO")."index.php/".$ruta;
    return constantes("DOMINIO").$ruta;
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

function is_session(){
    if(!isset($_SESSION["usuario"])){
        return false;
    }
    return true;
}

function is_admin(){
    if(!is_session()){
        return false;
    }

    if(isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->admin) && $_SESSION["usuario"]->admin){
        return true;
    }

    return false;
}

function is_ajax(){
    $isAjaxRequest = false;
 
    //IF HTTP_X_REQUESTED_WITH is equal to xmlhttprequest
    if(
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0
    ){
        //Set our $isAjaxRequest to true.
        $isAjaxRequest = true;
    }

    return $isAjaxRequest;
}