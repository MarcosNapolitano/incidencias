<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasTipo extends ObjectModel
{
  public $id_tipo;
  public $tipo;
  public $mensaje_predefinido;

  public static $definition = [
    'table' => 'incidencias_tipos',
    'primary' => 'id_tipo',
    'fields' => [
      'tipo' => [
        'type' => self::TYPE_STRING,
        'validate' => 'isString',
        'required' => true,
      ],
      'mensaje_predefinido' => [
        'type' => self::TYPE_HTML,
        'validate' => 'isCleanHtml'
      ]
    ],
  ];
}
