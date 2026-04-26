<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasMenuModuleFrontController extends ModuleFrontController
{
  public function initContent()
  {
    parent::initContent();

    if (!$this->context->customer->id)
      Tools::redirect('index.php?controller=404');

    $success = (int)Tools::getValue('conf', 0);
    $error = (int)Tools::getValue('error', 0);
    $id_incidencia = pSQL(Tools::getValue('id', 0));

    if ($id_incidencia) {

        $incidencia = Db::getInstance()->executeS('
            SELECT i.*, o.reference, t.tipo FROM ' . _DB_PREFIX_ . 'incidencias_incidencias i
            LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON i.id_order = o.id_order
            LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_tipos t ON i.id_tipo = t.id_tipo
            WHERE i.id_incidencia = ' . $id_incidencia)[0];

      // Procesar envío de mensaje
      if (Tools::isSubmit('submitMensaje')) {
        if($incidencia["estado"])
          $this->procesarMensaje();
        else
         $error = 1;
      };

      // ver de indexar mejor esto, la query trae varios array noo solo 1
      if (!$incidencia || $incidencia['id_customer'] != $this->context->customer->id) {
        Tools::redirect('index.php?controller=404');
      }

      $mensajes = DB::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_mensajes
            WHERE `id_incidencia` = ' . $id_incidencia);

      if ($incidencia["mensaje_employee"]) {
        require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php';
        $updateMensaje = new IncidenciasIncidencias($id_incidencia);
        $updateMensaje->mensaje_employee = 0;
        $updateMensaje->update();
      };

      $this->context->smarty->assign([
        'mensajes' => $mensajes,
        'id_customer' => $this->context->customer->id,
        'incidencia' => $incidencia,
        'success' => $success === 1,
        'error' => $error === 1,
      ]);

      return $this->setTemplate('module:incidencias/views/templates/front/incidencias_front_detail.tpl');
    }

    // Procesar envío del formulario
    if (Tools::isSubmit('submitGuardar')) {
      $this->procesarFormulario();
    }

    /* // Obtener categorías para el select */
    $user_id = $this->context->customer->id;

    $incidencias = Db::getInstance()->executeS('
            SELECT i.*, o.reference, t.tipo FROM ' . _DB_PREFIX_ . 'incidencias_incidencias i
            LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON i.id_order = o.id_order 
            LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_tipos t ON i.id_tipo = t.id_tipo 
            WHERE i.id_customer = ' . pSQL($user_id));

    $tipos = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_tipos');

    $pedidos = DB::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'orders WHERE
            `id_customer` = ' . pSQL($user_id));

    // Asignar a Smarty
    $this->context->smarty->assign(array(
      'tipos' => $tipos,
      'pedidos' => $pedidos,
      'incidencias' => $incidencias,
      'link' => $this->context->link,
      'success' => $success === 1,
      'error' => $error === 1,
    ));
    $this->setTemplate('module:incidencias/views/templates/front/incidencias_front.tpl');
  }

  protected function procesarFormulario()
  {
    // Obtener valores del formulario
    $id_customer = (int)$this->context->customer->id;
    $id_order = (int)Tools::getValue('id_order');
    $id_tipo = (int)Tools::getValue('id_tipo');
    $mensaje = Tools::getValue('mensaje', '', true);

    // Validar
    if (empty($mensaje)) {
      $this->errors[] = $this->l('No hay mensaje.');
      return;
    }

    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasMensaje.php';
    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php';

    $registro = new IncidenciasIncidencias();
    $nuevoMensaje = new IncidenciasMensaje();

    $registro->id_order = $id_order;
    $registro->id_customer = $id_customer;
    $registro->id_categoria = 1;
    $registro->id_tipo = $id_tipo;
    $registro->estado = 1;
    $registro->creado = date('Y-m-d H:i:s');
    $registro->modificado = $registro->creado;

    $resultado = $registro->add();

    if ($resultado) {
      $nuevoMensaje->id_incidencia = $registro->id;
      $nuevoMensaje->id_customer = $id_customer;
      $nuevoMensaje->mensaje = $mensaje;
      $nuevoMensaje->creado = date('Y-m-d H:i:s');

      $nuevoMensaje->add();
    }

    Tools::redirect(
      $this->context->link->getModuleLink(
        $this->module->name,
        'menu',
        $resultado ? ['conf' => 1] : ['error' => 1]
      )
    );
  }

  protected function procesarMensaje()
  {

    $mensaje = Tools::getValue('mensaje', '', true);
    $id_incidencia = Tools::getValue('id');

    // Validar
    if (empty($mensaje)) {
      $this->errors[] = $this->l('No hay mensaje.');
      return;
    }

    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php';
    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasMensaje.php';

    $updateIncidencia = new IncidenciasIncidencias($id_incidencia);
    $updateIncidencia->mensaje_customer = 1;
    $updateIncidencia->update();

    $nuevoMensaje = new IncidenciasMensaje();
    $nuevoMensaje->id_incidencia = $id_incidencia;
    $nuevoMensaje->id_customer = $this->context->customer->id;
    $nuevoMensaje->mensaje = $mensaje;
    $nuevoMensaje->creado = date('Y-m-d H:i:s');

    $resultado = $nuevoMensaje->add();

    Tools::redirect(
      $this->context->link->getModuleLink(
        $this->module->name,
        'menu',
        $resultado
          ? ['id' => $id_incidencia, 'conf' => 1]
          : ['id' => $id_incidencia, 'error' => 1]
      )
    );
  }

  public function setMedia()
  {
    parent::setMedia();

    $this->registerStylesheet(
      'mod-incidencias-style',
      'modules/' . $this->module->name . '/views/css/incidencias.css',
      [
        'media' => 'all',
        'priority' => 150,
      ]
    );
  }
}
