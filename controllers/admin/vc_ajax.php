<?php
defined('_PS_VERSION_') OR die('No Direct Script Access Allowed');


class VC_ajaxController extends ModuleAdminController 
{
    protected $_ajax_results;
    protected $_ajax_stripslash;
    protected $_filter_whitespace;
    protected $lushslider_model;
    public function __construct() 
    {        
        $this->display_header = false;
        $this->display_footer = false;
        $this->content_only   = true;
        parent::__construct();
        $this->_ajax_results['error_on'] = 1;
    }
    public function init()
    {        
        $this->initProcess();
    }
    public function initProcess()
    {
        if(Tools::getvalue('hook_filter')){
            $this->HookFilter();
        }elseif(Tools::getvalue('vcupcss')){
            $vc_css = Tools::getvalue('vc_css');
            $id_lang =  Tools::getvalue('id_lang');
            $page_type = Tools::getvalue('page_type');
            $page_id = Tools::getvalue('page_id');
            $optionname = "_wpb_{$page_type}_{$page_id}_{$id_lang}_css";
            Configuration::updateValue($optionname,$vc_css,true);
            die();
        }else{

            $jscomposer = Module::getInstanceByName('jscomposer');
            $action = $jscomposer->vc_post_param('action');
            $this->assignVCReqTplVars();
            $jscomposer->init();

            Hook::exec('VcExternalAjaxActions');

            if(isset(JsComposer::$sds_action_hooks[$action])){
                call_user_func(JsComposer::$sds_action_hooks[$action]);
            }
            die();
        }
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

        Context::getContext()->customer = new Customer();
        
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
    public function HookFilter()
    {
        $vcaw = vccontentanywhere::GetInstance();
        if((Tools::getvalue('hook_filter')) && ((Tools::getValue('vc_module_name')) || (Tools::getValue('vc_hook_name')))){
            $vc_module_name = Tools::getValue('vc_module_name');
            $vc_hook_name = Tools::getValue('vc_hook_name');
            $html = $vcaw->GetModuleHook($vc_module_name,$vc_hook_name);
            die($html);
        }
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

