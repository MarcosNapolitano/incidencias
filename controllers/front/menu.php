<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasMenuModuleFrontController extends ModuleFrontController
{
  public function initContent()
  {
    parent::initContent();

    $this->setTemplate('module:incidencias/views/templates/front/incidencias_front.tpl');
  }
}
