<?php

if (!defined('_PS_VERSION_')) {
  exit;
}

class Incidencias extends Module
{
  public function __construct()
  {
    $this->name = 'incidencias';
    $this->tab =  'administration';
    $this->version = '1.0.0';
    $this->author = 'Marcos Napolitano';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = [
      'max' => '9.99.99',
      'min' => '8.0.0',
    ];
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->trans('Incidencias', [], 'Modules.Incidencias.Admin');
    $this->description = $this->trans('Gestión de Incidencias', [], 'Modules.Incidencias.Admin');

    $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Incidencias.Admin');

    if (!Configuration::get('INCIDENCIAS_NAME')) {
      $this->warning = $this->trans('No name provided', [], 'Modules.Incidencias.Admin');
    }
  }

  public function install()
  {
    $sql = [
      'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_tipos` (
    `id_tipo` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    `tipo` VARCHAR(255) NOT NULL,
    `mensaje_predefinido` LONGTEXT NULL,
    PRIMARY KEY (`id_tipo`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',

      'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_estados_tipos` (
    `id_tipo` INT(11) UNSIGNED NOT NULL,
    `id_order_state` INT NOT NULL,
    PRIMARY KEY (`id_tipo`, `id_order_state`),
    FOREIGN KEY (`id_tipo`) REFERENCES `' . _DB_PREFIX_ . $this->name . '_tipos`(`id_tipo`) 
      ON DELETE CASCADE,
    FOREIGN KEY (`id_order_state`) REFERENCES `' . _DB_PREFIX_ . '_order_state`(`id_order_state`) 
      ON DELETE CASCADE
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',

      'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_categorias` (
    `id_categoria` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    `categoria` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id_categoria`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',


      'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_encargados` (
    `id_encargado` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `encargado` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id_encargado`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',

      'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_incidencias` (
    `id_incidencia` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_order` INT UNSIGNED NOT NULL,
    `id_customer` INT UNSIGNED NULL,
    `id_categoria` INT UNSIGNED NULL,
    `id_tipo` INT UNSIGNED NULL,
    `id_encargado` INT UNSIGNED DEFAULT 9,
    `estado` TINYINT(1) NOT NULL DEFAULT 1,
    `mensaje_customer` TINYINT(1) NOT NULL DEFAULT 1,
    `mensaje_employee` TINYINT(1) NOT NULL DEFAULT 0,
    `creado` DATETIME NOT NULL,
    `modificado` DATETIME NOT NULL,
    `estado_flow` VARCHAR(255),
    `maquina` VARCHAR(255),
    `coste` DECIMAL(20,6) NULL,
    PRIMARY KEY (`id_incidencia`), 
    INDEX `idx_id_order` (`id_order`),
    CONSTRAINT `fk_' . $this->name . '_order`
        FOREIGN KEY (`id_order`)
        REFERENCES `' . _DB_PREFIX_ . 'orders`(`id_order`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX `idx_id_customer` (`id_customer`),
    CONSTRAINT `fk_' . $this->name . '_customer`
        FOREIGN KEY (`id_customer`)
        REFERENCES `' . _DB_PREFIX_ . 'customer`(`id_customer`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX `idx_id_categoria` (`id_categoria`),
    CONSTRAINT `fk_' . $this->name . '_categoria`
        FOREIGN KEY (`id_categoria`)
        REFERENCES `' . _DB_PREFIX_ . 'incidencias_categorias`(`id_categoria`)
        ON DELETE SET NULL
        ON UPDATE SET NULL,
    INDEX `idx_id_tipo` (`id_tipo`),
    CONSTRAINT `fk_' . $this->name . '_tipo` FOREIGN KEY (`id_tipo`) REFERENCES `' . _DB_PREFIX_ . 'incidencias_tipos`(`id_tipo`)
        ON DELETE SET NULL
        ON UPDATE SET NULL,
    INDEX `idx_id_encargado` (`id_encargado`),
    CONSTRAINT `fk_' . $this->name . '_encargado`
        FOREIGN KEY (`id_encargado`)
        REFERENCES `' . _DB_PREFIX_ . 'incidencias_encargados`(`id_encargado`)
        ON DELETE SET DEFAULT
        ON UPDATE SET DEFAULT
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',

      'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_mensajes` (
    `id_mensaje` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_incidencia` INT UNSIGNED NOT NULL,
    `id_customer` INT UNSIGNED NOT NULL,
    `mensaje` TEXT NOT NULL,
    `creado` DATETIME NOT NULL,
    PRIMARY KEY (`id_mensaje`), 
    INDEX (`id_incidencia`),
    CONSTRAINT `fk_' . $this->name . '_incidencias`
        FOREIGN KEY (`id_incidencia`)
        REFERENCES `' . _DB_PREFIX_ . $this->name . '_incidencias`(`id_incidencia`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    INDEX (`id_customer`),
    CONSTRAINT `fk_' . $this->name . 'mensaje_customer`
        FOREIGN KEY (`id_customer`)
        REFERENCES `' . _DB_PREFIX_ . 'customer`(`id_customer`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',

      'INSERT INTO `' . _DB_PREFIX_ . $this->name . '_encargados` 
        (`id_encargado`, `encargado`) VALUES (1, "Regli"), (2, "Josema"), 
        (3, "Alvaro"), (4, "Lina"), (5, "Luis"), (6, "Bernardo"), (7, "Marcos"), 
        (8, "Mihaela"), (9, "Otro"), (10, "Seur"), (11, "MRW");'
    ];

    foreach ($sql as $query) {
      DB::getInstance()->execute($query);
    };

    $temp = [];

    $categorias = [
      "Pendiente Respuesta a Cliente",
      "Pendiente Indemnización",
      "Pendiente Datos del Cliente",
      "Envío Demorado"
    ];

    foreach ($categorias as $cat) {
      $temp[] = "('" . pSQL($cat) . "')";
    };

    $sql = "INSERT INTO `" . _DB_PREFIX_ . $this->name . "_categorias` (categoria)
      VALUES " . implode(',', $temp);

    DB::getInstance()->execute($sql);

    $temp = [];

    $tipos = [
      "Hay un retraso en mi pedido",
      "He recibido un pedido que no es mío",
      "Mi pedido ha llegado roto",
      "Mi pedido tiene defectos en la impresión",
      "Faltan artículos en mi pedido",
      "Quiero cancelar mi pedido",
      "Otros motivos..."
    ];

    foreach ($tipos as $tipo) {
      $temp[] = "('" . pSQL($tipo) . "')";
    };

    $sql = "INSERT INTO `" . _DB_PREFIX_ . $this->name . "_tipos` (tipo)
      VALUES " . implode(',', $temp);

    DB::getInstance()->execute($sql);

    return parent::install()
      && $this->installTab()
      && $this->registerHook('displayCustomerAccount');
  }

  public function uninstall()
  {

    $sql = [
      'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_mensajes`',
      'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_incidencias`',
      'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_categorias`',
      'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_estados_tipos`',
      'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_tipos`',
      'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_encargados`',
    ];
    /* Limpiamos Cache */
    Tools::clearCache();

    /* borrar tambien lo que haya metido en la bbdd haciendo un DROP
      * aca se hacer al reves, porque tenes que borrar mensajes priemro */
    foreach ($sql as $query) {
      DB::getInstance()->execute($query);
    }
    Configuration::deleteByName(strtoupper($this->name) . '_NAME');
    $this->uninstallTab();

    return parent::uninstall();
  }

  /**
   * This method handles the module's configuration page
   * @return string The page's HTML content
   */
  public function getContent()
  {

    $this->context->controller->addJS($this->getPathUri() . 'views/js/admin.js');
    $success = Tools::getValue('conf');
    $error = Tools::getValue('error');

    if (Tools::isSubmit('saveTipo')) return $this->procesarTipo();

    if (Tools::isSubmit('saveCategoria')) return $this->procesarCategoria();

    if (Tools::isSubmit('saveEncargado')) return $this->procesarEncargado();

    $encargados = DB::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_encargados`');
    $categorias = DB::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_categorias`');
    $tipos = DB::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . $this->name . '_tipos`');

    $this->context->smarty->assign([
      'encargados' => $encargados,
      'categorias' => $categorias,
      'tipos' => $tipos,
      'success' => $success === 1,
      'error' => $error === 1
    ]);

    return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
  }


  public function installTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminIncidencias';
    $tab->name = [];
    foreach (Language::getLanguages() as $lang) {
      $tab->name[$lang['id_lang']] = 'Incidencias';
    }
    $tab->id_parent = Tab::getIdFromClassName('AdminParentModulesSf');
    $tab->module = $this->name;

    return $tab->add();
  }
  private function uninstallTab()
  {
    $id_tab = (int) Tab::getIdFromClassName('AdminIncidencias');
    if ($id_tab) {
      $tab = new Tab($id_tab);
      $tab->delete();
    }
    return true;
  }

  public function hookDisplayCustomerAccount()
  {
    return $this->fetch('module:incidencias/views/templates/hook/myaccount.tpl');
  }

  public function procesarTipo()
  {
    $tipo = pSQL(Tools::getValue("nombre"));

    if (!$tipo)
      return Tools::redirectAdmin(
        $this->context->link->getAdminLink('AdminModules', true, null, [
          'configure' => $this->name,
          'error' => 1
        ])
      );

    $query = '';
    $id = (int)Tools::getValue("id_tipo");
    $mensaje = pSQL(Tools::getValue("mensaje"));

    die($id);
    if (!$id)
      $query = 'INSERT INTO `' . _DB_PREFIX_ . $this->name . '_tipos`(`tipo`) 
      VALUES ("' . $tipo . '")';
    else
      $query = 'UPDATE `' . _DB_PREFIX_ . $this->name . '_tipos` as t SET `tipo` = "'
        . $tipo . '", `mensaje_predefinido` = "' . $mensaje . '" WHERE t.`id_tipo` = ' . $id;

    $resultado = DB::getInstance()->execute($query);
    $params = ['configure' => $this->name];

    $resultado ? $params['conf'] = 1 : $params['error'] = 1;

    return Tools::redirectAdmin(
      $this->context->link->getAdminLink('AdminModules', true, null, $params)
    );
  }

  public function procesarCategoria()
  {
    $categoria = pSQL(Tools::getValue("nombre"));

    if (!$categoria)
      return Tools::redirectAdmin(
        $this->context->link->getAdminLink('AdminModules', true, null, [
          'configure' => $this->name,
          'error' => 1
        ])
      );

    $query = '';
    $id = (int)Tools::getValue("id_categoria");

    if (!$id)
      $query = 'INSERT INTO `' . _DB_PREFIX_ . $this->name . '_categoria`(`categoria`) 
      VALUES ("' . $categoria . '")';
    else
      $query = 'UPDATE `' . _DB_PREFIX_ . $this->name . '_tipos` as c SET 
      `categoria` = "' . $categoria . '" WHERE c.`id_categoria` = ' . $id;

    $resultado = DB::getInstance()->execute($query);
    $params = ['configure' => $this->name];

    $resultado ? $params['conf'] = 1 : $params['error'] = 1;

    return Tools::redirectAdmin(
      $this->context->link->getAdminLink('AdminModules', true, null, $params)
    );
  }

  public function procesarEncargado() {}
}
