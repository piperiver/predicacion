<?php

include_once('vendor/autoload.php');
include_once('api.php');

use Andr3a\Crossroad;

$routes = New Crossroad();

$routes->add('/Telefonos/',function() {
  $api = new Api();  
  echo json_encode($api->getTelefonos()); 
}, "get");

$routes->run("/predicacion/index.php");
// $routes->run("/");