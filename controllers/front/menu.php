<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasMenuModuleFrontController extends ModuleFrontController
{
  public function initContent()
  {
    parent::initContent();

    if (Tools::getValue('conf') == 1) {
      $this->context->smarty->assign('success', true);
    }

    if (Tools::getValue('error') == 1) {
      $this->context->smarty->assign('error', true);
    }

    // Procesar envío del formulario
    if (Tools::isSubmit('submitGuardar')) {
      $this->procesarFormulario();
    }

    /* // Obtener categorías para el select */
    $user_id = $this->context->customer->id;
    $pedidos = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'orders WHERE `id_customer` = ' . pSQL($user_id));

    $tipos = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_tipos');
    $incidencias = DB::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_incidencias WHERE
            `id_customer` = ' . pSQL($user_id));

    // Asignar a Smarty
    $this->context->smarty->assign(array(
      'tipos' => $tipos,
      'pedidos' => $pedidos,
      'incidencias' => $incidencias,
      'link' => $this->context->link
    ));
    $this->setTemplate('module:incidencias/views/templates/front/incidencias_front.tpl');
  }

  protected function procesarFormulario()
  {
    // Obtener valores del formulario
    $id_customer = (int)$this->context->customer->id;
    $id_order = (int)Tools::getValue('id_order');
    $id_tipo = (int)Tools::getValue('id_tipo');
    $mensaje = pSQL(Tools::getValue('mensaje'));

    // Validar
    if (empty($mensaje)) {
      $this->errors[] = $this->l('No hay mensaje.');
      return;
    }

    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php';

    $registro = new IncidenciasIncidencias();

    $registro->id_order = $id_order;
    $registro->id_customer = $id_customer;
    $registro->id_categoria = 1;
    $registro->id_tipo = $id_tipo;
    $registro->estado = 1;

    $resultado = $registro->add();

    Tools::redirect(
      $this->context->link->getModuleLink(
        $this->module->name,
        'incidencias',
        $resultado ? ['conf' => 1] : ['error' => 1]
      )
    );
  }
}
