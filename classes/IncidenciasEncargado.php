<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasEncargado extends ObjectModel
{
  public $id_encargado;
  public $encargado;

  public static $definition = [
    'table' => 'incidencias_encargado',
    'primary' => 'id_encargado',
    'fields' => [
      'encargado' => [
        'type' => self::TYPE_STRING,
        'validate' => 'isString',
        'required' => true,
      ],
    ],
  ];
}
