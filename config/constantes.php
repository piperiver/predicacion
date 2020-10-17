<?php

return [
    "AMBIENTE" => "DEV", //PROD
    /**
     * ESTADOS TELEFONOS
     */
    "INACTIVO" => 1,
    "REVISITA" => 2,
    "NO_LLAMAR" => 3,
    "ACTIVO" => 5,


    /**
     * Label estados
     */
    "ESTADO_TEXT" => [
        "1" => "INACTIVO",
        "2" =>  "REVISITA",
        "3" =>  "NO_LLAMAR",
        "5" => "ACTIVO",
    ],

    /**
     * TELEFONO USADO O DISPONIBLE
     */
    "USADO" => 1,
    "DISPONIBLE" => 0,

    /**
     * Ruta principal del proyecto
     */
    "DOMINIO" => "http://localhost:8888/predicacion/",

    /**
     * Intentos para actualizar
     */
    "INTENTOS_UPDATE" => 3,
];