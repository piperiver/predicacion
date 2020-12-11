<?php
// https://buddy.works/guides/5-ways-to-deploy-php-applications
include_once 'views/views.php';

use ODB\DB\Database;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class Controller
{

    private $db;
    private $view;
    private $validate_sesion;

    public function __construct($validate_sesion = true)
    {
        date_default_timezone_set('America/Bogota');

        $this->initHandlerErrors();
        $this->db = Database::connect();
        $this->view = new Views();
        $this->validate_sesion = $validate_sesion;

        if ($this->validate_sesion) {
            $this->validar_sesion();
        }
    }

    public function initHandlerErrors()
    {
        $run = new Whoops\Run;
        $handler = new PrettyPageHandler;

        // Set the title of the error page:
        $handler->setPageTitle("Ups! Tenemos un problema.");

        $run->pushHandler($handler);
        if (Whoops\Util\Misc::isAjaxRequest()) {
            $run->pushHandler(new JsonResponseHandler);
        }
        $run->register();
    }

    public function login()
    {
        $this->validar_sesion("login");
        $this->view->render("login.php", []);
    }

    public function predicacion()
    {
        $this->validar_sesion("predicacion");
        $this->view->render("predicacion.php", []);
    }

    public function planEmergencia()
    {
        $this->view->render("formulario.php", []);
    }

    public function planEmergenciaResumen()
    {
        $hermanos = $this->db->table('hermanos')
            ->orderBy("nombres", "ASC")
            ->select()->all();

        $this->view->render("hermanos.php", ['hermanos' => $hermanos]);
    }

    public function editarHermano($hermano_id)
    {
        $hermano = $this->db->table('hermanos')
            ->where("id", $hermano_id)
            ->select()->first();

        if(!isset($hermano->id)){
            $_SESSION['message']['type'] = 'danger';
            $_SESSION['message']['message'] = 'El id proporcionado no existe';
            header("Location: " . ruta("PlanEmergenciaResumen"));
        }

        $familiares = $this->db->table('familiares')
        ->where("hermano_id", $hermano_id)
        ->select()->all();

        $_SESSION['_token_'] = hash('sha256', 'T0k3N'.time());

        $this->view->render("formularioEdit.php", ["hermano" => $hermano, "familiares" => $familiares]);
    }

    public function administracion()
    {
        $this->validar_sesion("administracion");
        $usuarios = self::getDataUsers();

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

        $this->view->render("administracion.php", ["telefonos" => $telefonos, "estados" => $estados, "usuarios" => $usuarios]);
    }

    public function pageRecorridos()
    {
        $this->validar_sesion("recorridos");

        $reboot = $this->getReboot();
        $this->view->render("recorridos.php", ["reboot" => $reboot]);
    }

    public function consultaTelefonos($query_update = true)
    {

        $telefono = $this->db->table('telefonos')
            ->where('usado', false)
            ->where('estado', constantes("ACTIVO"))
            ->orderBy("telefono", "ASC")
            ->select()->first();

        if ($telefono == null && $query_update) {

            $this->db->table('telefonos')
                ->where('estado', constantes("ACTIVO"))
                ->update(["usado" => 0]);

            $this->reboot();

            return $this->consultaTelefonos(false);
        }

        return $telefono;
    }

    /**
     * Funcion para insertar la cantidad de vueltas que llevamos
     */
    public function reboot()
    {
        $insert = [
            "fechaReset" => date("Y-m-d H:i:s"),
        ];
        $this->db->table('vueltas')->insert($insert);
    }

    /**
     * Funcion para obtener las vueltas realizadas
     */
    public function getReboot()
    {
        $reboot = $this->db->table('vueltas')
            ->orderBy("id", "ASC")
            ->select()->all();

        if (count($reboot) == 0) {
            return [];
        }

        for ($i = 0; $i < count($reboot); $i++) {
            $tempFechaInicio = explode(" ", $reboot[$i]->fechaReset);
            $tempFechaFinal = (isset($reboot[$i + 1]->fechaReset)) ? explode(" ", $reboot[$i + 1]->fechaReset) : [date("Y-m-d")];

            $fechaInicio = new DateTime($tempFechaInicio[0]);
            $fechaFinal = new DateTime($tempFechaFinal[0]);
            $diff = $fechaInicio->diff($fechaFinal);

            $reboot[$i]->dias = $diff->days;
        }

        return $reboot;
    }

    /**
     * Funcion para obtener un numero de telefono de la lista
     * @return json resultado de la busqueda
     */
    public function obtenerTelefono()
    {
        try {

            if (!$this->validateShedule()) {
                return response_json(true, "En este momento no puede predicar porque no es un horario de predicación. La aplicación solo esta activa en horarios de predicación");
            }

            $telefono = $this->consultaTelefonos();

            if ($telefono == null) {
                return response_json(true, "En este momento no tenemos más números de teléfonos para mostrar");
            }

            $update = $this->updateTelefono($telefono->id, [
                "usado" => true,
            ]);

            if (!$update) {
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
    public function updateTelefono($telefono_id, $update, $intento = 1)
    {

        $result = $this->db->table('telefonos')
            ->where('id', $telefono_id)
            ->update($update);

        if (!$result) {
            if ($intento >= constantes("INTENTOS_UPDATE")) {
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
    public function updateEstado($telefono_id)
    {

        try {
            //code...
            if (!isset($_POST["estado"])) {
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor ");
            }

            $nuevo_estado = $_POST["estado"];

            $update = $this->updateTelefono($telefono_id, [
                "estado" => $nuevo_estado,
            ]);

            if (!$update) {
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
            }

            $infoEstado = $this->obtenerEstados($nuevo_estado);
            $infoEstado = $infoEstado->first();
            return response_json(true, "El número de teléfono, quedo marcado como '" . $infoEstado->nombre . "'", $infoEstado);

        } catch (\Exception $ex) {
            return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
        }

    }

    public function obtenerEstados($codigo_estado = false)
    {

        $estados = $this->db->table('estados');

        if ($codigo_estado != false) {
            $estados->where('id', $codigo_estado);
        }

        return $estados->select();
    }

    public function telefonosIn($telefonos)
    {
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

    public function getTelefonosEncontrados($telefonos_input)
    {
        $telefonos_bd = $this->telefonosIn($telefonos_input);

        $telefonos_encontrados = [];
        if ($telefonos_bd) {
            foreach ($telefonos_bd as $tel) {
                $telefonos_encontrados[] = $tel->telefono;
            }
        }

        return $telefonos_encontrados;
    }

    public function prepareInsert($telefonos_encontrados, $telefonos)
    {
        $insert = [];
        $no_insert = [];
        foreach ($telefonos as $tel) {
            $tel = trim($tel);

            if (!is_numeric($tel)) {
                $no_insert[] = "$tel: Formato inválido";
                continue;
            }

            if (in_array($tel, $telefonos_encontrados)) {
                $no_insert[] = "$tel: Ya se encuentra registrado";
                continue;
            }

            $insert[] = [
                "telefono" => $tel,
                "fecha_creacion" => date("Y-m-d H:i:s"),
                "estado" => constantes("ACTIVO"),
                "usado" => 0,
            ];
        }

        return [
            "insert" => $insert,
            "no_insert" => $no_insert,
        ];
    }

    public function insertTelefonos($array_insert)
    {
        foreach ($array_insert as $insert) {
            $this->db->table('telefonos')->insert($insert);
        }
    }

    public function guardarTelefonos()
    {
        try {
            if (!isset($_POST["telefonos"])) {
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor ");
            }

            if (empty($_POST["telefonos"])) {
                return response_json(false, "Debe escribir uno o más teléfonos");
            }

            $telefonos_input = $_POST["telefonos"];
            $telefonos_input = str_replace(' ', '', $telefonos_input);
            $telefonos_encontrados = $this->getTelefonosEncontrados($telefonos_input);

            $telefonos = explode(",", $telefonos_input);
            $result = $this->prepareInsert($telefonos_encontrados, $telefonos);

            $this->insertTelefonos($result['insert']);

            $title = "Se guardarón " . count($result['insert']) . " nuevos teléfonos.";
            $message = '';
            if (count($result["no_insert"]) > 0) {
                $message = "Algunos teléfonos no se guardarón por los siguientes motivos \n
                                " . implode("\n", $result['no_insert']);

            }

            return response_json(true, "Teléfonos guardados con exito, en breve se recargará la página", ["title" => $title, "message" => $message]);

        } catch (\Exception $ex) {
            return $this->messageError($ex);
        }
    }

    public function messageError($ex)
    {
        if (constantes("AMBIENTE") == "DEV") {
            return response_json(false, "Error: Linea: " . $ex->getLine() . ", Mensaje: " . $ex->getMessage());
        }
        return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
    }

    /**
     * GESTION DE USUARIOS
     */

    public function guardarUsuario()
    {
        try {
            //code...
            if (!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["admin"])) {
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
            }

            $usuario = $_POST["username"];
            $usuario_bd = $this->db->table('usuarios')->where("usuario", $usuario)->select()->first();
            if ($usuario_bd != null) {
                return response_json(false, "El usuario que intenta crear ya existe, intente con otro usuario diferente a '$usuario'");
            }

            $this->db->table('usuarios')->insert([
                "usuario" => $usuario,
                "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
                "admin" => $_POST["admin"],
                "active" => true,
            ]);

            return response_json(true, "Usuario creado con exito");

        } catch (\Exception $ex) {
            return $this->messageError($ex);
        }

    }

    public function eliminarUsuario()
    {
        try {
            $element_id = $_POST["element_id"];
            $type = $_POST["type"];

            if (!isset($element_id) || $element_id == null) {
                return response_json(false, "Ocurrio un problema, por favor recargue la página e intente de nuevo");
            }

            if (!isset($type) || $type == null) {
                return response_json(false, "Ocurrio un problema, por favor recargue la página e intente de nuevo");
            }

            if ($type == 'usuario') {
                $usuario_bd = $this->db->table('usuarios')->find($element_id)->delete();
            } else if ($type == 'telefono') {
                $usuario_bd = $this->db->table('telefonos')->find($element_id)->delete();
            }else if($type == 'hermano'){
                if(!is_numeric($element_id)){
                    return response_json(false, "Ocurrio un problema con el id del usuario, por favor recargue la página e intente de nuevo");
                }

                $sql = "DELETE FROM familiares WHERE hermano_id = $element_id;";
                $sql .= "DELETE FROM hermanos WHERE id = $element_id;";
                $query = $this->db->rawQuery($sql);
            }

            return response_json(true, "Usuario eliminado exitosamente");
        } catch (\Exception $ex) {
            var_dump($this->db->LastQuery());
            return $this->messageError($ex);
        }
    }

    public function getDataUsers()
    {
        return $this->db->table('usuarios')->select();
    }

    public function actualizarUsuario()
    {
        try {
            if (!isset($_POST["user"]) || !isset($_POST["admin"]) || !isset($_POST["active"])) {
                return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
            }

            $this->db->table('usuarios')
                ->where('id', $_POST["user"])
                ->update(["admin" => $_POST["admin"], "active" => $_POST["active"]]);

            return response_json(true, "Usuario modificado con exito");

        } catch (\Exception $ex) {
            return $this->messageError($ex);
        }
    }

    public function iniciarSesion()
    {
        if (!isset($_POST["username"]) || !isset($_POST["password"])) {
            return response_json(false, "Ocurrio un problema interno, recargue la pagina por favor");
        }

        $usuario_bd = $this->db->table('usuarios')->where("usuario", $_POST["username"])->select()->first();

        if ($usuario_bd == null) {
            return response_json(false, "Usuario o contraseña incorrecta");
        }

        if (!password_verify($_POST["password"], $usuario_bd->password)) {
            return response_json(false, "Usuario o contraseña incorrecta");
        }

        if (!$usuario_bd->active) {
            return response_json(false, "El Usuario se encuentra inactivo");
        }

        $_SESSION["usuario"] = $usuario_bd;

        $url = $this->accesoUsuario($usuario_bd->admin);

        return response_json(true, "¡Bienvenido!", ["redirect" => $url]);

    }

    public function accesoUsuario($admin)
    {
        if ($admin === null) {
            return ruta("Login");
        }

        return ($admin) ? ruta("Admin55210") : ruta("Predicacion");
    }

    public function validar_sesion($vista = "")
    {
        $infoUsuario = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : false;

        //si no inicio sesion y quiere entrar al login: se deja entrar al login
        if ($infoUsuario == false && $vista == "login") {
            return true;
        }

        //Si no hay sesion y quiere acceder a una vista distinta al login: se redirecciona al login
        if ($infoUsuario == false && $vista != "login") {
            if (is_ajax()) {
                echo response_json(false, "", ["redirect_for_session" => ruta("Login")]);
                die;
            }
            header("Location: " . ruta("Login"));
        }

        if ($infoUsuario != false) {
            //Se obtiene la ruta de la vista a la que puede acceder, dependiendo si es administrador o no
            $isAdmin = (isset($infoUsuario->admin)) ? $infoUsuario->admin : null;
            $ruta = $this->accesoUsuario($isAdmin);
            //Si esta logueado y quiere acceder al login: se redirecciona a la vista inicial de acceso
            if ($vista == "login") {
                if (is_ajax()) {
                    echo response_json(false, "", ["redirect_for_session" => $ruta]);
                    die;
                }
                header("Location: $ruta");
            }

            if (!$isAdmin && in_array($vista, ["administracion", "recorridos"])) {
                if (is_ajax()) {
                    echo response_json(false, "", ["redirect_for_session" => ruta("Predicacion")]);
                    die;
                }
                header("Location: " . ruta("Predicacion"));
            }
        }

    }

    public function cerrarSesion()
    {
        unset($_SESSION["usuario"]);
        header("Location: " . ruta("Login"));
    }

    public function getStatus()
    {
        $parametro = $this->db->table('parametros')
            ->where('id', 1)
            ->select()->first();
        return $parametro->valor;
    }

    public function getConfigurationDay()
    {
        $dayWeek = date("N");
        if ($dayWeek == 2) { //Martes
            return constantes('CONFIG_MARTES');
        } else if ($dayWeek == 6 || $dayWeek == 7) { //Sabado o domingo
            return constantes('CONFIG_SABADO_DOMINGO');
        } else {
            return constantes('CONFIG_LUNES_MIERCOLES_JUEVES_VIERNES');
        }
    }

    public function validateShedule()
    {
        try {
            $horariosPermitidos = $this->getConfigurationDay();
            $fechaActual = strtotime("now");
            $permitir = false;
            foreach ($horariosPermitidos as $horario) {
                if ($fechaActual >= $horario['fechaInicio'] && $fechaActual <= $horario['fechaFin']) {
                    $permitir = true;
                }
            }

            return $permitir;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function setLog($message)
    {
        try {
            if (!is_dir('./logs')) {
                mkdir('./logs');
            }

            $fecha = date('Y-m-d H:i:s');
            error_log("\n$fecha => $message", 3, "./logs/logs.log");

        } catch (\Exception $ex) {
            $fecha = date('Y-m-d H:i:s');
            error_log("\n$fecha => Error [Linea => " . $ex->getLine() . "] - [Mensaje => " . $ex->getMessage() . "]", 3, "./logs/logs.log");
        }

    }

    public function guardarPlanEmergencia()
    {

        try {
            //code...

            $insert = $this->db->table('hermanos')->insert(
                [
                    'perfil' => $_POST['perfil'],
                    'grupo' => $_POST['grupo'],
                    'nombres' => $_POST['nombres'],
                    'apellidos' => $_POST['apellidos'],
                    'direccion' => $_POST['direccion'],
                    'telefono' => $_POST['telefono'],
                    'celular1' => $_POST['celular1'],
                    'celular2' => $_POST['celular2'],
                    'nacimiento' => date('Y-m-d', strtotime($_POST['fecha_nacimiento'])),
                    'bautismo' => (isset($_POST['fecha_bautismo']) && !empty($_POST['fecha_bautismo'])) ? date('Y-m-d', strtotime($_POST['fecha_bautismo'])) : null,
                    'correo' => $_POST['correo'],
                    'fecha_creacion' => date('Y-m-d H:m:i'),
                    'fecha_actualizacion' => date('Y-m-d H:m:i'),
                ]
            );

            $hermano_id = $insert->lastInsertedId();

            for ($i = 0; $i < count($_POST['nombres_familiar']); $i++) {

                $insertFamiliar = $this->db->table('familiares')->insert(
                    [
                        'hermano_id' => $hermano_id,
                        'nombres' => $_POST['nombres_familiar'][$i],
                        'apellidos' => $_POST['apellidos_familiar'][$i],
                        'parentesco' => $_POST['parentesco_familiar'][$i],
                        'ciudad' => $_POST['ciudad_familiar'][$i],
                        'telefono' => $_POST['telefono_familiar'][$i],
                        'celular' => $_POST['celular_familiar'][$i],
                    ]
                );

            }

            $_SESSION['formulario_plan']['type'] = 'success';
            $_SESSION['formulario_plan']['message'] = 'Muchisimas Gracias, la información fue almacenada con exito';

            header("Location: " . ruta("PlanEmergencia"));

        } catch (\Exception $th) {
            $_SESSION['formulario_plan']['type'] = 'danger';
            $_SESSION['formulario_plan']['message'] = 'Ocurrio un problema, por favor recargue la página e intentelo de nuevo';

            header("Location: " . ruta("PlanEmergencia"));
        }
    }

    public function actualizarPlanEmergencia()
    {

        try {
            //code...
            if(!isset($_POST['hermano_id'])){
                $_SESSION['message']['type'] = 'danger';
                $_SESSION['message']['message'] = 'Ocurrio un problema con el hermano seleccionado, por favor recargue la página e intentelo de nuevo';
                header("Location: " . ruta("PlanEmergenciaResumen"));
            }

            $hermano_id = $_POST['hermano_id'];
            if($_SESSION['_token_'] != $_POST['csfr_token']){
                $_SESSION['formulario_plan']['type'] = 'danger';
                $_SESSION['formulario_plan']['message'] = 'Ocurrio un problema -token, por favor recargue la página e intentelo de nuevo';
                header("Location: " . ruta("EditarHermano/$hermano_id"));
            }

            $updateHermanos = $this->db->table('hermanos')
                    ->where('id', $hermano_id)
                    ->update(
                        [
                            'perfil' => $_POST['perfil'],
                            'grupo' => $_POST['grupo'],
                            'nombres' => $_POST['nombres'],
                            'apellidos' => $_POST['apellidos'],
                            'direccion' => $_POST['direccion'],
                            'telefono' => $_POST['telefono'],
                            'celular1' => $_POST['celular1'],
                            'celular2' => $_POST['celular2'],
                            'nacimiento' => date('Y-m-d', strtotime($_POST['fecha_nacimiento'])),
                            'bautismo' => (isset($_POST['fecha_bautismo']) && !empty($_POST['fecha_bautismo'])) ? date('Y-m-d', strtotime($_POST['fecha_bautismo'])) : null,
                            'correo' => $_POST['correo'],
                            'fecha_actualizacion' => date('Y-m-d H:m:i'),
                        ]
                    );

            for ($i = 0; $i < count($_POST['familiar_id']); $i++) {

                $insertFamiliar = $this->db->table('familiares')
                        ->where('id', $_POST['familiar_id'][$i])
                        ->update(
                            [
                                'nombres' => $_POST['nombres_familiar'][$i],
                                'apellidos' => $_POST['apellidos_familiar'][$i],
                                'parentesco' => $_POST['parentesco_familiar'][$i],
                                'ciudad' => $_POST['ciudad_familiar'][$i],
                                'telefono' => $_POST['telefono_familiar'][$i],
                                'celular' => $_POST['celular_familiar'][$i],
                                'fecha_actualizacion' => date('Y-m-d H:m:i'),
                            ]
                        );

            }

            $_SESSION['formulario_plan']['type'] = 'success';
            $_SESSION['formulario_plan']['message'] = 'Muchisimas Gracias, la información fue almacenada con exito';

            header("Location: " . ruta("EditarHermano/$hermano_id"));

        } catch (\Exception $th) {
            $_SESSION['formulario_plan']['type'] = 'danger';
            $_SESSION['formulario_plan']['message'] = 'Ocurrio un problema, por favor recargue la página e intentelo de nuevo';

            header("Location: " . ruta("EditarHermano/$hermano_id"));
        }
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function migrateDates(){
        $hermanos = $this->db->table('hermanos')
            ->orderBy("nombres", "ASC")
            ->select()->all();

        foreach ($hermanos as $key => $value) {
            
            if(!$this->validateDate($value->fecha_nacimiento)){
                $nacimiento = null;
            }else{
                $nacimiento = date('Y-m-d', strtotime($value->fecha_nacimiento));
            }

            if(!$this->validateDate($value->fecha_bautismo)){
                $bautismo = null;
            }else{
                $bautismo = date('Y-m-d', strtotime($value->fecha_bautismo));
            }

            $this->db->table('hermanos')
                ->where('id', $value->id)
                ->update([
                            "nacimiento" => $nacimiento,
                            "bautismo" => $bautismo
                ]);
        }

        echo "Termine";
    }
}
