<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasCategoria extends ObjectModel
{
  public $id_categoria;
  public $categoria;

  public static $definition = [
    'table' => 'incidencias_categorias',
    'primary' => 'id_categoria',
    'fields' => [
      'categoria' => [
        'type' => self::TYPE_STRING,
        'validate' => 'isString',
        'required' => true,
      ],
    ],
  ];
}
