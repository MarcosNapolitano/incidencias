<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasMensaje extends ObjectModel
{
  public $id_mensaje;
  public $id_incidencia;
  public $id_customer;
  public $mensaje;
  public $creado;

  public static $definition = [
    'table' => 'incidencias_mensajes',
    'primary' => 'id_mensaje',
    'fields' => [
      'id_incidencia' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
        'required' => true,
      ],
      'id_customer' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
        'required' => true,
      ],
      'mensaje' => [
        'type' => self::TYPE_HTML,
        'validate' => 'isCleanHtml',
        'required' => true,
      ],
      'creado' => [
        'type' => self::TYPE_DATE,
        'validate' => 'isDate',
      ]
    ],
  ];
}
