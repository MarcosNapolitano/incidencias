<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class AdminIncidenciasController extends ModuleAdminController
{
  public function __construct()
  {
    return parent::__construct();

    $this->bootstrap = true;
    $this->context = Context::getContext();
  }

  public function initContent()
  {
    parent::initContent();

    $mensaje = "Hola mundo desde el controlador";
    $datos = array(
      'nombre' => 'PrestaShop',
      'version' => _PS_VERSION_,
      'fecha' => date('d/m/y H:i:s')
    );

    $this->context->smarty->assign(array(
      'mensaje' => $mensaje,
      'datos' => $datos
    ));

    $this->setTemplate('incidencias.tpl');
  }
}
