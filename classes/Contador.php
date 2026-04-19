<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class Contador extends ObjectModel
{
    public $titulo;
    public $valor;
    
    // Define estructura de DB
    public static $definition = array(
        'table' => 'product',
        'primary' => 'id_product',
        'fields' => array()
    );
}
