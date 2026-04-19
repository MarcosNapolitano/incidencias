<?php
/**
 * PrestaShop stubs básicos para Intelephense
 */

class Module {
    /** @var string */
    public $name;
    
    /** @var string */
    public $displayName;
    
    /** @var string */
    public $description;
    
    /** @var string */
    public $version;
    
    /** @var string */
    public $author;
    
    /** @var int */
    public $id;
    
    /** @var Context */
    public $context;
    
    public function __construct() {}
    public function install() {}
    public function uninstall() {}
    public function getContent() {}
    public function registerHook($hook_name) {}
    public function unregisterHook($hook_name) {}
    public function l($string, $class = null) {}
    public function displayConfirmation($string) {}
    public function displayError($string) {}
}

class ModuleAdminController {
    /** @var Context */
    public $context;
    
    /** @var string */
    public $content;
    
    /** @var array */
    public $errors = [];
    
    /** @var array */
    public $confirmations = [];
    
    /** @var bool */
    public $bootstrap = true;
    
    public function initContent() {}
    public function setMedia($isNewTheme = false) {}
    public function l($string, $class = null) {}
}

class ObjectModel {
    const TYPE_INT = 1;
    const TYPE_BOOL = 2;
    const TYPE_STRING = 3;
    const TYPE_FLOAT = 4;
    const TYPE_DATE = 5;
    const TYPE_HTML = 6;
    const TYPE_NOTHING = 7;
    
    /** @var int */
    public $id;
    
    /** @var array */
    public static $definition = [];
    
    public function __construct($id = null, $id_lang = null, $id_shop = null) {}
    public function add($autodate = true, $null_values = false) {}
    public function update($null_values = false) {}
    public function delete() {}
    public function save($null_values = false, $autodate = true) {}
    public function toggleStatus() {}
    public static function existsInDatabase($id, $table) {}
}

class Product extends ObjectModel {
    /** @var string */
    public $name;
    
    /** @var float */
    public $price;
    
    /** @var bool */
    public $active;
    
    /** @var int */
    public $id_product;
}

class Order extends ObjectModel {
    /** @var int */
    public $id_order;
    
    /** @var int */
    public $id_customer;
    
    /** @var float */
    public $total_paid;
}

class Customer extends ObjectModel {
    /** @var int */
    public $id_customer;
    
    /** @var string */
    public $firstname;
    
    /** @var string */
    public $lastname;
    
    /** @var string */
    public $email;
}

class Tools {
    public static function getValue($key, $default_value = false) {}
    public static function isSubmit($submit) {}
    public static function redirect($url, $base_uri = __PS_BASE_URI__, Link $link = null, $headers = null) {}
    public static function redirectAdmin($url) {}
    public static function displayPrice($price, $currency = null, $no_utf8 = false, Context $context = null) {}
    public static function getToken($page = true, Context $context = null) {}
    public static function strlen($str, $encoding = 'UTF-8') {}
    public static function strtolower($str) {}
    public static function substr($str, $start, $length = false, $encoding = 'utf-8') {}
    public static function str2url($str) {}
    public static function displayDate($date, $id_lang = null, $full = false, $separator = null) {}
    public static function clearCache($id_cache = null) {}
    public static function clearSmartyCache() {}
    public static function clearXMLCache() {}
}

class Validate {
    public static function isLoadedObject($object) {}
    public static function isEmail($email) {}
    public static function isInt($value) {}
    public static function isUnsignedInt($value) {}
    public static function isFloat($value) {}
    public static function isBool($value) {}
    public static function isUrl($url) {}
    public static function isGenericName($name) {}
    public static function isCleanHtml($html, $allow_iframe = false) {}
    public static function isPrice($price) {}
    public static function isDate($date) {}
}

class Configuration {
    public static function get($key, $id_lang = null, $id_shop_group = null, $id_shop = null, $default = false) {}
    public static function getInt($key, $id_shop_group = null, $id_shop = null) {}
    public static function getMultiple($keys, $id_lang = null, $id_shop_group = null, $id_shop = null) {}
    public static function set($key, $values, $html = false, $id_shop_group = null, $id_shop = null) {}
    public static function updateValue($key, $values, $html = false, $id_shop_group = null, $id_shop = null) {}
    public static function updateGlobalValue($key, $values, $html = false) {}
    public static function deleteByName($key) {}
    public static function hasKey($key, $id_lang = null, $id_shop_group = null, $id_shop = null) {}
}

class Db {
    public static function getInstance($master = true) {}
    public function execute($sql, $use_cache = true) {}
    public function executeS($sql, $array = true, $use_cache = true) {}
    public function getRow($sql, $use_cache = true) {}
    public function getValue($sql, $use_cache = true) {}
    public function insert($table, $data, $null_values = false, $use_cache = true, $type = Db::INSERT, $add_prefix = true) {}
    public function update($table, $data, $where = '', $limit = 0, $null_values = false, $use_cache = true, $add_prefix = true) {}
    public function delete($table, $where = '', $limit = 0, $use_cache = true, $add_prefix = true) {}
    public function escape($string, $html_ok = false) {}
    public function getMsgError() {}
}

function pSQL($string, $html_ok = false) {}
function bqSQL($string) {}

class Context {
    /** @var Context */
    public static $instance;
    
    /** @var Smarty */
    public $smarty;
    
    /** @var Language */
    public $language;
    
    /** @var Country */
    public $country;
    
    /** @var Currency */
    public $currency;
    
    /** @var Shop */
    public $shop;
    
    /** @var Customer */
    public $customer;
    
    /** @var Cart */
    public $cart;
    
    /** @var Employee */
    public $employee;
    
    /** @var Controller */
    public $controller;
    
    /** @var Link */
    public $link;
    
    /** @var Cookie */
    public $cookie;
    
    public static function getContext() {}
}

class Language {
    /** @var int */
    public $id;
    
    /** @var string */
    public $name;
    
    /** @var string */
    public $iso_code;
    
    public static function getLanguages($active = true, $id_shop = false, $ids_only = false) {}
}

class Link {
    public function getAdminLink($controller, $with_token = true, $sfRouteParams = [], $params = []) {}
    public function getProductLink($product, $alias = null, $category = null, $ean13 = null, $id_lang = null, $id_shop = null, $id_product_attribute = 0, $force_routes = false, $relative_protocol = false, $with_id_in_anchor = false, $extraParams = []) {}
    public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null, $relative_protocol = false) {}
}

class Tab {
    /** @var int */
    public $id;
    
    /** @var string */
    public $class_name;
    
    /** @var int */
    public $id_parent;
    
    /** @var string */
    public $module;
    
    /** @var bool */
    public $active;
    
    /** @var array */
    public $name;
    
    public function add($autodate = true, $null_values = false) {}
    public function delete() {}
    public static function getIdFromClassName($class_name) {}
}

class PrestaShopLogger {
    public static function addLog($message, $severity = 1, $error_code = null, $object_type = null, $object_id = null, $allow_duplicate = false, $id_employee = null) {}
}

class Hook {
    public static function exec($hook_name, $hook_args = [], $id_module = null, $array_return = false, $check_exceptions = true, $use_push = false, $id_shop = null, $chain = false) {}
}

class Employee extends ObjectModel {
    /** @var int */
    public $id_employee;
    
    /** @var string */
    public $firstname;
    
    /** @var string */
    public $lastname;
    
    /** @var string */
    public $email;
}

class Smarty {
    public function assign($tpl_var, $value = null, $nocache = false) {}
    public function fetch($template, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false) {}
}
