<?php
include_once('controller/controller.php');

use Andr3a\Crossroad;

$routes = New Crossroad();


$routes->add('/Predicacion/',function() {
  $controller = new Controller();  
  $controller->predicacion();
}, "get");

$routes->add('/Admin55210/',function() {
  $controller = new Controller();  
  $controller->administracion();
}, "get");

$routes->add('/GuardarTelefonos/',function() {
  $controller = new Controller();  
  echo $controller->guardarTelefonos();
}, "post", "api");

$routes->add('/ObtenerTelefono/',function() {
  $controller = new Controller();  
  echo $controller->obtenerTelefono();
}, "post", "api");

$routes->add('/CambiarEstado/([0-9]*)/',function($request) {
  $controller = new Controller();  
  echo $controller->updateEstado($request);
}, "post", "api");

$routes->add('/GuardarTelefonos/([0-9]*)/',function($request) {
  var_dump($request, $_POST);
  die("termine");
}, "post", "api");

$routes->run("/predicacion/index.php");
// $routes->run("/");