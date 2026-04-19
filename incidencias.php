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
    $sql1 = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_incidencias` (
    `id_incidencia` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    `id_order` INT(11) UNSIGNED NOT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1,
    `categoria` VARCHAR(255) NOT NULL DEFAULT \'Otros Motivos...\',
    `creado` DATETIME NOT NULL,
    `modificado` DATETIME NOT NULL,
    PRIMARY KEY (`id_incidencia`), 
    INDEX `idx_id_order` (`id_order`),
    CONSTRAINT `fk_' . $this->name . '_order`
        FOREIGN KEY (`id_order`)
        REFERENCES `' . _DB_PREFIX_ . 'orders`(`id_order`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

    $sql2 = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $this->name . '_mensajes` (
    `id_mensaje` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
    `id_incidencia` INT(11) UNSIGNED NOT NULL,
    `mensaje` TEXT NOT NULL,
    `creado` DATETIME NOT NULL,
    PRIMARY KEY (`id_mensaje`), 
    INDEX (`id_incidencia`),
    CONSTRAINT `fk_' . $this->name . '_incidencias`
    FOREIGN KEY (`id_incidencia`)
    REFERENCES `' . _DB_PREFIX_ . $this->name . '_incidencias`(`id_incidencia`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

    return parent::install()
      && DB::getInstance()->execute($sql1)
      && DB::getInstance()->execute($sql2)
      && $this->installTab()
      && $this->registerHook('displayCustomerAccount');
  }

  public function uninstall()
  {

    $sql1 = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_incidencias`';
    $sql2 = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $this->name . '_mensajes`';
    /* Limpiamos Cache */
    Tools::clearCache();

    /* borrar tambien lo que haya metido en la bbdd haciendo un DROP
      * aca se hacer al reves, porque tenes que borrar mensajes priemro */
    DB::getInstance()->execute($sql2);
    DB::getInstance()->execute($sql1);
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
    $output = '';

    if (Tools::isSubmit('submit' . $this->name)) {
      // this part is executed only when the form is submitted
      // retrieve the value set by the user
      $configValue = (string) Tools::getValue('INCIDENCIAS_CONFIG');

      // check that the value is valid
      if (empty($configValue) || !Validate::isGenericName($configValue)) {
        // invalid value, show an error
        $output = $this->displayError($this->l('Invalid Configuration value'));
      } else {
        // value is ok, update it and display a confirmation message
        Configuration::updateValue('INCIDENCIAS_CONFIG', $configValue);
        $output = $this->displayConfirmation($this->l('Settings updated'));
      }
    }

    // display any message, then the form
    return $output . $this->displayForm();
  }

  /**
   * Builds the configuration form
   * @return string HTML code
   */
  public function displayForm()
  {
    // Init Fields form array
    $form = [
      'form' => [
        'legend' => [
          'title' => $this->l('Settings'),
        ],
        'input' => [
          [
            'type' => 'text',
            'label' => $this->l('Configuration value'),
            'name' => 'INCIDENCIAS_CONFIG',
            'size' => 20,
            'required' => true,
          ],
        ],
        'submit' => [
          'title' => $this->l('Save'),
          'class' => 'btn btn-default pull-right',
        ],
      ],
    ];

    $helper = new HelperForm();

    // Module, token and currentIndex
    $helper->table = $this->table;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
    $helper->submit_action = 'submit' . $this->name;

    // Default language
    $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

    // Load current value into the form
    $helper->fields_value['INCIDENCIAS_CONFIG'] = Tools::getValue('INCIDENCIAS_CONFIG', Configuration::get('INCIDENCIAS_CONFIG'));

    return $helper->generateForm([$form]);
  }

  public function installTab()
  {
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminIncidencias';
    $tab->name = [];
    foreach (Language::getLanguages() as $lang) {
      $tab->name[$lang['id_lang']] = 'incidencias';
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
}
