<?php

include_once('vendor/autoload.php');

use ODB\DB\Database;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

class Api {

    function __constructor(){
        $this->initHandlerErrors();
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

    function getTelefonos(){
        $db = Database::connect();
        $result = $db->table('telefonos')->select();
        return $result->results();
    }
}

