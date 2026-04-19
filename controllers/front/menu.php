<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class IncidenciasMenuModuleFrontController extends ModuleFrontController
{
  public function initContent()
  {
    parent::initContent();
    // Procesar envío del formulario
    if (Tools::isSubmit('submitGuardar')) {
      $this->procesarFormulario();
    }

    // Obtener datos para el formulario (si edición)
    $id = (int)Tools::getValue('id_registro');
    $registro = null;

    if ($id) {
      $registro = Db::getInstance()->getRow(
        '
                SELECT * FROM ' . _DB_PREFIX_ . 'holamundo_registros 
                WHERE id_registro = ' . $id
      );
    }

    // Obtener categorías para el select
    $categorias = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'holamundo_categorias
        ');

    // Asignar a Smarty
    $this->context->smarty->assign(array(
      'registro' => $registro,
      'categorias' => $categorias,
      'link' => $this->context->link
    ));
    $this->setTemplate('module:incidencias/views/templates/front/incidencias_front.tpl');
  }

  protected function procesarFormulario()
  {
    // Obtener valores del formulario
    $id = (int)Tools::getValue('id_registro');
    $titulo = pSQL(Tools::getValue('titulo'));
    $descripcion = pSQL(Tools::getValue('descripcion'));
    $activo = (int)Tools::getValue('activo');
    $id_categoria = (int)Tools::getValue('id_categoria');

    // Validar
    if (empty($titulo)) {
      $this->errors[] = $this->l('El título es obligatorio');
      return;
    }

    // Guardar con ObjectModel
    if ($id) {
      // Actualizar
      /* $registro = new INCIDENCIA_MODEL($id); */
    } else {
      // Crear nuevo
      // NUEVA INCIDENCIA MODEL
      /* $registro = new HolaMundoRegistro(); */
    }

    /* $registro->titulo = $titulo; */
    /* $registro->descripcion = $descripcion; */
    /* $registro->activo = $activo; */
    /* $registro->id_categoria = $id_categoria; */
    /**/
    /* if ($id) { */
    /*   $resultado = $registro->update(); */
    /* } else { */
    /*   $resultado = $registro->add(); */
    /* } */
    /**/
    if ($resultado) {
      $this->confirmations[] = $this->l('Guardado correctamente');

      // Redireccionar a la lista
      Tools::redirectAdmin($this->context->link->getAdminLink('AdminHolaMundo'));
    } else {
      $this->errors[] = $this->l('Error al guardar');
    }
  }
}
