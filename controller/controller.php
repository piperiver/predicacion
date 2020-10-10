<?php

include_once('views/views.php');

use ODB\DB\Database;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

class Controller {

    private $db;
    private $view;

    function __construct(){
        $this->initHandlerErrors();
        $this->db = Database::connect();
        $this->view = new Views();
    }

    function initHandlerErrors(){
        $run     = new Whoops\Run;
        $handler = new PrettyPageHandler;

        // Set the title of the error page:
        $handler->setPageTitle("Ups! Tenemos un problema.");

        $run->pushHandler($handler);
        if (Whoops\Util\Misc::isAjaxRequest()) {
            $run->pushHandler(new JsonResponseHandler);
        }
        $run->register();
    }

    function predicacion(){
        $this->view->render("predicacion.php", []);
    }

    function administracion(){
        $sql = "SELECT 
                    estados.nombre,
                    telefonos.telefono,
                    telefonos.id,
                    telefonos.estado,
                    telefonos.usado
                FROM telefonos 
                JOIN estados ON telefonos.estado = estados.id
                ORDER BY telefono ASC";
        
        $query = $this->db->rawQuery($sql);
        $telefonos = $query->results();
        $estados = $this->obtenerEstados()->results();
        
        $this->view->render("administracion.php", ["telefonos" => $telefonos, "estados" => $estados]);
    }

    function consultaTelefonos($query_update = true){

        $telefono = $this->db->table('telefonos')
                        ->where('usado', false)
                        ->where('estado', constantes("ACTIVO"))
                        ->orderBy("telefono", "ASC")
                        ->select()->first();
        
        if($telefono == null && $query_update){

            $this->db->table('telefonos')
                ->where('estado', constantes("ACTIVO"))
                ->update(["usado" => 0]);

            return $this->consultaTelefonos(false);
        }

        return $telefono;
    }

    /**
     * Funcion para obtener un numero de telefono de la lista
     * @return json resultado de la busqueda
     */
    function obtenerTelefono(){
        try {
            
            $telefono = $this->consultaTelefonos();            

            if($telefono == null){
                return response_json(true, "En este momento no tenemos más números de teléfonos para mostrar");
            }
            
            $update = $this->updateTelefono($telefono->id, [
                "usado" => true
            ]);
            
            if(!$update){
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
            }

            return response_json(true, "Ahora puede llamar al número que aparecerá en pantalla", $telefono);

        } catch (\Exception $ex) {
            return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
        }
    }

    /**
     * Funcion para actualizar en la tabla telefonos
     * @param integer $telefono_id identificacion del telefono
     * @param Array $update array de lo que se desea actualizar
     * @return boolean resultado de la actualizacion
     */
    function updateTelefono($telefono_id, $update, $intento = 1){
        
        $result = $this->db->table('telefonos')
                ->where('id', $telefono_id)
                ->update($update);

        if(!$result){
            if($intento >= constantes("INTENTOS_UPDATE")){
                return false;
            }

            return $this->updateTelefono($telefono_id, $update, $intento + 1);
        }

        return $result;
    }

    /**
     * Funcion para actualizar el estado de un telefono
     * @param integer $telefono_id identificacion del telefono
     * @return json resultado de la actualizacion
     */
    function updateEstado($telefono_id){

        try {
            //code...
            if(!isset($_POST["estado"])){
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor ");
            }
            
            $nuevo_estado = $_POST["estado"];
    
            $update = $this->updateTelefono($telefono_id, [
                "estado" => $nuevo_estado
            ]);
            
            if(!$update){
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
            }
            
            $infoEstado = $this->obtenerEstados($nuevo_estado);
            $infoEstado = $infoEstado->first();
            return response_json(true, "El número de teléfono, quedo marcado como '".$infoEstado->nombre."'", $infoEstado);

        } catch (\Exception $ex) {
            return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
        }

    }

    function obtenerEstados($codigo_estado = false){

        $estados = $this->db->table('estados');
        
        if($codigo_estado != false){
            $estados->where('id', $codigo_estado);
        }

        return $estados->select();
    }

    function telefonosIn($telefonos){
        try {
            
            $sql = "SELECT 
                        telefonos.telefono
                    FROM telefonos 
                    WHERE telefono in ($telefonos)
                    ORDER BY telefono ASC";
            
            $query = $this->db->rawQuery($sql);
            return $query->results();

        } catch (\Exception $ex) {
            return $this->messageError($ex);
        }
    }

    function getTelefonosEncontrados($telefonos_input){
        $telefonos_bd = $this->telefonosIn($telefonos_input);

        $telefonos_encontrados = [];
        if($telefonos_bd){
            foreach($telefonos_bd as $tel){
                $telefonos_encontrados[] = $tel->telefono;
            }
        }

        return $telefonos_encontrados;
    }

    function prepareInsert($telefonos_encontrados, $telefonos){
        $insert = [];
        $no_insert = [];
        foreach ($telefonos as $tel) {
            $tel = trim($tel);
            
            if(!is_numeric($tel)){
                $no_insert[] = "$tel: Formato inválido";
                continue;
            }

            if(in_array($tel, $telefonos_encontrados)){
                $no_insert[] = "$tel: Ya se encuentra registrado";
                continue;
            }

            $insert[] = [
                "telefono" => $tel,
                "fecha_creacion" => date("Y-m-d H:i:s"),
                "estado" => constantes("ACTIVO"),
                "usado" => 0
            ];
        }

        return [
            "insert" => $insert,
            "no_insert" => $no_insert
        ];
    }

    function insertTelefonos($array_insert){
        foreach($array_insert as $insert){
            $this->db->table('telefonos')->insert($insert);
        }
    }

    function guardarTelefonos(){
        try {
            if(!isset($_POST["telefonos"])){
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor ");
            }

            if(empty($_POST["telefonos"])){
                return response_json(false, "Debe escribir uno o más teléfonos");
            }

            $telefonos_input = $_POST["telefonos"];
            $telefonos_encontrados = $this->getTelefonosEncontrados($telefonos_input);

            $telefonos = explode(",", $telefonos_input);
            $result = $this->prepareInsert($telefonos_encontrados, $telefonos);
            
            $this->insertTelefonos($result['insert']);
            
            $title = "Se guardarón ".count($result['insert'])." nuevos teléfonos.";
            $message = '';
            if(count($result["no_insert"]) > 0){
                $message = "Algunos teléfonos no se guardarón por los siguientes motivos \n
                                ".implode("\n", $result['no_insert']);
                
            }
            
            return response_json(true, "Teléfonos guardados con exito, en breve se recargará la página", ["title" => $title, "message" => $message]);

        } catch (\Exception $ex) {
            return $this->messageError($ex);
        }
    }

    function messageError($ex){
        if(constantes("AMBIENTE") == "DEV"){
            return response_json(false, "Error: Linea: ".$ex->getLine().", Mensaje: ".$ex->getMessage());
        }
        return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
    }
}

