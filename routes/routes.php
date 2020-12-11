<?php
include_once('controller/controller.php');

use Andr3a\Crossroad;

$routes = New Crossroad();


/**
 * VISTAS
 */
$routes->add('/Predicacion/',function() {
  $controller = new Controller();  
  $controller->predicacion();
}, "get");

$routes->add('/Admin55210/',function() {
  $controller = new Controller();  
  $controller->administracion();
}, "get");

$routes->add('/Recorridos/',function() {
  $controller = new Controller();  
  $controller->pageRecorridos();
}, "get");

$routes->add('/Login/',function() {
  $controller = new Controller(false);  
  $controller->login();
}, "get");

$routes->add('/PlanEmergencia/',function() {
  $controller = new Controller(false);  
  $controller->planEmergencia();
}, "get");

$routes->add('/PlanEmergenciaResumen/',function() {
  $controller = new Controller();  
  $controller->planEmergenciaResumen();
}, "get");

$routes->add('/EditarHermano/([0-9]*)/',function($request) {
  $controller = new Controller();  
  $controller->editarHermano($request);
}, "get");

$routes->add('/MigrationDates/',function() {
  $controller = new Controller();  
  $controller->migrateDates();
}, "get");



/**
 * ACCIONES
 */
$routes->add('/Salir/',function() {
  $controller = new Controller();  
  echo $controller->cerrarSesion();
}, "get", "api");

$routes->add('/IniciarSesion/',function() {
  $controller = new Controller(false);  
  echo $controller->iniciarSesion();
}, "post", "api");

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

$routes->add('/GuardarUsuario/',function() {
  $controller = new Controller();  
  echo $controller->guardarUsuario();
}, "post", "api");

$routes->add('/EliminarUsuario/',function() {
  $controller = new Controller();  
  echo $controller->eliminarUsuario();
}, "post", "api");

$routes->add('/ActualizarUsuario/',function() {
  $controller = new Controller();  
  echo $controller->actualizarUsuario();
}, "post", "api");


$routes->add('/jwt/',function() {
  $controller = new Controller();  
  $controller->jwt();
}, "get", "api");

$routes->add('/verificar/',function() {
  $controller = new Controller();  
  $controller->jwt_verificar();
}, "get", "api");

$routes->add('/guardarPlanEmergencia/',function() {
  $controller = new Controller();  
  $controller->guardarPlanEmergencia();
}, "post", "api");

$routes->add('/actualizarPlanEmergencia/',function() {
  $controller = new Controller();  
  $controller->actualizarPlanEmergencia();
}, "post", "api");


if(isProduction()){
  $routes->run("/"); //PROD
}else{
  $routes->run("/predicacion"); //DEV
}