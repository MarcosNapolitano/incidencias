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

    $id_incidencia = (int)Tools::getValue('id', 0);

    $tipos = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_tipos');

    $categorias = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_categorias');

    $encargados = Db::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_encargados');

    /* // Procesar envío del formulario */
    /* if (Tools::isSubmit('submitGuardar')) { */
    /*   return $this->procesarFormulario(); */
    /* } */

    // csv
    if (pSQL(Tools::getValue('exportIncidencias'))) {
      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename=export.csv');

      $output = fopen('php://output', 'w');

      $sql = "SELECT * FROM ps_incidencias_incidencias";
      $results = Db::getInstance()->executeS($sql);

      if (!empty($results)) {
        // Sacar las claves del primer resultado
        $headers = array_keys($results[0]);
        fputcsv($output, $headers);

        // Datos
        foreach ($results as $row) {
          fputcsv($output, $row);
        }
      }
      fclose($output);
      exit;
    }

    if ($id_incidencia) {

      // Procesar envío de mensaje
      if (Tools::isSubmit('submitMensaje')) {
        return $this->procesarMensaje();
      };

      $incidencia = Db::getInstance()->executeS('
            SELECT i.*, o.reference, t.tipo, c.categoria, e.encargado FROM ' . _DB_PREFIX_ . 'incidencias_incidencias i
            LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON i.id_order = o.id_order
            LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_tipos t ON i.id_tipo = t.id_tipo
            LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_categorias c ON i.id_order = c.id_categoria
            LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_encargados e ON i.id_encargado = e.id_encargado
            WHERE i.id_incidencia = ' . $id_incidencia)[0];

      // to do: ver de indexar mejor esto, la query trae varios array noo solo 1
      if (!$incidencia) {
        Tools::redirect('index.php?controller=404');
      }

      $mensajes = DB::getInstance()->executeS('
            SELECT * FROM ' . _DB_PREFIX_ . 'incidencias_mensajes
            WHERE `id_incidencia` = ' . $id_incidencia);

      if ($incidencia["mensaje_customer"]) {
        require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php';
        $updateMensaje = new IncidenciasIncidencias($id_incidencia);
        $updateMensaje->mensaje_customer = 0;
        $updateMensaje->update();
      };

      $this->context->smarty->assign([
        'mensajes' => $mensajes,
        'categorias' => $categorias,
        'tipos' => $tipos,
        'encargados' => $encargados,
        'id_employee' => $this->context->employee->id,
        'incidencia' => $incidencia,
        'confirmations' => $this->confirmations,
        'errors' => $this->errors,
        'admin_link' => $this->context->link->getAdminLink('AdminIncidencias', true),
      ]);
      return $this->setTemplate('incidencias_detail.tpl');
    }

    $query = 'SELECT i.*, o.reference, c.categoria, t.tipo FROM ' . _DB_PREFIX_ . 'incidencias_incidencias i
              LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON i.id_order = o.id_order
              LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_categorias c ON i.id_categoria = c.id_categoria
              LEFT JOIN ' . _DB_PREFIX_ . 'incidencias_tipos t ON i.id_tipo = t.id_tipo';

    // Procesar filtro
    if (Tools::isSubmit('submitFiltro')) {
      $utilities = $this->procesarFiltro();
      $query = $query . $utilities[0];

      $this->context->smarty->assign(array(
        'parametros' => $utilities[1]
      ));
    };

    $incidencias = Db::getInstance()->executeS($query);

    // Asignar a Smarty
    // to do: agregar hashmap de parametros de filtro para asegurar persistencia
    $this->context->smarty->assign(array(
      'tipos' => $tipos,
      'categorias' => $categorias,
      'incidencias' => $incidencias,
      'admin_link' => $this->context->link->getAdminLink('AdminIncidencias', true),
    ));

    $this->setTemplate('incidencias.tpl');
  }

  /* protected function procesarFormulario() */
  /* { */
  /*   // Obtener valores del formulario */
  /*   $id_customer = (int)$this->context->customer->id; */
  /*   $id_order = (int)Tools::getValue('id_order'); */
  /*   $id_tipo = (int)Tools::getValue('id_tipo'); */
  /*   $mensaje = Tools::getValue('mensaje', '', true); */
  /**/
  /*   // Validar */
  /*   if (empty($mensaje)) { */
  /*     $this->errors[] = $this->l('No hay mensaje.'); */
  /*     return; */
  /*   } */
  /**/
  /*   require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasMensaje.php'; */
  /*   require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php'; */
  /**/
  /*   $registro = new IncidenciasIncidencias(); */
  /*   $nuevoMensaje = new IncidenciasMensaje(); */
  /**/
  /*   $registro->id_order = $id_order; */
  /*   $registro->id_customer = $id_customer; */
  /*   $registro->id_categoria = 1; */
  /*   $registro->id_tipo = $id_tipo; */
  /*   $registro->id_encargado = $encargado; */
  /*   $registro->estado = 1; */
  /*   $registro->creado = date('Y-m-d H:i:s'); */
  /*   $registro->modificado = $registro->creado; */
  /**/
  /*   $resultado = $registro->add(); */
  /**/
  /*   if ($resultado) { */
  /*     $nuevoMensaje->id_incidencia = $registro->id; */
  /*     $nuevoMensaje->id_customer = $id_customer; */
  /*     $nuevoMensaje->mensaje = $mensaje; */
  /*     $nuevoMensaje->creado = date('Y-m-d H:i:s'); */
  /**/
  /*     $nuevoMensaje->add(); */
  /*   } */
  /**/
  /*   Tools::redirect( */
  /*     $this->context->link->getModuleLink( */
  /*       $this->module->name, */
  /*       'menu', */
  /*       $resultado ? ['conf' => 1] : ['error' => 1] */
  /*     ) */
  /*   ); */
  /* } */

  protected function procesarMensaje()
  {

    $mensaje = Tools::getValue('mensaje', '', true);
    $id_incidencia = Tools::getValue('id');
    $nuevaCategoria = Tools::getValue('categoria');
    $nuevoTipo = Tools::getValue('tipo');
    $nuevoEstado = Tools::getValue('estado');
    $encargado = (int)Tools::getValue('encargado');

    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasIncidencias.php';
    require_once _PS_MODULE_DIR_ . 'incidencias/classes/IncidenciasMensaje.php';

    // Validar
    if (!empty($mensaje)) {
      $nuevoMensaje = new IncidenciasMensaje();

      $nuevoMensaje->id_incidencia = $id_incidencia;
      $nuevoMensaje->id_customer = (int) $this->context->employee->id;
      $nuevoMensaje->mensaje = $mensaje;
      $nuevoMensaje->creado = date('Y-m-d H:i:s');

      $resultado = $nuevoMensaje->add();
    }

    $updateIncidencia = new IncidenciasIncidencias($id_incidencia);
    $updateIncidencia->id_categoria = $nuevaCategoria;
    $updateIncidencia->id_tipo = $nuevoTipo;
    $updateIncidencia->id_encargado = $encargado;
    $updateIncidencia->estado = $nuevoEstado;
    $updateIncidencia->mensaje_employee = 1;

    //to do: mejorar esto
    $resultado = $updateIncidencia->update();

    if ($resultado) {
      Tools::redirectAdmin(self::$currentIndex . '&id=' . $id_incidencia . '&conf=4&token=' . $this->token);
    } else {
      Tools::redirectAdmin(self::$currentIndex . '&id=' . $id_incidencia . '&conf=1&token=' . $this->token);
    }
  }

  protected function procesarFiltro()
  {
    $filtroId = (int)Tools::getValue("id");
    $filtroFecha = pSQL(Tools::getValue("id"));
    $filtroPedido = pSQL(Tools::getValue("pedido"));
    $filtroTipo = (int)Tools::getValue("tipo");
    $filtroCategoria = (int)Tools::getValue("categoria");
    $filtroEstado = (int)Tools::getValue("estado");
    $filtroNuevo = (bool)Tools::getValue("nuevo");

    // esto determina si concatenamos where o and
    $secondParam = false;

    $filtroSelect = "";
    $parametros = [];

    //id, fecha, pedido, tipo, categoria, abierta o cerrada, nuevo mensaje a futuro
    //to do: refactorizar esto, es una basura
    if ($filtroPedido) {
      $filtroSelect = $filtroSelect . ' WHERE o.reference LIKE "' . strtoupper($filtroPedido) . '%"';
      $secondParam = true;
      $parametros["filtroPedido"] = $filtroPedido;
    }

    if ($filtroId) {
      $clausula = $secondParam ? " AND " : " WHERE ";
      $filtroSelect = $filtroSelect . $clausula . 'i.id_incidencia = ' . $filtroId;

      if (!$secondParam) $secondParam = true;
      $parametros["filtroId"] = $filtroId;
    }

    if ($filtroFecha && strtotime($filtroFecha) !== false) {
      $safeDate = pSQL($filtroFecha);
      //concat safe date
    }

    if ($filtroTipo) {
      $clausula = $secondParam ? " AND " : " WHERE ";
      $filtroSelect = $filtroSelect . $clausula . 'i.id_tipo = ' . $filtroTipo;

      if (!$secondParam) $secondParam = true;
      $parametros["filtroTipo"] = $filtroTipo;
    }

    if ($filtroCategoria) {
      $clausula = $secondParam ? " AND " : " WHERE ";
      $filtroSelect = $filtroSelect . $clausula . 'i.id_categoria = ' . $filtroCategoria;

      if (!$secondParam) $secondParam = true;
      $parametros["filtroCategoria"] = $filtroCategoria;
    }

    if ($filtroEstado) {
      $clausula = $secondParam ? " AND " : " WHERE ";

      $filtroEstado = $filtroEstado == 1 ? "1" : "0";

      $filtroSelect = $filtroSelect . $clausula . 'i.estado = ' . $filtroEstado;

      if (!$secondParam) $secondParam = true;
      $parametros["filtroEstado"] = $filtroEstado;
    }

    if ($filtroNuevo) {
      $clausula = $secondParam ? " AND " : " WHERE ";

      $filtroNuevo = $filtroNuevo == 1 ? "1" : "0";

      $filtroSelect = $filtroSelect . $clausula . 'i.mensaje_customer = ' . $filtroNuevo;
      $parametros["filtroNuevo"] = $filtroNuevo;
    }

    return [$filtroSelect, $parametros];
  }

  // es asi porque si
  public function setMedia($isNewTheme = false)
  {
    parent::setMedia();

    $this->addCSS($this->module->getPathUri() . 'views/css/incidencias.css');
  }
}
