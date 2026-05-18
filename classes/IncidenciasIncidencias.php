<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasIncidencias extends ObjectModel
{
  public $id_incidencia;
  public $id_order;
  public $id_customer;
  public $id_categoria;
  public $id_tipo;
  public $id_encargado;
  public $mensaje_customer; public $mensaje_employee;
  public $estado;
  public $creado;
  public $modificado;
  public $coste;

  public static $definition = [
    'table' => 'incidencias_incidencias',
    'primary' => 'id_incidencia',
    'fields' => [
      'id_order' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
        'required' => true,
      ],
      'id_customer' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
        'required' => true,
      ],
      'id_categoria' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
        'required' => true,
      ],
      'id_tipo' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
        'required' => true,
      ],
      'id_encargado' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedId',
      ],
      'mensaje_customer' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedInt',
      ],
      'mensaje_employee' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedInt',
      ],
      'estado' => [
        'type' => self::TYPE_INT,
        'validate' => 'isUnsignedInt',
      ],
      'creado' => [
        'type' => self::TYPE_DATE,
        'validate' => 'isDate',
      ],
      'modificado' => [
        'type' => self::TYPE_DATE,
        'validate' => 'isDate',
      ],
      'coste' => [
        'type' => self::TYPE_FLOAT,
        'validate' => 'isFloat',
      ],
    ],
  ];
}
