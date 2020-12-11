<?php

return [
    "AMBIENTE" => "DEV", //PROD //DEV
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

    // "DOMINIO" => "https://canaverales.epizy.com/", //PROD
    "DOMINIO" => (isProduction())? "https://canaverales.epizy.com/" : "http://localhost:8888/predicacion/", //DEV

    /**
     * Intentos para actualizar
     */
    "INTENTOS_UPDATE" => 3,

    /**
     * Configuracion de los horarios por cada dia de la semana
     */
    "CONFIG_LUNES_MIERCOLES_JUEVES_VIERNES" => [
        [
            "fechaInicio" => strtotime(date("Y-m-d 08:00:00")),
            "fechaFin" => strtotime(date("Y-m-d 12:00:00"))
        ],
        [
            "fechaInicio" => strtotime(date("Y-m-d 14:00:00")),
            "fechaFin" => strtotime(date("Y-m-d 20:00:00"))
        ]
    ],
    "CONFIG_MARTES" => [
        [
            "fechaInicio" => strtotime(date("Y-m-d 08:00:00")),
            "fechaFin" => strtotime(date("Y-m-d 12:00:00"))
        ]
    ],
    "CONFIG_SABADO_DOMINGO" => [
        [
            "fechaInicio" => strtotime(date("Y-m-d 08:00:00")),
            "fechaFin" => strtotime(date("Y-m-d 13:00:00"))
        ]
    ],

    /**
     * Mocks Plan de emergencia
     */
    "PERFIL_HERMANO" => [
        "estudiante" => "Estudiante",
        "publicado_no_bautizado" => "Publicador no bautizado",
        "bautizado" => "Bautizado",
    ],

    "GRUPOS_CONGRE" => [
        "1" => "Grupo 1 - Alberto Rios",
        "2" => "Grupo 2 - Amado Murillo",
        "3" => "Grupo 3 - Mesías Gualguan Rios",
        "4" => "Grupo 4 - Duber Nieto",
        "5" => "Grupo 5 - Rodolfo Ortiz",
        "6" => "Grupo 6 - Ariel Rivera",
        "7" => "Grupo 7 - Numar Bermúdez",
    ]
];