<?php
defined('_PS_VERSION_') OR die('No Direct Script Access Allowed');

class VC_frontendController extends ModuleAdminController 
{
    protected $_ajax_results;
    protected $_ajax_stripslash;
    protected $_filter_whitespace;
    protected $lushslider_model;
    public static $calledFor = 1;
    public function __construct() 
    {        
        $this->display_header = true;
        $this->display_footer = true;
        $this->content_only   = true;
        $this->toolbar_scroll = false;
        $this->show_toolbar = false;
        
        $this->module = 'jscomposer';       
        parent::__construct();
        $this->_ajax_results['error_on'] = 1;
    }

    public function display()
    {
        if(Tools::getValue('frontend_module_name')){
            $modules_configuration = JsComposer::getModulesConfiguration();

            $frontend_module_name = Tools::getValue('frontend_module_name');

            $module_type = '';
            $module_controller = '';
            $module_table = '';
            $module_identifier = '';
            $module_field = '';
            $module_status = '';
            $module_frontend_status = '';
            $module_backend_status = '';

            foreach ($modules_configuration as $key => $value) {
                if($value->controller == $frontend_module_name){

                    $module_type = (isset($value->type)) ? $value->type : '';
                    $module_controller = $value->controller;
                    $field_identifier = $value->identifier;
                    $field_content = $value->field;
                    $db_table = (isset($value->dbtable)) ? $value->dbtable : '';
                    $module_status = $value->module_status;
                    $module_frontend_status = $value->module_frontend_status;
                    $module_backend_status = $value->module_backend_status;

                    $back_url = array();
                    foreach($_GET as $key => $value){
                        $back_url[$key] = $value;
                    }
                    $back_url = urlencode(serialize($back_url));


                    $context = Context::getContext();
                    $id_lang = $context->language->id;

                    $page = '';
                    if($db_table != '')
                    $page = JsComposer::getJsControllerValues($db_table,$field_content,$field_identifier,Tools::getValue('val_identifier'),$id_lang);

                    $this->meta_title = (isset($page->meta_title)) ? $page->meta_title : '';

                    $this->initHeader();
                    $this->assignVCReqTplVars();
                    $content = $this->getFrontEditContent();
                    $this->initFooter();

                    $this->ajax = true;
                    $this->content = $content;
                 //  var_dump($content);die();
                    parent::display();
                }
            }
        }
    }
    
    public function initModal()
    {
        
    }
    public function assignVCReqTplVars(){
        
        $protocol_link = (Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode()) ? 'https://' : 'http://';
        $useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';
        $link = new Link($protocol_link, $protocol_content);
        $this->context->link = $link;
//        $page_name = $this->page_name;
        $currency = Tools::setCurrency($this->context->cookie);
        $cart = new Cart($this->context->cookie->id_cart);
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $meta_language = array();
        foreach ($languages as $lang)
                $meta_language[] = $lang['iso_code'];

        $compared_products = array();
        if (Configuration::get('PS_COMPARATOR_MAX_ITEM') && isset($this->context->cookie->id_compare))
                $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);

        Product::initPricesComputation();

        Context::getContext()->customer = new Customer(1);
        
        $display_tax_label = $this->context->country->display_tax_label;
        if (isset($cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}) && $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')})
        {
                $infos = Address::getCountryAndState((int)$cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                $country = new Country((int)$infos['id_country']);
                $this->context->country = $country;
                if (Validate::isLoadedObject($country))
                        $display_tax_label = $country->display_tax_label;
        }

        $this->context->smarty->assign(array(
                // Useful for layout.tpl
                'mobile_device'       => $this->context->getMobileDevice(),
                'link'                => $link,
                'cart'                => $cart,
                'currency'            => $currency,
                'currencyRate'        => method_exists($currency, 'getConversationRate') ? (float)$currency->getConversationRate() : null,
                'cookie'              => $this->context->cookie,
                'page_name'           => '',
               'hide_left_column'    => true,
               'hide_right_column'   => true,
                'tabs' => array(),
                'base_dir'            => _PS_BASE_URL_.__PS_BASE_URI__,
                'base_dir_ssl'        => $protocol_link.Tools::getShopDomainSsl().__PS_BASE_URI__,
                'force_ssl'           => Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'),
                'content_dir'         => $protocol_content.Tools::getHttpHost().__PS_BASE_URI__,
                'base_uri'            => $protocol_content.Tools::getHttpHost().__PS_BASE_URI__.(!Configuration::get('PS_REWRITING_SETTINGS') ? 'index.php' : ''),
                'tpl_dir'             => _PS_THEME_DIR_,
                'tpl_uri'             => _THEME_DIR_,
                'modules_dir'         => _MODULE_DIR_,
                'mail_dir'            => _MAIL_DIR_,
                'lang_iso'            => $this->context->language->iso_code,
                'lang_id'             => (int)$this->context->language->id,
                'language_code'       => $this->context->language->language_code ? $this->context->language->language_code : $this->context->language->iso_code,
                'come_from'           => Tools::getHttpHost(true, true).Tools::htmlentitiesUTF8(str_replace(array('\'', '\\'), '', urldecode($_SERVER['REQUEST_URI']))),
                'cart_qties'          => (int)$cart->nbProducts(),
                'currencies'          => Currency::getCurrencies(),
                'languages'           => $languages,
                'meta_language'       => implode(',', $meta_language),
                'priceDisplay'        => Product::getTaxCalculationMethod((int)$this->context->cookie->id_customer),
                'is_logged'           => true,
                'is_guest'            => false,
                'add_prod_display'    => (int)Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'shop_name'           => Configuration::get('PS_SHOP_NAME'),
                'roundMode'           => (int)Configuration::get('PS_PRICE_ROUND_MODE'),
                'use_taxes'           => (int)Configuration::get('PS_TAX'),
                'show_taxes'          => (int)(Configuration::get('PS_TAX_DISPLAY') == 1 && (int)Configuration::get('PS_TAX')),
                'display_tax_label'   => (bool)$display_tax_label,
                'vat_management'      => (int)Configuration::get('VATNUMBER_MANAGEMENT'),
                'opc'                 => (bool)Configuration::get('PS_ORDER_PROCESS_TYPE'),
                'PS_CATALOG_MODE'     => (bool)Configuration::get('PS_CATALOG_MODE') || (Group::isFeatureActive() && !(bool)Group::getCurrent()->show_prices),
                'b2b_enable'          => (bool)Configuration::get('PS_B2B_ENABLE'),
                'request'             => $link->getPaginationLink(false, false, false, true),
                'PS_STOCK_MANAGEMENT' => Configuration::get('PS_STOCK_MANAGEMENT'),
                'quick_view'          => (bool)Configuration::get('PS_QUICK_VIEW'),
                'shop_phone'          => Configuration::get('PS_SHOP_PHONE'),
                'compared_products'   => is_array($compared_products) ? $compared_products : array(),
                'comparator_max_item' => (int)Configuration::get('PS_COMPARATOR_MAX_ITEM'),
                'currencySign'        => $currency->sign, // backward compat, see global.tpl
                'currencyFormat'      => $currency->format, // backward compat
                'currencyBlank'       => $currency->blank, // backward compat
        ));
    }
    
    public function getFrontEditContent()
    {                
        ob_start();
        if(isset(JsComposer::$front_editor_actions['current_screen']) && is_callable(JsComposer::$front_editor_actions['current_screen']))
            call_user_func(JsComposer::$front_editor_actions['current_screen']);
        return ob_get_clean();
    }
    public function initHeader()
    {
        $this->addJqueryPlugin(array( 'fancybox'));
        parent::initHeader();

    }
    
    public function initFooter()
    {
        parent::initFooter();

    }
    protected function bindToAjaxRequest($post_method = false)
    {
        if(!$this->isXmlHttpRequest())
            die ('We Only Accept Ajax Request');
        if($post_method)
        {
            if(!isset ($_SERVER['REQUEST_METHOD']) OR 'POST' != $_SERVER['REQUEST_METHOD'])
                die ('Only POST Request Method is allowed');
        }                        
        return TRUE;                 
    }
}

