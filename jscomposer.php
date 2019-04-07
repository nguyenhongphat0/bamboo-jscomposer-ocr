<?php
if (!defined('_PS_VERSION_'))
    exit;

if (!defined('WPB_VC_VERSION'))
    define('WPB_VC_VERSION', '4.4.11');

if (!defined('WPB_JQUERY_UI_VERSION'))
    define('WPB_JQUERY_UI_VERSION', 'less');

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

require_once (dirname(__FILE__) . '/classes/VcImageType.php');
require_once (dirname(__FILE__) . '/classes/vccontentanywhere.php');
require_once (dirname(__FILE__) . '/classes/vcproducttabcreator.php');
require_once (dirname(__FILE__) . '/classes/smartlisence.php');

class JsComposer extends Module implements WidgetInterface
{

    //the purpose of this variable to reduce db call , hook wise
    public $hook_content = array();
    public $composer_settings, $post_custom_css = array(), $custom_user_templates_dir = false, $front_js = array(), $front_css = array();
    protected $default_templates = false;
    public static $_url, $instance, $shortcode_tags = array(), $static_shortcode_tags = array(), $sds_current_hook, $sds_action_hooks = array(), $vc_before_init = array();
    public static $front_content_scripts = array(), $backOfficeCalledFor = 0, $VCBackofficeShortcodesAction = array();
    public static $VC_MEDIA = 'vc_media', $vc_translations = array();
    public $vccawobj;
    public $vctcbj;
    private $contentBoxNamesByController = array(
        'AdminBlogPost' => 'content',
        'AdminProducts' => 'description',
        'AdminSuppliers' => 'description',
        'AdminManufacturers' => 'description',
        'AdminCategories' => 'description',
        'AdminCmsContent' => 'content',
        'VC_frontend' => 'content',
        'Adminvccontentanywhere' => 'content',
        'Adminvcproducttabcreator' => 'content',
    );
    public static $vc_image_allowed_attr = 'image|images|img|button_bg_img|icon_img|spacer_img|thumb_img|banner_image|info_img|btn_img|bg_image_new|video_poster|swatch_trans_bg_img|layer_image';
    public $controls = array(
        'add_element',
        'templates',
        'save_backend',
        'preview',
        'frontend',
        'custom_css'
    );
    public static $registeredCSS = array(), $registeredJS = array(), $front_editor_actions = array();
    public $ajaxController, $image_sizes = array(), $image_sizes_dropdown = array();
    public $mode = 'admin_page';
    public $factory = array();
    public $brand_url = 'http://vc.wpbakery.com/?utm_campaign=VCplugin_header&utm_source=vc_user&utm_medium=backend_editor';
    public $css_class = 'vc_navbar';
    public $controls_filter_name = 'vc_nav_controls';
    public $vc_row_layouts = array(
        array('cells' => '11', 'mask' => '12', 'title' => '1/1', 'icon_class' => 'l_11'),
        array('cells' => '12_12', 'mask' => '26', 'title' => '1/2 + 1/2', 'icon_class' => 'l_12_12'),
        array('cells' => '23_13', 'mask' => '29', 'title' => '2/3 + 1/3', 'icon_class' => 'l_23_13'),
        array('cells' => '13_13_13', 'mask' => '312', 'title' => '1/3 + 1/3 + 1/3', 'icon_class' => 'l_13_13_13'),
        array('cells' => '14_14_14_14', 'mask' => '420', 'title' => '1/4 + 1/4 + 1/4 + 1/4', 'icon_class' => 'l_14_14_14_14'),
        array('cells' => '14_34', 'mask' => '212', 'title' => '1/4 + 3/4', 'icon_class' => 'l_14_34'),
        array('cells' => '14_12_14', 'mask' => '313', 'title' => '1/4 + 1/2 + 1/4', 'icon_class' => 'l_14_12_14'),
        array('cells' => '56_16', 'mask' => '218', 'title' => '5/6 + 1/6', 'icon_class' => 'l_56_16'),
        array('cells' => '16_16_16_16_16_16', 'mask' => '642', 'title' => '1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6', 'icon_class' => 'l_16_16_16_16_16_16'),
        array('cells' => '16_23_16', 'mask' => '319', 'title' => '1/6 + 4/6 + 1/6', 'icon_class' => 'l_16_46_16'),
        array('cells' => '16_16_16_12', 'mask' => '424', 'title' => '1/6 + 1/6 + 1/6 + 1/2', 'icon_class' => 'l_16_16_16_12')
    );
    public $wpb_js_composer_js_view = array();
    public $wpb_js_composer_automapper = array();
    public $smarty;
    private static $isVcAdminCustomController, $vcBackofficePageIndenfiers;
    public static $vcCustomPageType, $vcCustomPageId;
    public static $vc_version;
    public static $vc_mode_name;


    public function __construct()
    {
        $dir = _PS_MODULE_DIR_ . 'jscomposer';
        $this->name = 'jscomposer';
        self::$vc_mode_name = $this->name;
        $this->tab = 'front_office_features';
        $this->version = WPB_VC_VERSION;
        self::$vc_version = $this->version;
        $this->author = 'smartdatasoft';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        parent::__construct();
        
        $this->smarty = $this->context->smarty;
        self::$instance = $this;
        self::$_url = $this->_path;
        self::$sds_action_hooks['wpb_single_image_src'] = array(&$this, 'wpb_single_image_src');
        self::$sds_action_hooks['wpb_gallery_html'] = array('Vc_Base', 'galleryHTML');
        self::$sds_action_hooks['delete_image'] = array('JsComposer', 'delete_uploaded_file');
        self::$sds_action_hooks['editpost'] = array($this, 'update_cms_frontend');
//        self::$sds_action_hooks['wpb_save_css_values'] =  array($this,'updateCSSValues');
        self::$sds_action_hooks['wpb_get_preview_link'] = array($this, 'getPreviewLink');
        self::$sds_action_hooks['vcca_ajax_get_products'] = array($this, 'getProductsList'); 

        self::$sds_action_hooks['vc_get_autocomplete_suggestion'] = array($this, 'vc_get_autocomplete_suggestion');

        $is_overrides = Configuration::get('PS_DISABLE_OVERRIDES');

        if ($is_overrides == 1)
            $this->warning = $this->l('Disable overrides option is enabled. It must be disabled before using this Visual Composer module.');

        $this->displayName = $this->l('Visual Composer');
        $this->description = $this->l('Visual Composer - Content builder for Prestashop');
        $this->composer_settings = array(
            'APP_ROOT' => $dir,
            'UPLOADS_DIR' => $dir . '/uploads/',
            'WP_ROOT' => _PS_ROOT_DIR_,
            'APP_DIR' => basename($dir),
            'CONFIG_DIR' => $dir . '/config',
            'ASSETS_DIR' => $dir . '/assets',
            'ASSETS_DIR_NAME' => 'assets',
            'CORE_DIR' => $dir . '/include/classes/core',
            'HELPERS_DIR' => $dir . '/include/helpers',
            'SHORTCODES_DIR' => $dir . '/include/classes/shortcodes',
            'SETTINGS_DIR' => $dir . '/include/classes/settings',
            'TEMPLATES_DIR' => $dir . '/include/templates',
            'EDITORS_DIR' => $dir . '/include/classes/editors',
            'PARAMS_DIR' => $dir . '/include/params',
            'UPDATERS_DIR' => $dir . '/include/classes/updaters',
            'VENDORS_DIR' => $dir . '/include/classes/vendors'
        );
        $this->wpb_js_composer_automapper = array('i18nLocaleVcAutomapper', array(
                'are_you_sure_delete' => $this->l('Are you sure you want to delete this shortcode?'),
                'are_you_sure_delete_param' => $this->l("Are you sure you want to delete the shortcode's param?"),
                'my_shortcodes_category' => $this->l('My shortcodes'),
                'error_shortcode_name_is_required' => $this->l("Shortcode name is required."),
                'error_enter_valid_shortcode_tag' => $this->l("Please enter valid shortcode tag."),
                'error_enter_required_fields' => $this->l("Please enter all required fields for params."),
                'new_shortcode_mapped' => $this->l('New shortcode mapped from string!'),
                'shortcode_updated' => $this->l('Shortcode updated!'),
                'error_content_param_not_manually' => $this->l('Content param can not be added manually, please use checkbox.'),
                'error_param_already_exists' => $this->l('Param %s already exists. Param names must be unique.'),
                'error_wrong_param_name' => $this->l('Please use only letters, numbers and underscore for param name'),
                'error_enter_valid_shortcode' => $this->l('Please enter valid shortcode to parse!')
        ));
        $this->wpb_js_composer_js_view = array('i18nLocale',
            array(
                'add_remove_picture' => $this->l('Add/remove picture'),
                'finish_adding_text' => $this->l('Finish Adding Images'),
                'add_image' => $this->l('Add Image'),
                'add_images' => $this->l('Add Images'),
                'main_button_title' => $this->l('Visual Composer'),
                'main_button_title_backend_editor' => $this->l('BACKEND EDITOR'),
                'main_button_title_frontend_editor' => $this->l('FRONTEND EDITOR'),
                'main_button_title_revert' => $this->l('CLASSIC MODE'),
                'please_enter_templates_name' => $this->l('Please enter template name'),
                'confirm_deleting_template' => $this->l('Confirm deleting "{template_name}" template, press Cancel to leave. This action cannot be undone.'),
                'press_ok_to_delete_section' => $this->l('Press OK to delete section, Cancel to leave'),
                'drag_drop_me_in_column' => $this->l('Drag and drop me in the column'),
                'press_ok_to_delete_tab' => $this->l('Press OK to delete "{tab_name}" tab, Cancel to leave'),
                'slide' => $this->l('Slide'),
                'tab' => $this->l('Tab'),
                'section' => $this->l('Section'),
                'please_enter_new_tab_title' => $this->l('Please enter new tab title'),
                'press_ok_delete_section' => $this->l('Press OK to delete "{tab_name}" section, Cancel to leave'),
                'section_default_title' => $this->l('Section'),
                'please_enter_section_title' => $this->l('Please enter new section title'),
                'error_please_try_again' => $this->l('Error. Please try again.'),
                'if_close_data_lost' => $this->l('If you close this window all shortcode settings will be lost. Close this window?'),
                'header_select_element_type' => $this->l('Select element type'),
                'header_media_gallery' => $this->l('Media gallery'),
                'header_element_settings' => $this->l('Element settings'),
                'add_tab' => $this->l('Add tab'),
                'are_you_sure_convert_to_new_version' => $this->l('Are you sure you want to convert to new version?'),
                'loading' => $this->l('Loading...'),
                'set_image' => $this->l('Set Image'),
                'are_you_sure_reset_css_classes' => $this->l('Are you sure that you want to remove all your data?'),
                'loop_frame_title' => $this->l('Loop settings'),
                'enter_custom_layout' => $this->l('Enter custom layout for your row:'),
                'wrong_cells_layout' => $this->l('Wrong row layout format! Example: 1/2 + 1/2 or span6 + span6.'),
                'row_background_color' => $this->l('Row background color'),
                'row_background_image' => $this->l('Row background image'),
                'column_background_color' => $this->l('Column background color'),
                'column_background_image' => $this->l('Column background image'),
                'guides_on' => $this->l('Guides ON'),
                'guides_off' => $this->l('Guides OFF'),
                'template_save' => $this->l('New template successfully saved!'),
                'template_added' => $this->l('Template added to the page.'),
                'template_is_empty' => $this->l('Nothing to save. Template is empty.'),
                'css_updated' => $this->l('Page settings updated!'),
                'update_all' => $this->l('Update all'),
                'confirm_to_leave' => $this->l('The changes you made will be lost if you navigate away from this page.'),
                'inline_element_saved' => $this->l('%s saved!'),
                'inline_element_deleted' => $this->l('%s deleted!'),
                'inline_element_cloned' => $this->l('%s cloned. <a href="#" class="vc_edit-cloned" data-model-id="%s">Edit now?</a>'),
                'gfonts_loading_google_font_failed' => $this->l('Loading Google Font failed'),
                'gfonts_loading_google_font' => $this->l('Loading Font...'),
                'gfonts_unable_to_load_google_fonts' => $this->l('Unable to load Google Fonts'),
        ));
        self::$vc_translations['Read more'] = $this->l('Read more');
        self::$vc_translations['Permalink to %s'] = $this->l('Permalink to %s');
        
        require_once $this->path('HELPERS_DIR', 'helpers_factory.php');
        require_once $this->path('HELPERS_DIR', 'helpers.php');
        require_once $this->path('CORE_DIR', 'interfaces.php');
        require_once $this->path('CORE_DIR', 'class-wpb-map.php');
        require_once $this->path('HELPERS_DIR', 'helpers_api.php');
        require_once $this->path('HELPERS_DIR', 'filters.php');
        require_once $this->path('PARAMS_DIR', 'params.php');
        require_once $this->path('SHORTCODES_DIR', 'shortcodes.php');
        if(!$this->is_admin()){
            $this->cachedHookContent();
        }
    }
    public function path($name, $file = '')
    {
        return $this->vc_path_dir($name, $file);
    }

    public function vc_path_dir($name, $file = '')
    {
        return $this->composer_settings[$name] . '/' . $file;
    }
    public function is_admin()
    {
        if (isset($this->context->controller->admin_webpath) && !empty($this->context->controller->admin_webpath))
            return true;

        return false;
    }

    public function cachedHookContent()
    {

     if (Module::isInstalled('jscomposer') && Module::isEnabled('jscomposer')) {

   
        $sql = ' SELECT  `hook_name`, count(`id_vccontentanywhere`) as contentCount   FROM `' . _DB_PREFIX_ . 'vccontentanywhere` GROUP BY `hook_name` ';

        $results = Db::getInstance()->executeS($sql);

        foreach ($results as $key => $value) {

            if (isset($value['hook_name']) && !empty($value['hook_name'])) {
                $hook_retro_name = Hook::getRetroHookName($value['hook_name']);

                $this->hook_content[$hook_retro_name] = $value['contentCount'];
            }
            $this->hook_content[$value['hook_name']] = $value['contentCount'];
        }
        }
    }
    public function install()
    {
        if (!parent::install() || !$this->registerHook('displayBackOfficeHeader') 
                || !$this->registerHook('displayBackOfficeFooter') 
                || !$this->registerHook('displayBackOfficeTop') 
                || !$this->registerHook('vcBeforeInit') 
                || !$this->registerHook('VcShortcodesCssClass') 
                || !$this->registerHook('displayAdminProductsExtra') 
                || !$this->registerHook('displayHeader') 
                || !$this->registerHook('displayHome') 
                || !$this->registerHook('displayFooter') 
                || !$this->registerHook('displayTop') 
                || !$this->registerHook('displayLeftColumn') 
                || !$this->registerHook('displayRightColumn') 
                || !$this->registerHook('displaySmartBlogLeft') 
                || !$this->registerHook('displaySidearea') 
                || !$this->registerHook('displaySmartBlogRight') 
                || !$this->registerHook('displayFooterProduct') 
                || !$this->registerHook('displayMaintenance') 
                || !$this->registerHook('actionObjectvccontentanywhereAddAfter') 
                || !$this->registerHook('actionObjectvccontentanywhereUpdateAfter') 
                || !$this->registerHook('actionObjectvccontentanywhereDeleteAfter') 
                || !$this->registerHook('actionObjectvcproducttabcreatorAddAfter') 
                || !$this->registerHook('actionObjectvcproducttabcreatorUpdateAfter') 
                || !$this->registerHook('actionObjectvcproducttabcreatorDeleteAfter') 
                || !$this->registerHook('actionAdminPerformanceControllerAfter') 
                || !$this->registerHook('actionvccontentanywhereUpdate') 
                || !$this->registerHook('actionvcproducttabcreatorUpdate') 
                || !$this->registerHook('VcAllowedImgAttrs')
                || !$this->insertTable()
                || !$this->installTpls()
                || !$this->moduleControllerRegistration()
        ){
            return false;
        }
        Configuration::updateValue('vc_load_flex_js', 'yes');
        Configuration::updateValue('vc_load_flex_css', 'yes');
        Configuration::updateValue('vc_load_nivo_js', 'yes');
        Configuration::updateValue('vc_load_nivo_css', 'yes');
        return true;
    }
    public function uninstall()
    {
        return (parent::uninstall()
            && $this->dropTable()
            && $this->UninstallTpls()
            && $this->moduleControllerUnRegistration()
            );
    }
    public function insertTable()
    {
        $sql = array();
        require_once(dirname(__FILE__) . '/include/helpers/install_sql.php');
        if (is_array($sql) && !empty($sql))
            foreach ($sql as $sq) :
                if (!Db::getInstance()->Execute($sq))
                    return false;
            endforeach;
        return true;
    }
    public function installTpls()
    {
        $list_dir = _PS_OVERRIDE_DIR_ . 'controllers/front/listing/';
        if (!is_dir($list_dir)) {
            @mkdir($list_dir . $fn, 0777);
        }
        
        // END TINYMCE EDITOR
        $dir = _PS_MODULE_DIR_ . 'jscomposer/override/controllers/admin/templates/';
        $dstdir = _PS_OVERRIDE_DIR_ . 'controllers/admin/templates/';
        if (!is_dir($dstdir))
            @mkdir($dstdir, 0777);
        if (is_dir($dstdir)) {
            $folder = opendir($dir);
            while (false !== ($folders = readdir($folder))) {
                $fn = $folders;
                if ($fn !== '.' && $fn !== '..') {
                    if (is_dir($dir . $fn)) {
                        $folder2 = opendir($dir . $fn);
                        while (($file = readdir($folder2)) !== false) {
                            if ($file !== '.' && $file !== '..') {
                                @mkdir($dstdir . $fn, 0777);
                                $dstfile = $dstdir . $fn . '/' . $file;
                                Tools::copy($dir . $fn . '/' . $file, $dstfile);
                            }
                        }
                    } else {
                        $dstfile = $dstdir . $fn;
                        Tools::copy($dir . $fn, $dstfile);
                    }
                }
            }
        }
        return true;
    }
    public function moduleControllerRegistration()
    {
        $configuration = array(
            'cmscontent' => array(
                'type' => 'core',
                'shortname' => '',
                'controller' => 'AdminCmsContent',
                'context_controller' => 'cms',
                'dbtable' => 'cms',
                'identifier' => 'id_cms',
                'field' => 'content',
                'module_status' => 1,
                'module_frontend_status' => 1,
                'module_backend_status' => 1,
                'module_frontend_enable' => 1
            ),
            'categories' => array(
                'type' => 'core',
                'shortname' => '',
                'controller' => 'AdminCategories',
                'context_controller' => 'category',
                'dbtable' => 'category',
                'identifier' => 'id_category',
                'field' => 'description',
                'module_status' => 1,
                'module_frontend_status' => 1,
                'module_backend_status' => 1,
                'module_frontend_enable' => 1
            ),
            // 'products' => array(
            //     'type' => 'core',
            //     'shortname' => '',
            //     'controller' => 'AdminProducts',
            //     'context_controller' => 'product',
            //     'dbtable' => 'product',
            //     'identifier' => 'id_product',
            //     'field' => 'description',
            //     'module_status' => 1,
            //     'module_frontend_status' => 1,
            //     'module_backend_status' => 1,
            //     'module_frontend_enable' => 1
            // ),
            'products' => array(
                'type' => 'core',
                'shortname' => '',
                'controller' => 'AdminProducts',
                'context_controller' => 'product',
                'dbtable' => 'product',
                'identifier' => 'id_product',
                'field' => 'description',
                'module_status' => 1,
                'module_frontend_status' => 0,
                'module_backend_status' => 1,
                'module_frontend_enable' => 0
            ),
            'manufacturers' => array(
                'type' => 'core',
                'shortname' => '',
                'controller' => 'AdminManufacturers',
                'context_controller' => 'manufacturer',
                'dbtable' => 'manufacturer',
                'identifier' => 'id_manufacturer',
                'field' => 'description',
                'module_status' => 1,
                'module_frontend_status' => 1,
                'module_backend_status' => 1,
                'module_frontend_enable' => 1
            ),
            'suppliers' => array(
                'type' => 'core',
                'shortname' => '',
                'controller' => 'AdminSuppliers',
                'context_controller' => 'suppliers',
                'dbtable' => 'supplier',
                'identifier' => 'id_supplier',
                'field' => 'description',
                'module_status' => 1,
                'module_frontend_status' => 1,
                'module_backend_status' => 1,
                'module_frontend_enable' => 1
            ),
            'vccontentanywhere' => array(
                'type' => 'core',
                'shortname' => '',
                'controller' => 'Adminvccontentanywhere',
                'dbtable' => 'vccontentanywhere',
                'identifier' => 'id_vccontentanywhere',
                'field' => 'content',
                'module_status' => 1,
                'module_frontend_status' => 0,
                'module_backend_status' => 1,
                'module_frontend_enable' => 0
            )
        );

        Configuration::updateValue('VC_ENQUEUED_CONTROLLERS', Tools::jsonEncode($configuration));


        $langs = Language::getLanguages();
        $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $vctabobj = new Tab();
        $vctabobj->class_name = "Adminjscomposer";
        $vctabobj->module = "";
        $vctabobj->id_parent = 0;
        $vctabobj->active = 1;
        foreach ($langs as $l) {
            $vctabobj->name[$l['id_lang']] = $this->l('Visual Composer');
        }
        $vctabobj->save();
        $tab_id = $vctabobj->id;
        require_once(dirname(__FILE__) . '/include/helpers/install_tab.php');
        foreach ($tabvalue as $tab):
            $newtab = new Tab();
            $newtab->class_name = $tab['class_name'];
            if ($tab['id_parent'] == 'parent') {
                $newtab->id_parent = $tab_id;
            } else {
                $newtab->id_parent = $tab['id_parent'];
            }
            $newtab->active = $tab['active'];
            $newtab->module = $tab['module'];
            foreach ($langs as $l) {
                $newtab->name[$l['id_lang']] = $this->l($tab['name']);
            }
            $newtab->save();
        endforeach;
        return true;
    }

    public function dropTable()
    {
        $sql = array();
        require_once(dirname(__FILE__) . '/include/helpers/uninstall_sql.php');
        if (is_array($sql) && !empty($sql))
            foreach ($sql as $s) :
                if (!Db::getInstance()->Execute($s))
                    return false;
            endforeach;
        return true;
    }
    public function UninstallTpls()
    {
        $dirs[] = _PS_OVERRIDE_DIR_ . 'controllers/admin/templates/';

        $files[] = _PS_OVERRIDE_DIR_ . 'controllers/admin/AdminCmsController.php';
        $files[] = _PS_OVERRIDE_DIR_ . 'controllers/front/CmsController.php';
        if (is_array($dirs) && !empty($dirs)) {
            foreach ($dirs as $dir) {
                Tools::deleteDirectory($dir);
            }
        }
        if (is_array($files) && !empty($files)) {
            foreach ($files as $file) {
                Tools::deleteFile($file);
            }
        }
        return true;
    }
    public function moduleControllerUnRegistration()
    {
        Configuration::deleteByName('VC_ENQUEUED_CONTROLLERS');
        require_once(dirname(__FILE__) . '/include/helpers/install_tab.php');
        foreach ($tabvalue as $tab):
            $tabid = Tab::getIdFromClassName($tab['class_name']);
            if ($tabid) {
                $tab = new Tab($tabid);
                $tab->delete();
            }
        endforeach;
        $tabmid = Tab::getIdFromClassName("Adminjscomposer");
        if ($tabmid) {
            $tabm = new Tab($tabmid);
            $tabm->delete();
        }
        return true;
    }
    public function getWidgetVariables($hookName, array $configuration)
    {
        // nothing to do...
    }
    public function renderWidget($hookName, array $configuration)
    {
        return $this->contenthookvalue($hookName);
    }
    public function contenthookvalue($hook = '')
    {

        if (!$this->vcHookContentCount($hook))
            return false;

        $context = $this->context;
        $page = $context->controller->php_self;
        if (!is_object($this->vccawobj)) {
            $this->vccawobj = vccontentanywhere::GetInstance();
        }
        $vcaw = $this->vccawobj;

        $id_page_value = '';

        if ($id_cms = Tools::getValue('id_cms')) {
            $id_page_value = $id_cms;
        } else if ($id_category = Tools::getValue('id_category')) {
            $id_page_value = $id_category;
        } else if ($id_product = Tools::getValue('id_product')) {
            $id_page_value = $id_product;
        }
        $cacheId = 'vccc' . $page . $hook . $id_page_value;
        if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $cacheId)) {
            $results = $vcaw->GetVcContentAnyWhereByHookPageFilter($hook, $page, $id_page_value);
            $this->smarty->assign(array(
                'results' => $results
            ));
        }
        return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $cacheId);
    }
    public function vcHookContentCount($hook)
    {

        if (isset($this->hook_content[$hook]) && ($this->hook_content[$hook] > 0))
            return true;
        // once we made function to calculate the hook then it will use
    }

    public function l($string, $specific = false, $locale = null)
    {
        return htmlspecialchars_decode(parent::l($string, $specific, $locale));
    }

    public static function getJsControllerValues($table_name,$failed_name,$identifier,$val_identifier,$id_lang){
            $db = Db::getInstance();
            $db_results = $db->getRow("SELECT * FROM "._DB_PREFIX_."{$table_name}_lang WHERE {$identifier}={$val_identifier} AND id_lang = " . $id_lang, true, false);
            $tmp = $db_results[$failed_name];
            unset($db_results[$failed_name]);
            $db_results[$failed_name][$id_lang] = $tmp;

            return (object) $db_results;
    }

    public static function getModulesConfiguration(){
        $setModulesConfiguration = Tools::jsonDecode(Configuration::get('VC_ENQUEUED_CONTROLLERS'));
        return $setModulesConfiguration;
    }

    public function isLoadJsComposer($loade_for = false){
        $current_controller = Tools::getValue('controller');
        if ($current_controller == 'VC_frontend') {
            $return_url = Tools::getValue('return_url');
            $return_url_array = @unserialize(urldecode($return_url));

            $current_controller = $return_url_array['controller'];
        }

        $modules_configuration = JsComposer::getModulesConfiguration();

        $modules_configuration_found = false;
        if( is_object($modules_configuration)){
            foreach ($modules_configuration as $key => $value) {
                if($value->controller == $current_controller){
                    if(isset($value->module_status)){
                        if($value->module_status == 0){
                            return false;
                        }
                        elseif ($loade_for == 'frontend' && $value->module_frontend_status == 0){
                            return false;
                        }
                        elseif ($loade_for == 'backend' && $value->module_backend_status == 0){
                            return false;
                        } elseif ($value->module_frontend_status == 0 && $value->module_backend_status == 0) {
                            return false;
                        }
                    }
                    $modules_configuration_found = true;
                }
            }
        }

        return $modules_configuration_found ? true : false;
    }

    public function getcontent()
    {
        $url = Context::getContext()->link->getAdminLink('AdminJsComposerSetting');
        Tools::redirectAdmin($url);
    }

    private function addCSS($url)
    {
        if (is_array($url)) {
            if (!empty($url))
                foreach ($url as $urlcss) {
                    echo '<link href="' . $urlcss . '" rel="stylesheet" type="text/css" media="all" />' . "\r\n";
                }
        } else {
            echo '<link href="' . $url . '" rel="stylesheet" type="text/css" media="all" />' . "\r\n";
        }
    }

    private function addJS($url)
    {
        if (is_array($url)) {
            if (!empty($url))
                foreach ($url as $urljs) {
                    echo '<script type="text/javascript" src="' . $urljs . '"></script>' . "\r\n";
                }
        } else {
            echo '<script type="text/javascript" src="' . $url . '"></script>' . "\r\n";
        }
    }
    public function hookDisplayAdminProductsExtra($params)
    {
        if (self::condition()) {
            $post_id = $page_type = '';
            switch (Tools::getValue('controller')) {
                case 'AdminCmsContent':
                case 'VC_frontend':
                    $post_id = Tools::getValue('id_cms');
                    $page_type = 'cms';
                    break;
            }
            $languages = Language::getLanguages();
            foreach ($languages as $lang) {
                $optname = "_wpb_{$page_type}_{$post_id}_{$lang['id_lang']}_css";
                $this->post_custom_css["{$lang['id_lang']}"] = Configuration::get($optname);
            }
            ob_start();
            $this->vc_include_template('editors/backend_editor.tpl.php', array(
                'editor' => $this,
            ));
            if(Tools::version_compare(_PS_VERSION_, '1.7.3.0', '<')){
                $this->vc_include_template('editors/partials/include.js.php', array());
            }
            $content = ob_get_clean();
            $content .= $this->renderEditorFooter();
            return $content;
        }
    }


    private function setCustomControllersCondition()
    {
        self::$isVcAdminCustomController = false;
        $content = '';
        $controllers = Configuration::get('VC_ENQUEUED_CONTROLLERS');
        if (!empty($controllers)) {
            $controllers = Tools::jsonDecode($controllers, true);
            foreach ($controllers as $id => $controller) {
//                $add = 'add' . $id;
//                $update = 'update' . $id;
                $add = 'add' . $controller['dbtable'];
                $update = 'update' . $controller['dbtable'];
                if (Tools::getValue('controller') == $controller['controller'] && (Tools::isSubmit($add) || Tools::isSubmit($update))) {
                    self::$isVcAdminCustomController = true;
                    $content .= "var page_id = " . (Tools::getValue($controller['identifier']) ? Tools::getValue($controller['identifier']) : "null") . ";\n";
                    $content .= "var page_type = '{$controller['shortname']}';";
                    self::$vcCustomPageType = $controller['shortname'];
                    self::$vcCustomPageId = Tools::getValue($controller['identifier']);
                    $this->contentBoxNamesByController[$controller['controller']] = $controller['field'];
                    break;
                }
            }
        }
        if (!self::$isVcAdminCustomController) {

            if (Tools::getValue('controller') == 'AdminBlogPost') {
                $content .= "var page_id = " . (Tools::getValue('id_smart_blog_post') ? Tools::getValue('id_smart_blog_post') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'AdminProducts') {
                $content .= "var page_id = " . (Tools::getValue('id_product') ? Tools::getValue('id_product') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'AdminSuppliers') {
                $content .= "var page_id = " . (Tools::getValue('id_supplier') ? Tools::getValue('id_supplier') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'AdminManufacturers') {
                $content .= "var page_id = " . (Tools::getValue('id_manufacturer') ? Tools::getValue('id_manufacturer') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'AdminCategories') {
                $content .= "var page_id = " . (Tools::getValue('id_category') ? Tools::getValue('id_category') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'AdminCmsContent') {
                $content .= "var page_id = " . (Tools::getValue('id_cms') ? Tools::getValue('id_cms') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'Adminvccontentanywhere') {
                $content .= "var page_id = " . (Tools::getValue('id_vccontentanywhere') ? Tools::getValue('id_vccontentanywhere') : "null") . ";\n";
            } elseif (Tools::getValue('controller') == 'Adminvcproducttabcreator') {
                $content .= "var page_id = " . (Tools::getValue('id_vcproducttabcreator') ? Tools::getValue('id_vcproducttabcreator') : "null") . ";\n";
            } else {
                $content .= "var page_id = " . (Tools::getValue('id_cms') ? Tools::getValue('id_cms') : "null") . ";\n";
            }
            switch (Tools::getValue('controller')) {
                case 'AdminBlogPost':
                    $content .= "var page_type = 'smartblog';\n";
                    break;
                case 'AdminProducts':
                    $content .= "var page_type = 'prd';\n";
                    break;
                case 'AdminSuppliers':
                    $content .= "var page_type = 'sup';\n";
                    break;
                case 'AdminManufacturers':
                    $content .= "var page_type = 'man';\n";
                    break;
                case 'AdminCategories':
                    $content .= "var page_type = 'cat';\n";
                    break;
                case 'AdminCmsContent':
                    $content .= "var page_type = 'cms';\n";
                    break;
                case 'VC_frontend':
                    $content .= "var page_type = 'cms';\n";
                    break;
                case 'Adminvccontentanywhere':
                    $content .= "var page_type = 'vccaw';\n";
                    break;
                case 'Adminvcproducttabcreator':
                    $content .= "var page_type = 'vctc';\n";
                    break;

            }
        }
        self::$vcBackofficePageIndenfiers = $content;
    }

    public static function condition()
    {
        $modules_configuration = JsComposer::getModulesConfiguration();

        $controller = Tools::getValue('controller');

        $module_type = '';
        $module_controller = '';
        $module_table = '';
        $module_identifier = '';
        $module_field = '';
        $module_status = '';
        $module_frontend_status = '';
        $module_backend_status = '';

        $id_product = false;

        if(isset($_SERVER['PATH_INFO'])){
            $path_info = pathinfo($_SERVER['PATH_INFO']);
            if(isset($path_info['filename'])) $id_product = $path_info['filename'];
        }


        $current_url = array();
        foreach($_GET as $key => $value){
            $ck_process_type_add = substr($key, 0, 3);// add 3
            $ck_process_type_update = substr($key, 0, 6);// update 6
            if(((($ck_process_type_add == 'add' || $ck_process_type_update == 'update') && $value == '') || ($id_product && $controller == 'AdminProducts')) && is_object($modules_configuration)){

                foreach ($modules_configuration as $key => $value) {
                    if($value->controller == $controller){
                        return true;
                    }
                }
            }
        }

        if($controller == 'VC_frontend') return true;

        // return
        //     (Tools::getValue('controller') == 'AdminBlogPost' && (Tools::isSubmit('addsmart_blog_post') || Tools::isSubmit('updatesmart_blog_post'))) // smartblog module
        //     || (Tools::getValue('controller') == 'AdminProducts' && !Tools::isSubmit('ajax') && (Tools::isSubmit('addproduct') || (Tools::isSubmit('updateproduct') || Tools::getValue('id_product')))) || (Tools::getValue('controller') == 'AdminSuppliers' && (Tools::isSubmit('addsupplier') || Tools::isSubmit('updatesupplier'))) || (Tools::getValue('controller') == 'AdminManufacturers' && (Tools::isSubmit('addmanufacturer') || Tools::isSubmit('updatemanufacturer'))) || (Tools::getValue('controller') == 'AdminCategories' && (Tools::isSubmit('addcategory') || Tools::isSubmit('updatecategory'))) || (Tools::getValue('controller') == 'AdminCmsContent' && (Tools::isSubmit('addcms') || Tools::isSubmit('updatecms'))) || (Tools::getValue('controller') == 'VC_frontend' && Tools::getValue('vc_action') == 'vc_inline' && Tools::getValue('id_cms')) || (Tools::getValue('controller') == 'Adminvccontentanywhere' && (Tools::isSubmit('addvccontentanywhere') || Tools::isSubmit('updatevccontentanywhere'))) || (Tools::getValue('controller') == 'Adminvcproducttabcreator' && (Tools::isSubmit('addvcproducttabcreator') || Tools::isSubmit('updatevcproducttabcreator')))
        //     || self::$isVcAdminCustomController
        // ;
    }

    public static function getCurrentContent(){
        $frontend_module_name = Tools::getValue('frontend_module_name');
        $frontend_module_type = Tools::getValue('frontend_module_type');
        $val_identifier = Tools::getValue('val_identifier');
        $content = '';
        switch ($frontend_module_name) {
            case 'Adminvccontentanywhere':
                $result = new vccontentanywhere($val_identifier);
                $content = $result->content[Tools::getValue('id_lang')];
                break;
            case 'AdminCategories':
                $result = new Category($val_identifier);
                $content = $result->description[Tools::getValue('id_lang')];
                break;
            case 'AdminManufacturers':
                $result = new Manufacturer($val_identifier);
                $content = $result->description[Tools::getValue('id_lang')];
                break;
            case 'AdminCmsContent':
                $result = new CMS($val_identifier);
                $content = $result->content[Tools::getValue('id_lang')];
                break;
            
            default:
                $modules_configuration = JsComposer::getModulesConfiguration();

                foreach ($modules_configuration as $key => $value) {
                    if(isset($value->controller)){
                        if($value->controller == $frontend_module_name){
                            $tmp_data = (array) self::getJsControllerValues($value->dbtable,$value->field,$value->identifier,$val_identifier,Tools::getValue('id_lang'));
                            $content = $tmp_data[$value->field][Tools::getValue('id_lang')];
                        }
                    }
                }
                break;
        }
        return $content;
    }

    public function loadVcFrontendActionScripts()
    {
        $admin_theme = __PS_BASE_URI__ . $this->context->controller->admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/css/' . $this->context->employee->bo_css;
        $admin_theme_override = __PS_BASE_URI__ . $this->context->controller->admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/css/overrides.css';
        echo "<!-- css files -->\n";
        echo "<style type='text/css'> iframe#vc_inline-frame{border:none;} body.vc_frontend.page-topbar #main{padding-top:0 !important;}  body.vc_frontend #main .bootstrap, body.vc_frontend > #header, body.vc_frontend > #footer{display:none;} body.vc_frontend #content.nobootstrap, body.vc_frontend > #main{margin:0; padding:0; min-width:0;}</style>\n";
        $this->context->controller->addCSS($admin_theme);
        $this->context->controller->addCSS($admin_theme_override);
        $id_lang = (int)Tools::getValue('id_lang');
        $lang = new Language($id_lang);
        Media::addJsDef(array(
            'ad' => __PS_BASE_URI__.$this->context->controller->admin_webpath,
            'iso' => $lang->iso_code
        ));
        if(Tools::version_compare(_PS_VERSION_, '1.6.0.11', '<')){
            echo '<script type="text/javascript">
                var ad = "'.__PS_BASE_URI__.$this->context->controller->admin_webpath.'";
                var iso = "'.$lang->iso_code.'";
            </script>'."\n";
        }
        $this->context->controller->addJS(__PS_BASE_URI__ . $this->context->controller->admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/js/vendor/bootstrap.min.js');
        $this->context->controller->addJS(__PS_BASE_URI__ . $this->context->controller->admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/js/vendor/modernizr.min.js');
        $this->context->controller->addJS(__PS_BASE_URI__ . $this->context->controller->admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/js/modernizr-loads.js');
        $this->context->controller->addJS(__PS_BASE_URI__ . $this->context->controller->admin_webpath . '/themes/' . $this->context->employee->bo_theme . '/js/vendor/moment-with-langs.min.js');
        $this->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
        if (Tools::version_compare(_PS_VERSION_, '1.6.0.9', '<='))
            $this->addJS(_PS_JS_DIR_ . 'tinymce.inc.js');
        else
            $this->addJS(_PS_JS_DIR_ . 'admin/tinymce.inc.js');
    }

    public function isUpdateCmsPage()
    {
        return Tools::getValue('controller') === 'AdminCmsContent' && Tools::getValue('updatecms') !== FALSE && Tools::getValue('id_cms');
    }

    public function updateCSSValues()
    {
        $post_id = Tools::getValue('post_id');
        $id_lang = Tools::getValue('id_lang');
        $css = Tools::getValue('css');
        $type = Tools::getValue('type');
        $optionname = "_wpb_{$type}_{$post_id}_{$id_lang}_css";
        Configuration::updateValue($optionname, $css, true);
        die();
    }

    public static function getInstance()
    {
        return Module::getInstanceByName('jscomposer');
    }

    public static function VcGetLinkobj()
    {
//        $ret = array();
//      if(Tools::usingSecureMode())
//       $useSSL = true;
//      else
//       $useSSL = false;
//      $protocol_link = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 'https://' : 'http://';
//      $protocol_content = (isset($useSSL) AND $useSSL AND Configuration::get('PS_SSL_ENABLED') AND Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 'https://' : 'http://';
//      $link = new Link($protocol_link, $protocol_content);
//      $ret['protocol_link'] = $protocol_link;
//      $ret['protocol_content'] = $protocol_content;
//      $ret['obj'] = $link;
        return $ret = '//';
    }

    public static function ModifyImageUrl($img_src = '')
    {
        // /* JsComposer::ModifyImageUrl(); */
        $httpprefix = self::VcGetLinkobj();
        $img_pathinfo = pathinfo($img_src);
        $mainstr = $img_pathinfo['basename'];
        $static_url = $img_pathinfo['dirname'] . '/' . $mainstr;
//        $static_url = __PS_BASE_URI__.'modules/jscomposer/uploads/'.$mainstr;
        return $httpprefix . Tools::getMediaServer($static_url) . $static_url;
        // return $img_src;
    }

    public static function remove_shortcode($tag)
    {
        unset(self::$static_shortcode_tags[$tag]);
    }

    public static function add_shortcode($tag, $func)
    {
        self::$static_shortcode_tags[$tag] = $func;
    }

    public static function vc_remove_element($tag)
    {
        vc_remove_element($tag);
    }

    public static function add_shortcode_param($name, $form_field_callback, $script_url = null)
    {
        $path = _PS_MODULE_DIR_ . 'jscomposer/include/params/params.php';
        if (file_exists($path)) {
            require_once($path);
            return WpbakeryShortcodeParams::addField($name, $form_field_callback, $script_url);
        }
    }

    public static function getModuleEditorConfiguration($controller,$config_name){
        $modules_configuration = JsComposer::getModulesConfiguration();
        foreach ($modules_configuration as $key => $value) {
            $arr_value = (array) $value;
            if(isset($value->controller)){
                if($value->controller == $controller){
                    return $arr_value[$config_name];
                }
            }
        }
    }

    public static function getVcInlineTag($content){
        $vc_inline_tag = '';
        $controller_name = Tools::getValue('frontend_module_name');
        $module_frontend_status = JsComposer::getModuleEditorConfiguration($controller_name,'module_frontend_status');
        if($module_frontend_status) $vc_inline_tag = '<span id="vc_inline-anchor" style="display:none !important;"></span>';
        switch ($controller_name) {
            case 'Adminvccontentanywhere':
                $Smartlisence = new Smartlisence();
                $module_active_status = $Smartlisence->isActive();
                if($module_active_status AND $module_frontend_status) $vc_inline_tag = '<span id="vc_inline-anchor" style="display:none !important;"></span>';
                else $vc_inline_tag = '';
                break;
            case 'AdminCategories':

                break;
            case 'AdminManufacturers':

                break;
            case 'AdminCmsContent':

                break;

            default:
                $Smartlisence = new Smartlisence();
                $module_active_status = $Smartlisence->isActive();
                if($module_active_status AND $module_frontend_status) $vc_inline_tag = '<span id="vc_inline-anchor" style="display:none !important;"></span>';
                else $vc_inline_tag = '';
                break;
        }
        return $vc_inline_tag.$content;
    }

    public static function do_shortcode($content, $hook_name = '')
    {
        if(!vc_manager()->moduleFrontendEnable()) return $content;
        $shortcode_tags = self::$static_shortcode_tags;
        if (empty($shortcode_tags) || !is_array($shortcode_tags))
            return $content;
        $pattern = vc_manager()->get_shortcode_regex();
        self::$sds_current_hook = $hook_name;
        return preg_replace_callback("/$pattern/s", array(__CLASS__, 'do_shortcode_tag'), $content);
    }

    public function moduleFrontendEnable(){
        if(Tools::getValue('controller') == 'VC_frontend')
            return true;

        $modules_configuration = JsComposer::getModulesConfiguration();

        $current_context_controller = (isset($this->context->controller->php_self)) ? $this->context->controller->php_self : '';

        $modules_configuration = JsComposer::getModulesConfiguration();

        foreach ($modules_configuration as $key => $value) {
            if(isset($value->context_controller)){
                if($value->context_controller == $current_context_controller && $value->type == 'core'){
                    $module_frontend_enable = (isset($value->module_frontend_enable)) ? $value->module_frontend_enable : 0;
                    if($module_frontend_enable == 0) return false;
                }
            }
        }

        return true;
    }

    public static function do_shortcode_tag($m)
    {
        $Vc_Base = new Vc_Base();
        $shortcode_tags = self::$static_shortcode_tags;
        if ($m[1] == '[' && $m[6] == ']') {
            return Tools::substr($m[0], 1, -1);
        }
        $tag = $m[2];
        $attr = self::shortcode_parse_atts($m[3]);
        if (isset($m[5])) {
            return $m[1] . call_user_func($shortcode_tags[$tag], $attr, $m[5], $tag, self::$sds_current_hook) . $m[6];
        } else {

            return $m[1] . call_user_func($shortcode_tags[$tag], $attr, null, $tag, self::$sds_current_hook) . $m[6];
        }
    }

    public function get_shortcode_regex()
    {
        $shortcode_tags = self::$static_shortcode_tags;
        $tagnames = array_keys($shortcode_tags);
        $tagregexp = join('|', array_map('preg_quote', $tagnames));
        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*'               // Not a closing bracket or forward slash
            . ')*?'
            . ')'
            . '(?:'
            . '(\\/)'                        // 4: Self closing tag ...
            . '\\]'                          // ... and closing bracket
            . '|'
            . '\\]'                          // Closing bracket
            . '(?:'
            . '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+'             // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+'         // Not an opening bracket
            . ')*+'
            . ')'
            . '\\[\\/\\2\\]'             // Closing shortcode tag
            . ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }

    public static function shortcode_parse_atts($text)
    {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) and strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
            }
        }else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    public static function admin_shortcode_atts($pairs, $atts, $shortcode = '')
    {
        $out = self::shortcode_atts($pairs, $atts, $shortcode);
        if (isset($atts['content'])) {
            $out['content'] = $atts['content'];
        }
        return $out;
    }

    public static function shortcode_atts($pairs, $atts, $shortcode = '')
    {
        $atts = (array) $atts;

        $out = array();
        foreach ($pairs as $name => $default) {
            if (array_key_exists($name, $atts))
                $out[$name] = $atts[$name];
            else
                $out[$name] = $default;
        }
        return $out;
    }

    public static function strip_shortcodes($content)
    {
        $shortcode_tags = self::$static_shortcode_tags;
        if (empty($shortcode_tags) || !is_array($shortcode_tags))
            return $content;
        $pattern = vc_manager()->get_shortcode_regex();
        return preg_replace_callback("/$pattern/s", array(__CLASS__, 'strip_shortcode_tag'), $content);
    }

    public static function strip_shortcode_tag($m)
    {
        if ($m[1] == '[' && $m[6] == ']') {
            return Tools::substr($m[0], 1, -1);
        }
        return $m[1] . $m[6];
    }

    public static function wpautop($pee, $br = true)
    {
        $pre_tags = array();
        if (trim($pee) === '')
            return '';
        $pee = $pee . "\n";
        if (Tools::strpos($pee, '<pre') !== false) {
            $pee_parts = explode('</pre>', $pee);
            $last_pee = array_pop($pee_parts);
            $pee = '';
            $i = 0;
            foreach ($pee_parts as $pee_part) {
                $start = Tools::strpos($pee_part, '<pre');
                if ($start === false) {
                    $pee .= $pee_part;
                    continue;
                }
                $name = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = Tools::substr($pee_part, $start) . '</pre>';
                $pee .= Tools::substr($pee_part, 0, $start) . $name;
                $i++;
            }
            $pee .= $last_pee;
        }
        $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|details|menu|summary)';
        $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
        if (Tools::strpos($pee, '<option') !== false) {
            $pee = preg_replace('|\s*<option|', '<option', $pee);
            $pee = preg_replace('|</option>\s*|', '</option>', $pee);
        }
        if (Tools::strpos($pee, '</object>') !== false) {
            // no P/BR around param and embed
            $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
            $pee = preg_replace('|\s*</object>|', '</object>', $pee);
            $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
        }

        if (Tools::strpos($pee, '<source') !== false || Tools::strpos($pee, '<track') !== false) {
            // no P/BR around source and track
            $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
            $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
            $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
        }

        $pee = preg_replace("/\n\n+/", "\n\n", $pee);
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee = '';
        foreach ($pees as $tinkle) {
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }
        $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
        if ($br) {
            $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', array(__CLASS__, '_autop_newline_preservation_helper'), $pee);
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);
        if (!empty($pre_tags))
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);
        return $pee;
    }

    public static function _autop_newline_preservation_helper($matches)
    {
        return str_replace("\n", "<WPPreserveNewline />", $matches[0]);
    }

    public static function shortcode_unautop($pee)
    {
        $shortcode_tags = self::$static_shortcode_tags;
        if (empty($shortcode_tags) || !is_array($shortcode_tags)) {
            return $pee;
        }
        $tagregexp = join('|', array_map('preg_quote', array_keys($shortcode_tags)));
        $pattern = '/'
            . '<p>'                              // Opening paragraph
            . '\\s*+'                            // Optional leading whitespace
            . '('                                // 1: The shortcode
            . '\\['                          // Opening bracket
            . "($tagregexp)"                 // 2: Shortcode name
            . '(?![\\w-])'                   // Not followed by word character or hyphen
            // Unroll the loop: Inside the opening shortcode tag
            . '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            . '(?:'
            . '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            . '[^\\]\\/]*'               // Not a closing bracket or forward slash
            . ')*?'
            . '(?:'
            . '\\/\\]'                   // Self closing tag and closing bracket
            . '|'
            . '\\]'                      // Closing bracket
            . '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            . '[^\\[]*+'             // Not an opening bracket
            . '(?:'
            . '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            . '[^\\[]*+'         // Not an opening bracket
            . ')*+'
            . '\\[\\/\\2\\]'         // Closing shortcode tag
            . ')?'
            . ')'
            . ')'
            . '\\s*+'                            // optional trailing whitespace
            . '<\\/p>'                           // closing paragraph
            . '/s';

        return preg_replace($pattern, '$1', $pee);
    }

    public function init()
    {
        $this->vcallmod();
        $this->add_custom_param_code();
        Hook::exec('vcBeforeInit');
        $this->setMode();
        $this->setVersion();
//        visual_composer()->init();
        $this->vc()->init();
        $this->mapper()->init();
        $this->automapper()->map();
        $this->automapper()->addAjaxActions();
        $this->is_admin() && $this->asAdmin();
        vc_enabled_frontend() && vc_frontend_editor()->init();
    }



    protected function asAdmin()
    {
        $this->backendEditor()->addHooksSettings();
    }

    public function vc()
    {
        if (!isset($this->factory['vc'])) {
            require_once $this->path('CORE_DIR', 'class-vc-base.php');
            $vc = new Vc_Base();
            require_once $this->path('EDITORS_DIR', 'popups/class-vc-templates-editor.php');
            $vc->setTemplatesEditor(new Vc_Templates_Editor());
            require_once $this->path('EDITORS_DIR', 'popups/class-vc-shortcode-edit-form.php');
            $vc->setEditForm(new Vc_Shortcode_Edit_Form());
            require_once $this->path('VENDORS_DIR', 'class-vc-vendors-manager.php');
            $vc->setVendorsManager(new Vc_Vendors_Manager($this));
            $this->factory['vc'] = $vc;
        }
        return $this->factory['vc'];
    }

    public function vc_loop_include_templates()
    {
        require_once $this->vc_path_dir('TEMPLATES_DIR', 'params/loop/templates.html');
    }



    protected function setMode()
    {
        if ($this->is_admin()) {
            if ($this->vc_action() === 'vc_inline') {
                $this->mode = 'admin_frontend_editor';
            } else {
                $this->mode = 'admin_page';
            }
        } elseif (Tools::getValue('vc_editable') === 'true') {
            $this->mode = 'page_editable';
        }
    }

    public function mode()
    {
        return $this->mode;
    }

    public static function backToAdminLink()
    {

        $return_url = Tools::getValue('return_url');
        $return_url_array = @unserialize(urldecode($return_url));
        $return_url = '';
        foreach ($return_url_array AS $key => $value) {
            $return_url .= '&' . $key . '=' . $value;
        }
        $return_url = substr($return_url, 1);
        return strtok($_SERVER["REQUEST_URI"],'?').'?'.$return_url;
    }

    public static function getAdminUpdateCMSLink($params = array())
    {
        $link = Context::getContext()->link->getAdminLink('AdminCmsContent');
        if (!empty($params) && is_array($params)) {
            $params = http_build_query($params);
            $params = htmlspecialchars_decode($params);
            $link .= "&{$params}";
        }
        return $link;
    }

    public static function getCMSLink($id, $alias = null, $ssl = null, $id_lang = null, $id_shop = null)
    {
        $link = new Link;
        $cms = new CMS($id);
        return $link->getCMSLink($cms, $alias, $ssl, $id_lang, $id_shop);
    }

    protected function setVersion()
    {
        $version = Configuration::get('vc_version');
        if (!is_string($version) || version_compare($version, WPB_VC_VERSION) !== 0) {
            Configuration::updateValue('vc_version', WPB_VC_VERSION);
        }
    }

    public function backendEditor()
    {
        if (!isset($this->factory['backend_editor'])) {
            require_once $this->path('EDITORS_DIR', 'class-vc-backend-editor.php');
            $this->factory['backend_editor'] = new Vc_Backend_Editor();
        }
        return $this->factory['backend_editor'];
    }

    public function mapper()
    {
        if (!isset($this->factory['mapper'])) {
            require_once $this->path('CORE_DIR', 'class-vc-mapper.php');
            $this->factory['mapper'] = new Vc_Mapper();
        }
        return $this->factory['mapper'];
    }

    public function automapper()
    {
        if (!isset($this->factory['automapper'])) {
            require_once $this->path('SETTINGS_DIR', 'class-vc-automapper.php');
            $this->factory['automapper'] = new Vc_Automapper();
        }
        return $this->factory['automapper'];
    }

    public function updater()
    {
        if (!isset($this->factory['updater'])) {
            require_once $this->path('UPDATERS_DIR', 'class-vc-updater.php');
            $updater = new Vc_Updater();
            require_once $this->vc_path_dir('UPDATERS_DIR', 'class-vc-updating-manager.php');
            $updater->setUpdateManager(new Vc_Updating_Manager(WPB_VC_VERSION, $updater->versionUrl(), vc_plugin_name()));
            $this->factory['updater'] = $updater;
        }
        return $this->factory['updater'];
    }

    public function settings()
    {
        if (!isset($this->factory['settings'])) {
            require_once $this->path('SETTINGS_DIR', 'class-vc-settings.php');
            $this->factory['settings'] = new Vc_Settings();
        }
        return $this->factory['settings'];
    }

    public function vc_action()
    {
        if ($vc_action = Tools::getValue('vc_action'))
            return $vc_action;
        return null;
    }

    public function vc_post_param($param, $default = null)
    {
        return Tools::getValue($param) ? Tools::getValue($param) : $default;
    }

    public function addDefaultTemplates($data)
    {
        vc_add_default_templates($data);
    }

    public function loadDefaultTemplates()
    {
        return vc_load_default_templates();
    }



    public function vc_include_template($file, $args)
    {
        extract($args);
        require $this->vc_path_dir('TEMPLATES_DIR', $file);
    }

    public function assetUrl($file)
    {
        return ( $this->vc_asset_url($file));
    }

    public function vc_asset_url($url)
    {
        return $this->_path . 'assets/' . $url;
    }

    public function esc_attr_e($string, $textdomain = '')
    {
        echo $this->esc_attr($string);
    }

    public function esc_attr($string)
    {
        return Tools::htmlentitiesUTF8($string);
    }

    public function esc_attr__($string)
    {
        return $this->esc_attr($this->l($string));
    }

    public function lcfirst($str)
    {
        $str[0] = mb_strtolower($str[0]);
        return $str;
    }

    public function vc_studly($value)
    {
        $value = Tools::ucwords(str_replace(array('-', '_'), ' ', $value));
        return str_replace(' ', '', $value);
    }

    public function vc_camel_case($value)
    {
        return $this->lcfirst($this->vc_studly($value));
    }

    public function getControls()
    {
        $list = array();
        foreach ($this->controls as $control) {
            $method = $this->vc_camel_case('get_control_' . $control);
            if (method_exists($this, $method)) {
                $list[] = array($control, $this->$method() . "\n");
            }
        }
        return $list;
    }

    public function renderEditor($post = null)
    {
        $post_id = $page_type = '';
        switch (Tools::getValue('controller')) {
            case 'AdminCmsContent':
            case 'VC_frontend':
                $post_id = Tools::getValue('id_cms');
                $page_type = 'cms';
                break;
        }
        
        $languages = Language::getLanguages();
        foreach ($languages as $lang) {
            $optname = "_wpb_{$page_type}_{$post_id}_{$lang['id_lang']}_css";
            $this->post_custom_css["{$lang['id_lang']}"] = Configuration::get($optname);
        }
        ob_start();
        $this->vc_include_template('editors/backend_editor.tpl.php', array(
            'editor' => $this,
        ));
        $content = ob_get_clean();
        $content .= $this->renderEditorFooter();
        return $content;
    }

    public function renderEditorFooter()
    {
        ob_start();
        $this->init();
        $this->vc_include_template('editors/partials/backend_editor_footer.tpl.php', array(
            'editor' => $this,
        ));
        return ob_get_clean();
    }

    public function getLogo()
    {
        $output = '<a id="vc_logo" class="vc_navbar-brand" title="' . $this->esc_attr('Visual Composer', 'js_composer')
            . '" href="' . $this->esc_attr($this->brand_url) . '" target="_blank">'
            . $this->l('Visual Composer') . '</a>';
        return $output;
    }

    public function getControlCustomCss()
    {
        return '<li class="vc_pull-right"><a id="vc_post-settings-button" class="vc_icon-btn vc_post-settings" title="'
            . $this->esc_attr('Page settings', 'js_composer') . '">'
            . '<span id="vc_post-css-badge" class="vc_badge vc_badge-custom-css" style="display: none;">' . $this->l('CSS') . '</span></a>'
            . '</li>';
    }

    public function getControlAddElement()
    {
        return '<li class="vc_show-mobile">'
            . ' <a href="javascript:;" class="vc_icon-btn vc_element-button" data-model-id="vc_element" id="vc_add-new-element" title="'
            . '' . $this->l('Add new element') . '">'
            . ' </a>'
            . '</li>';
    }

    public function getControlTemplates()
    {
        return '<li><a href="javascript:;" class="vc_icon-btn vc_templates-button vc_navbar-border-right"  id="vc_templates-editor-button" title="'
            . $this->l('Templates') . '"></a></li>';
    }

    public function getControlFrontend()
    {
        if (!function_exists('vc_enabled_frontend'))
            return false;
        return '<li class="vc_pull-right">'
            . '<a href="' . vc_frontend_editor()->getInlineUrl() . '" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn" id="wpb-edit-inline">' . __('Frontend', "js_composer") . '</a>'
            . '</li>';
    }

    public function getControlPreview()
    {
        return '';
    }

    public function getControlSaveBackend()
    {
        return '<li class="vc_pull-right vc_save-backend">'
            . '<a href="javascript:;" class="vc_btn vc_btn-grey vc_btn-sm vc_navbar-btn vc_control-preview">' . $this->l('Preview') . '</a>'
            . '<a class="vc_btn vc_btn-sm vc_navbar-btn vc_btn-primary vc_control-save" id="wpb-save-post">' . $this->l('Update') . '</a>'
            . '</li>';
    }

    public function frontendEditor()
    {
        if (!isset($this->factory['frontend_editor'])) {
            require_once $this->path('EDITORS_DIR', 'class-vc-frontend-editor.php');
            $this->factory['frontend_editor'] = new Vc_Frontend_Editor();
        }
        return $this->factory['frontend_editor'];
    }

    public function setCustomUserShortcodesTemplateDir($dir)
    {
        preg_replace('/\/$/', '', $dir);
        $this->custom_user_templates_dir = $dir;
    }

    public function getDefaultShortcodesTemplatesDir()
    {
        return vc_path_dir('TEMPLATES_DIR', 'shortcodes');
    }

    public function getShortcodesTemplateDir($template)
    {
        return '';
    }

    public static function controller_upload_url($link = '')
    {
        $hash = vc_manager()->secure_key;

        //$url = '//' . Tools::getHttpHost(false) . __PS_BASE_URI__ . Context::getContext()->controller->admin_webpath . '/';
        $url = Context::getContext()->link->getAdminLink('VC_upload') . '&security_key=' . $hash;

        if ($link != '')
            $url = "{$url}&{$link}";

        return $url;
    }

    public static function getMediaUploaderUrl()
    {
        return '//' . Tools::getShopDomain(false) . _MODULE_DIR_ . 'jscomposer/views/';
    }

    public static function delete_uploaded_file()
    {

        require_once dirname(__FILE__) . '/views/lang/en.php';

        $db = Db::getInstance();

        $tablename = _DB_PREFIX_ . self::$VC_MEDIA;

        $imgdir = vc_manager()->composer_settings['UPLOADS_DIR'];

        $data = $_POST;

        if (!isset($data['img']))
            die('-1');

        $filename = $db->escape($data['img']);
        $subdir = $db->getValue("SELECT subdir FROM {$tablename} WHERE file_name='{$data['img']}'");

        if (file_exists("{$imgdir}{$subdir}{$data['img']}")) {

            $images = array($filename);

            $types = VcImageType::getImagesTypes();

            foreach ($images as $image) {
                if (!empty($types)) {
                    $filerealname = Tools::substr($image, 0, Tools::strrpos($image, '.'));
                    $ext = substr($image, Tools::strrpos($image, '.'));
                    foreach ($types as $imageType) {
                        $newfilename = "{$filerealname}-{$imageType['name']}";
                        if (file_exists("{$imgdir}{$newfilename}{$ext}")) {
                            Tools::deleteFile("{$imgdir}{$newfilename}{$ext}");
                        }
                        if (file_exists("{$imgdir}{$subdir}{$newfilename}{$ext}")) {
                            Tools::deleteFile("{$imgdir}{$subdir}{$newfilename}{$ext}");
                        }
                    }
                }
                Tools::deleteFile("{$imgdir}{$image}");
                if (file_exists("{$imgdir}{$subdir}{$image}")) {
                    Tools::deleteFile("{$imgdir}{$subdir}{$image}");
                }
            }
            if ($db->query("DELETE FROM {$tablename} WHERE file_name='{$filename}'"))
                echo Tools::jsonEncode(array(
                    'success' => '1',
                    'output' => self::get_uploaded_files_markup(self::get_uploaded_files_result(30, 0, $subdir))
                ));

            die();
        }
    }

    public static function get_uploaded_files_result($per_page = 20, $start = 0, $subdir = '')
    {

        $db = Db::getInstance();
        $tablename = _DB_PREFIX_ . self::$VC_MEDIA;


        if (!empty($subdir) && $subdir != '/') {
            $subdir = "subdir='{$subdir}'";
        } else {
            $subdir = "subdir IS NULL";
        }
        $sql = "SELECT * FROM {$tablename} WHERE {$subdir} ORDER BY id_vc_media DESC LIMIT {$start},{$per_page}";

        $db_results = $db->executeS($sql, true, false);

        $results = array();
        if (!empty($db_results)) {
            foreach ($db_results as $dres) {
                $dres = (object) $dres;

                if ($dres->subdir == '/')
                    $dres->subdir = '';

                if (isset($dres->file_name) && !empty($dres->file_name) && file_exists(vc_manager()->composer_settings['UPLOADS_DIR'] . $dres->subdir . $dres->file_name)
                ) {

                    $results["{$dres->id_vc_media}"] = $dres->file_name;
                }
            }
        }
        return $results;
    }

    public static function get_uploaded_files_markup($results = array(), $path = '')
    {
        $upload_dir = self::$_url . 'uploads/';
        $current_path = vc_manager()->composer_settings['UPLOADS_DIR'];
        if (!empty($path)) {
            $current_path .= "{$path}";
            $upload_dir .= "{$path}";
        }

        ob_start();

        if (!empty($results)):

            $num = 0;

            foreach ($results as $id => $filename):
                $filerealname = Tools::substr($filename, 0, Tools::strrpos($filename, '.'));
                $file_path = $current_path . $filename;

                $img = $filename;
                $date = filemtime($file_path);
                $size = filesize($file_path);
                // $file_ext = Tools::substr(strrchr($file, '.'), 1);
                $file_infos = pathinfo($file_path);
                $file_ext = $file_infos['extension'];
                // $sorted[$k] = array('file' => $file, 'date' => $date, 'size' => $size, 'extension' => $file_ext);
                $extension_lower = strtolower($file_ext);

                $is_img = true;

                list($img_width, $img_height, $img_type, $attr) = getimagesize($file_path);

                // if (++$num % 4 === 1):


                $ext = substr($filename, strrpos($filename, '.'));
                $thumbimg = "{$filerealname}-vc_media_thumbnail{$ext}";

                ?>
                <li data-image-folder="<?php echo $path ?>" data-image="<?php echo $filename ?>" data-id="<?php echo $id ?>" class="ff-item-type-2 file">
                    <figure data-type="img" data-name="<?php echo $filename ?>">
                        <a data-function="apply" data-field_id="<?php echo $id ?>" href="#" data-file="<?php echo $img ?>" class="link-img">
                            <div class="img-precontainer">
                                <div class="img-container">
                                    <span></span>
                                    <img alt="<?php echo $img ?>" data-id="<?php echo $id ?>" src="<?php echo $upload_dir . $thumbimg ?>"  class="original "  >
                                </div>
                            </div>
                            <div class="img-precontainer-mini original-thumb">
                                <div class="filetype png hide"><?php echo $img_type ?></div>
                                <div class="img-container-mini">

                                    <img src="<?php echo $upload_dir . $thumbimg ?>" class=" " alt="<?php echo $filerealname ?> thumbnails" />
                                </div>
                            </div>
                        </a>

                        <div class="box">
                            <h4 class="ellipsis">
                                <a data-function="apply" data-field_id="" data-file-id="<?php echo $id ?>" data-file="<?php echo $img ?>" class="link" href="javascript:void('')">
                        <?php echo $img ?></a></h4>
                        </div>
                <?php $date = filemtime($current_path . $img); ?>
                        <input type="hidden" class="date" value="<?php echo $date; ?>"/>
                        <input type="hidden" class="size" value="<?php echo $size ?>"/>
                        <input type="hidden" class="extension" value="<?php echo $extension_lower; ?>"/>
                        <input type="hidden" class="name" value="<?php echo $filerealname ?>"/>
                        <input type="hidden" class="id" value="<?php echo $id ?>"/>

                        <div class="file-date"><?php echo date(lang_Date_type, $date); ?></div>
                        <div class="file-size"><?php echo $size; ?></div>
                        <div class='img-dimension'><?php
                            if ($is_img) {
                                echo $img_width . "x" . $img_height;
                            }

                            ?></div>
                        <div class='file-extension'><?php echo Tools::safeOutput($extension_lower); ?></div>

                        <figcaption>

                        </figcaption>
                    </figure>
                </li>
                <?php
            endforeach;

        endif;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function wpb_single_image_src()
    {
        if (Tools::getValue('content') && is_numeric(Tools::getValue('content'))) {
            $image_src = $this->_path . 'uploads/' . self::get_media_thumbnail_url(Tools::getValue('content'));
            echo JsComposer::ModifyImageUrl($image_src);
            die();
        }
    }

    public static function get_media_thumbnail_url($id = '')
    {
        if (isset($id) && !empty($id)) {
            $db = Db::getInstance();
            $tablename = _DB_PREFIX_ . self::$VC_MEDIA;

            $db_results = $db->executeS("SELECT `file_name`, `subdir` FROM {$tablename} WHERE id_vc_media={$id}", true, false);

//            return isset($db_results[0]['file_name']) ? $db_results[0]['file_name'] : 'no_image.jpg';
            $url = isset($db_results[0]['subdir']) && !empty($db_results[0]['subdir']) ? $db_results[0]['subdir'] . '/' : '';
            return $url .= isset($db_results[0]['file_name']) ? $db_results[0]['file_name'] : '';
        } else {
            return '';
        }
    }

    public static function get_media_alt($id = '')
    {
        if (isset($id) && !empty($id)) {
            $db = Db::getInstance();
            $context = Context::getContext();
            $id_lang = (int) Context::getContext()->language->id;

            $tablename = _DB_PREFIX_ . self::$VC_MEDIA;


            $db_results = $db->getRow("SELECT `legend`  FROM {$tablename}  INNER JOIN `{$tablename}_lang` ON `{$tablename}`.`id_vc_media` = `{$tablename}_lang`.`id_vc_media`  WHERE {$tablename}.id_vc_media={$id} AND `{$tablename}_lang`.id_lang = " . $id_lang, true, false);
//            return isset($db_results[0]['file_name']) ? $db_results[0]['file_name'] : 'no_image.jpg';
            return isset($db_results['legend']) ? $db_results['legend'] : '';
        } else {
            return '';
        }
    }

    public static function import_media_img($file_url, $folder, $filename, $imgSubDir = '')
    {
        $db = Db::getInstance();
        $tablename = _DB_PREFIX_ . self::$VC_MEDIA;

        $tempname = substr($filename, 0, strrpos($filename, '.'));
        $extension = substr($filename, strrpos($filename, '.'));

        $found = $db->getValue("SELECT COUNT(*) AS found FROM {$tablename} WHERE file_name LIKE '{$tempname}%' AND subdir='{$imgSubDir}'");

        if ($found && $found > 0) {
            $filename = $tempname . '-' . ( ++$found) . $extension;
        }
        if (!empty($imgSubDir) && $imgSubDir != '/') {
            $imgSubDir = "'{$imgSubDir}'";
        } else {
            $imgSubDir = 'NULL';
        }


        $db->execute("INSERT INTO {$tablename}(file_name,subdir) VALUES('{$filename}',{$imgSubDir})");
        $imgid = $db->Insert_ID();
        if (!empty($imgid) && is_numeric($imgid)) { //new fixing 
            Tools::copy($file_url, $folder . $filename);
            $dir = $folder;
            $filerealname = Tools::substr($filename, 0, Tools::strrpos($filename, '.'));
            $ext = substr($filename, strrpos($filename, '.'));
            $type = VcImageType::getImagesTypes('active');
            if (!empty($type)) {
                foreach ($type as $imageType) {
                    $newfilename = "{$filerealname}-{$imageType['name']}";
                    if (!file_exists($dir . $newfilename . $ext)) {
                        ImageManager::resize($dir . $filename, $dir . $newfilename . $ext, (int) $imageType['width'], (int) $imageType['height']);
                    }
                }
            }

            return array("id" => $imgid, "path" => 'uploads/' . $filename);
        }
    }

    public function generateImageSizesArray()
    {
        $sizes = array_merge(array(array('name' => 'default')), VcImageType::getImagesTypes());
        if (!empty($sizes)) {
            foreach ($sizes as $size) {
                if (isset($size['width'])) {
                    $this->image_sizes[$size['name']] = "{$size['width']}x{$size['height']}";
                }
                $this->image_sizes_dropdown[$size['name']] = $size['name'];
            }
        }
    }

    public function getImageSize($name)
    {
        if (isset($this->image_sizes[$name]) && !empty($this->image_sizes[$name]))
            return $this->image_sizes[$name];
        return false;
    }

    public function update_cms_frontend()
    {
        $id_lang = Tools::getValue('id_lang');
        $content = Tools::getValue('content');
        $content = addcslashes($content, "'");

        $controller_name = Tools::getValue('controller_name');
        $val_identifier = Tools::getValue('val_identifier');
        switch ($controller_name) {
            case 'Adminvccontentanywhere':
                if (!empty($val_identifier) && !empty($val_identifier)) {
                    $db = Db::getInstance();
                    $table = _DB_PREFIX_ . 'vccontentanywhere_lang';
                    $sql = "UPDATE {$table} SET content='{$content}' WHERE id_vccontentanywhere={$val_identifier} AND id_lang={$id_lang}";
                    $stat = $db->execute($sql, false);
                    echo intval($stat);
                }
                break;
            case 'AdminCategories':
                if (!empty($val_identifier) && !empty($val_identifier)) {
                    $db = Db::getInstance();
                    $table = _DB_PREFIX_ . 'category_lang';
                    $sql = "UPDATE {$table} SET description='{$content}' WHERE id_category={$val_identifier} AND id_lang={$id_lang}";
                    $stat = $db->execute($sql, false);
                    echo intval($stat);
                }
                break;
            case 'AdminManufacturers':
                if (!empty($val_identifier) && !empty($val_identifier)) {
                    $db = Db::getInstance();
                    $table = _DB_PREFIX_ . 'manufacturer_lang';
                    $sql = "UPDATE {$table} SET description='{$content}' WHERE id_manufacturer={$val_identifier} AND id_lang={$id_lang}";
                    $stat = $db->execute($sql, false);
                    echo intval($stat);
                }
                break;
            case 'AdminCmsContent':
                if (!empty($val_identifier) && !empty($val_identifier)) {
                    $db = Db::getInstance();
                    $table = _DB_PREFIX_ . 'cms_lang';
                    $sql = "UPDATE {$table} SET content='{$content}' WHERE id_cms={$val_identifier} AND id_lang={$id_lang}";
                    $stat = $db->execute($sql, false);
                    echo intval($stat);
                }
                break;

            default:
                $modules_configuration = JsComposer::getModulesConfiguration();

                foreach ($modules_configuration as $key => $value) {
                    if(isset($value->controller)){
                        if($value->controller == $controller_name){
                            // $tmp_data = (array) self::getJsControllerValues($value->dbtable,$value->field,$value->identifier,$val_identifier,Tools::getValue('id_lang'));
                            // $content = $tmp_data[$value->field][Tools::getValue('id_lang')];

                            if (!empty($val_identifier) && !empty($val_identifier)) {
                                $db = Db::getInstance();
                                $db_table = (isset($value->dbtable)) ? _DB_PREFIX_.$value->dbtable : '';
                                $table = _DB_PREFIX_ . $value->dbtable . '_lang';
                                $field_content = $value->field;
                                $field_identifier = $value->identifier;
                                $val_content = addcslashes($content, "'");
                                $sql = "UPDATE {$db_table}_lang SET {$field_content}='{$val_content}' WHERE {$field_identifier}={$val_identifier} AND id_lang={$id_lang}";
                                $stat = $db->execute($sql, false);
                                echo intval($stat);
                            }

                        }
                    }
                }
                break;
        }
    }

    public static function getSmartBlogPostsThumbSizes()
    {
        $dbvc = Db::getInstance();
        $thumb_sizes = $dbvc->executeS("SELECT type_name FROM " . _DB_PREFIX_ . "smart_blog_imagetype WHERE type='post'", true, false);
        $nthumbs = array();
        if (!empty($thumb_sizes)) {
            foreach ($thumb_sizes as $tsize) {
                $tsize = $tsize['type_name'];
                $nthumbs["{$tsize}"] = $tsize;
            }
        }
        return $nthumbs;
    }

    public function getPreviewLink()
    {
        $id = intval(Tools::getValue('post_id'));
        $link = new Link;
        $id_lang = Tools::getValue('id_lang') ? Tools::getValue('id_lang') : null;
        $id_shop = Tools::getValue('id_shop') ? Tools::getValue('id_shop') : null;
        $ssl = Tools::getValue('ssl') ? Tools::getValue('ssl') : null;
        $type = Tools::getValue('type');
        
        
            if($type == ''){
                $type = 'cms';
            }
        if (!empty($id) && is_numeric($id)) {
            switch ($type) {
                case 'cms':
                    //            echo JsComposer::getCMSLink(intval($id), null, $ssl, $id_lang , $id_shop );            
//                    $cms = new CMS($id);
                    echo $link->getCMSLink($id, null, $ssl, $id_lang, $id_shop);
                    break;
                case 'cat':
                    echo $link->getCategoryLink($id, null, $ssl, $id_lang, $id_shop);
                    break;
                case 'man':
                    echo $link->getManufacturerLink($id, null, $ssl, $id_lang, $id_shop);
                    break;
                case 'sup':
                    echo $link->getSupplierLink($id, null, $ssl, $id_lang, $id_shop);
                    break;
                case 'prd':
                    echo $link->getProductLink($id, null, $ssl, $id_lang, $id_shop);
                    break;
                case 'smartblog':
                    if (class_exists('SmartBlogPost')) { //smartblog link generator...
                        $blog = new SmartBlogPost($id);
                        if (!empty($blog->id_smart_blog_post)) {
                            $options = array('id_post' => $blog->id_smart_blog_post, 'slug' => $blog->link_rewrite[intval($id_lang)]);
                            echo smartblog::GetSmartBlogLink('smartblog_post', $options);
                        }
                    }
                    break;
            }
        }
        die();
    }

    public static function getTPLPath($template = '', $module_name = 'jscomposer')
    {
        if (Tools::file_exists_cache(_PS_THEME_DIR_ . 'modules/' . $module_name . '/' . $template))
            return _PS_THEME_DIR_ . 'modules/' . $module_name . '/' . $template;
        elseif (Tools::file_exists_cache(_PS_THEME_DIR_ . 'modules/' . $module_name . '/views/templates/front/' . $template))
            return _PS_THEME_DIR_ . 'modules/' . $module_name . '/views/templates/front/' . $template;
        elseif (Tools::file_exists_cache(_PS_MODULE_DIR_ . $module_name . '/views/templates/front/' . $template))
            return _PS_MODULE_DIR_ . $module_name . '/views/templates/front/' . $template;
        return false;
    }

    public static function asign_smarty_object()
    {
        $smarty = new smarty();
        JsComposer::fronted_smarty_asign($smarty);
        return $smarty;
    }

    public static function fronted_smarty_asign($smarty)
    {
        // start smarty asign
        smartyRegisterFunction($smarty, 'modifier', 'truncate', 'smarty_modifier_truncate');
        smartyRegisterFunction($smarty, 'modifier', 'secureReferrer', array('Tools', 'secureReferrer'));
        smartyRegisterFunction($smarty, 'function', 't', 'smartyTruncate'); // unused
        smartyRegisterFunction($smarty, 'function', 'm', 'smartyMaxWords'); // unused
        smartyRegisterFunction($smarty, 'function', 'p', 'smartyShowObject'); // Debug only
        smartyRegisterFunction($smarty, 'function', 'd', 'smartyDieObject'); // Debug only
        smartyRegisterFunction($smarty, 'function', 'l', 'smartyTranslate', false);
        smartyRegisterFunction($smarty, 'function', 'hook', 'smartyHook');
        smartyRegisterFunction($smarty, 'function', 'toolsConvertPrice', 'toolsConvertPrice');
        smartyRegisterFunction($smarty, 'modifier', 'json_encode', array('Tools', 'jsonEncode'));
        smartyRegisterFunction($smarty, 'modifier', 'json_decode', array('Tools', 'jsonDecode'));
        smartyRegisterFunction($smarty, 'function', 'dateFormat', array('Tools', 'dateFormat'));
        smartyRegisterFunction($smarty, 'function', 'convertPrice', array('Product', 'convertPrice'));
        smartyRegisterFunction($smarty, 'function', 'convertPriceWithCurrency', array('Product', 'convertPriceWithCurrency'));
        smartyRegisterFunction($smarty, 'function', 'displayWtPrice', array('Product', 'displayWtPrice'));
        smartyRegisterFunction($smarty, 'function', 'displayWtPriceWithCurrency', array('Product', 'displayWtPriceWithCurrency'));
        smartyRegisterFunction($smarty, 'function', 'displayPrice', array('Tools', 'displayPriceSmarty'));
        smartyRegisterFunction($smarty, 'modifier', 'convertAndFormatPrice', array('Product', 'convertAndFormatPrice')); // used twice
        smartyRegisterFunction($smarty, 'function', 'getAdminToken', array('Tools', 'getAdminTokenLiteSmarty'));
        smartyRegisterFunction($smarty, 'function', 'displayAddressDetail', array('AddressFormat', 'generateAddressSmarty'));
        smartyRegisterFunction($smarty, 'function', 'getWidthSize', array('Image', 'getWidth'));
        smartyRegisterFunction($smarty, 'function', 'getHeightSize', array('Image', 'getHeight'));
        smartyRegisterFunction($smarty, 'function', 'addJsDef', array('Media', 'addJsDef'));
        smartyRegisterFunction($smarty, 'block', 'addJsDefL', array('Media', 'addJsDefL'));
        smartyRegisterFunction($smarty, 'modifier', 'boolval', array('Tools', 'boolval'));
        $compared_products = array();
        if (Configuration::get('PS_COMPARATOR_MAX_ITEM') && isset(Context::getcontext()->cookie->id_compare))
            $compared_products = CompareProduct::getCompareProducts(Context::getcontext()->cookie->id_compare);
        if (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'))
            $link_ssl = true;
        else
            $link_ssl = false;
        $protocol_link = (Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode()) ? 'https://' : 'http://';
        $useSSL = ((isset($link_ssl) && $link_ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';
        $link = new Link($protocol_link, $protocol_content);
        $currency = Tools::setCurrency(Context::getcontext()->cookie);
        // START cart block
        if ((int) Context::getcontext()->cookie->id_cart) {
            $cart = new Cart(Context::getcontext()->cookie->id_cart);
            if ($cart->OrderExists()) {
                unset($this->context->cookie->id_cart, $cart, Context::getcontext()->cookie->checkedTOS);
                Context::getcontext()->cookie->check_cgv = false;
            }
            /* Delete product of cart, if user can't make an order from his country */ elseif (intval(Configuration::get('PS_GEOLOCATION_ENABLED')) &&
                !in_array(strtoupper(Context::getcontext()->cookie->iso_code_country), explode(';', Configuration::get('PS_ALLOWED_COUNTRIES'))) &&
                $cart->nbProducts() && intval(Configuration::get('PS_GEOLOCATION_NA_BEHAVIOR')) != -1 &&
//                    !FrontController::isInWhitelistForGeolocation() &&
                !in_array($_SERVER['SERVER_NAME'], array('localhost', '127.0.0.1'))) {
                unset(Context::getcontext()->cookie->id_cart, $cart);
            }
            // update cart values
            elseif (Context::getcontext()->cookie->id_customer != $cart->id_customer || Context::getcontext()->cookie->id_lang != $cart->id_lang || $currency->id != $cart->id_currency) {
                if (Context::getcontext()->cookie->id_customer)
                    $cart->id_customer = (int) (Context::getcontext()->cookie->id_customer);
                $cart->id_lang = (int) (Context::getcontext()->cookie->id_lang);
                $cart->id_currency = (int) $currency->id;
                $cart->update();
            }
            /* Select an address if not set */
            if (isset($cart) && (!isset($cart->id_address_delivery) || $cart->id_address_delivery == 0 ||
                !isset($cart->id_address_invoice) || $cart->id_address_invoice == 0) && Context::getcontext()->cookie->id_customer) {
                $to_update = false;
                if (!isset($cart->id_address_delivery) || $cart->id_address_delivery == 0) {
                    $to_update = true;
                    $cart->id_address_delivery = (int) Address::getFirstCustomerAddressId($cart->id_customer);
                }
                if (!isset($cart->id_address_invoice) || $cart->id_address_invoice == 0) {
                    $to_update = true;
                    $cart->id_address_invoice = (int) Address::getFirstCustomerAddressId($cart->id_customer);
                }
                if ($to_update)
                    $cart->update();
            }
        }
        if (!isset($cart) || !$cart->id) {
            $cart = new Cart();
            $cart->id_lang = (int) (Context::getcontext()->cookie->id_lang);
            $cart->id_currency = (int) (Context::getcontext()->cookie->id_currency);
            $cart->id_guest = (int) (Context::getcontext()->cookie->id_guest);
            $cart->id_shop_group = (int) Context::getcontext()->shop->id_shop_group;
            $cart->id_shop = Context::getcontext()->shop->id;
            if (Context::getcontext()->cookie->id_customer) {
                $cart->id_customer = (int) (Context::getcontext()->cookie->id_customer);
                $cart->id_address_delivery = (int) (Address::getFirstCustomerAddressId($cart->id_customer));
                $cart->id_address_invoice = $cart->id_address_delivery;
            } else {
                $cart->id_address_delivery = 0;
                $cart->id_address_invoice = 0;
            }

            // Needed if the merchant want to give a free product to every visitors
            Context::getcontext()->cart = $cart;
            CartRule::autoAddToCart(Context::getcontext());
        } else
            Context::getcontext()->cart = $cart;
        // END cart block
        $smarty->assign(
            array(
                'page_name' => Context::getcontext()->controller->php_self,
                'add_prod_display' => (int) Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
                'link' => $link,
                'cart' => $cart,
                'currency' => $currency,
                //'static_token' => Tools::getToken(false),
                'cookie' => Context::getcontext()->cookie,
                'tpl_dir' => _PS_THEME_DIR_,
                'PS_CATALOG_MODE' => Configuration::get('PS_CATALOG_MODE'),
                'PS_STOCK_MANAGEMENT' => Configuration::get('PS_STOCK_MANAGEMENT'),
                'priceDisplay' => Product::getTaxCalculationMethod((int) Context::getcontext()->cookie->id_customer),
                'compared_products' => is_array($compared_products) ? $compared_products : array(),
                'comparator_max_item' => (int) Configuration::get('PS_COMPARATOR_MAX_ITEM')
            )
        );
        // End smarty asign
    }
    /*
      public function contenthookvalue($hook = '')
      {
      $context = Context::getContext();
      $page = $context->controller->php_self;
      if (!is_object($this->vccawobj)) {
      $this->vccawobj = vccontentanywhere::GetInstance();
      }
      $vcaw = $this->vccawobj;
      $results = $vcaw->GetVcContentAnyWhereByHook($hook);
      if (isset($results) && !empty($results)) {

      foreach ($results as $result) {
      $display_type = $result['display_type'];
      $prd_page = $result['prd_page'];
      $prd_specify = $result['prd_specify'];
      $cat_page = $result['cat_page'];
      $cat_specify = $result['cat_specify'];
      $cms_page = $result['cms_page'];
      $cms_specify = $result['cms_specify'];

      if ($result['exception_type'] == 1) {

      $exception_vals = explode(',', $result['exception']);
      if (in_array($page, $exception_vals)) {
      $allexceptionpage = 'allexcp' . str_replace(' ', '', $hook) . $page . $result['id_vccontentanywhere'];
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $allexceptionpage)) {
      $values = $vcaw->GetVcContentByAllException($hook, $page);

      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $allexceptionpage);
      }
      } else {
      //start hhhhhhhhhhhhhhhhhh
      if ($result['display_type'] == 1) { //Start display type 1
      $allpage = 'alpg' . str_replace(' ', '', $hook);
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $allpage)) {

      $values = $vcaw->GetVcContentByAll($hook);


      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $allpage);
      } else {  //Start display type else
      if ($page == 'product') { //start product page
      if ($result['prd_page'] == 1) {
      $allprdpage = 'alprdpg' . str_replace(' ', '', $hook);
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $allprdpage)) {

      $values = $vcaw->GetVcContentByAllPRD($hook);

      $this->smarty->assign(array(
      'results' => $values
      ));
      }

      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $allprdpage);
      } else {
      $id_product = Tools::getValue('id_product');
      //                                $id_prd_cats = $vcaw->getProductCategories($id_product);
      $prd_specify_arr = explode('-', $result['prd_specify']);
      $prd_specify_prd_arr = array();
      $prd_specify_cat_arr = array();
      if (isset($prd_specify_arr) && !empty($prd_specify_arr)) {
      unset($prd_specify_arr[count($prd_specify_arr) - 1]);



      if (in_array($id_product, $prd_specify_arr)) {
      //Start execute and asign
      $spcprdpage = 'spprdpg' . str_replace(' ', '', $hook) . $id_product;
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $spcprdpage)) {

      $values = $vcaw->GetVcContentByAllPRDID($hook, $id_product);

      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $spcprdpage);
      //End execute and asign
      }

      //                                    }
      }
      //exception product page
      }
      }//end product page
      elseif ($page == 'category') { //start category page

      if ($result['cat_page'] == 1) {
      $allcatpage = 'allcatpage' . str_replace(' ', '', $hook);
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $allcatpage)) {

      $values = $vcaw->GetVcContentByAllCAT($hook);

      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $allcatpage);
      } else {
      $id_category = Tools::getValue('id_category');
      $cat_specify_arr = explode(',', $result['cat_specify']);

      if (in_array($id_category, $cat_specify_arr)) {
      $spccatpage = 'cat' . str_replace(' ', '', $hook) . $id_category;
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $spccatpage)) {

      $values = $vcaw->GetVcContentByAllCATID($hook, $id_category);


      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $spccatpage);
      }
      //exception Category page
      }
      }//end category page
      elseif ($page == 'cms') { //start cms page
      if ($result['cms_page'] == 1) {
      $allcmspage = 'allcmspage' . str_replace(' ', '', $hook);
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $allcmspage)) {

      $values = $vcaw->GetVcContentByAllCMS($hook);

      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $allcmspage);
      } else {
      $id_cms = Tools::getValue('id_cms');
      $cms_specify_arr = explode(',', $result['cms_specify']);
      if (in_array($id_cms, $cms_specify_arr)) {
      $spccmspage = 'cms' . str_replace(' ', '', $hook) . $id_cms;
      if (!$this->isCached('jscomposer.tpl', $this->getCacheId(), $spccmspage)) {

      $values = $vcaw->GetVcContentByAllCMSID($hook, $id_cms);
      //                                            if( strtolower($result['hook_name']) == strtolower($hook)){
      //                                                $values[] = $result;
      //                                            }

      $this->smarty->assign(array(
      'results' => $values
      ));
      }
      return $this->display(__FILE__, 'views/templates/front/jscomposer.tpl', $this->getCacheId(), $spccmspage);
      }
      //exception CMS page
      }
      }//end cms page
      }
      //end hhhhhhhhhhhhhhhhhh
      }
      }
      }
      }

     */



    public function vcmaps_init($base = '', $module_name = null, $vccaw = null)
    {
        $hooks = array();

        if (!is_object($this->vccawobj)) {
            $this->vccawobj = new vccontentanywhere();
        }
        $vccaw = $this->vccawobj;
        $mod_name = str_replace('vc_', '', $base);
        if (empty($module_name)) {
            $module_name = $mod_name;
        }

       // print_r($module_name);
       /* $allhooks = $vccaw->getModuleHookbyedit($mod_name);
        if (isset($allhooks) && !empty($allhooks)) {
            foreach ($allhooks as $hook) {
                $hooks[$hook['name']] = $hook['name'];
            }
        }*/
      
 
       // $module_id = 7;//(int)Tools::getValue('module_id');
        if ($mod_name == '') {
            die('{"hasError" : true, "errors" : ["Wrong module Name."]}');
        }

        $module_hooks = array();
        $module_instance = Module::getInstanceByName($mod_name);
        if (Module::isInstalled($mod_name) || file_exists(_PS_MODULE_DIR_."$mod_name/$mod_name.php")) {
            $module_hooks = $module_instance->getPossibleHooksList();
        }


        if (isset($module_hooks) && !empty($module_hooks)) {
            foreach ($module_hooks as $hook) {
                $hooks[$hook['name']] = $hook['name'];
            }
        }

        // echo 'aaa<pre>';
        // print_r($module_hooks);
        // echo '</pre>';

        //print_r( $module_hooks);

        $icon_url = context::getcontext()->shop->getBaseURL() . 'modules/' . $mod_name . '/logo.png';
        $vc = vc_manager();
        $brands_params = array(
            'name' => $module_name,
            'base' => $base,
            'icon' => $icon_url,
            'category' => 'Modules',
            'params' => array(
                array(
                    "type" => "dropdown",
                    "heading" => $vc->l("Executed Hook"),
                    "param_name" => "execute_hook",
                    "value" => $hooks
                ), array(
                    "type" => "vc_hidden_field",
                    "param_name" => "execute_module",
                    "def_value" => $mod_name,
                    "value" => $mod_name
                )
            )
        );
        vc_map($brands_params);
    }

    public function GenerateModuleIcon()
    {
        $output = '<style>';
        if (!is_object($this->vccawobj)) {
            $this->vccawobj = new vccontentanywhere();
        }
        $vccaw = $this->vccawobj;
        $GetAllmodules_list = $vccaw->GetAllFilterModules();
        //print_r($GetAllmodules_list);
        foreach ($GetAllmodules_list as $value) {
            $icon_url = context::getcontext()->shop->getBaseURL() . 'modules/' . $value['id'] . '/logo.png';
            $output .= "
                .vc_el-container #vc_" . $value['id'] . " .vc_element-icon,
                .wpb_vc_" . $value['id'] . " .wpb_element_title .vc_element-icon {
                    background-image: url(" . $icon_url . ");
                    background-image: url(" . $icon_url . ");
                    -webkit-background-size: contain;
                    -moz-background-size: contain;
                    -ms-background-size: contain;
                    -o-background-size: contain;
                    background-size: contain;
                }
            ";
        }
        $output .= '</style>';
        echo $output;
    }

    public function add_custom_param_code()
    {
        jscomposer::add_shortcode_param('vc_hidden_field', array($this, 'vc_hidden_fields_func'));
        jscomposer::add_shortcode_param('vc_product_fileds', array($this, 'vc_product_fileds_func'));
        jscomposer::add_shortcode_param('vc_category_fileds', array($this, 'vc_category_fileds_func'));
        jscomposer::add_shortcode_param('vc_brands_fileds', array($this, 'vc_brands_fileds_func'));
        jscomposer::add_shortcode_param('vc_supplier_fileds', array($this, 'vc_supplier_fileds_func'));
    }

    public function vc_hidden_fields_func($settings, $value)
    {
        $outputcontent = '<input type="hidden" name="' . $settings['param_name'] . '" value="' . $settings['def_value'] . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field">';
        return $outputcontent;
    }

    public function vc_product_fileds_func($settings, $value)
    {
        $_html = '<div class="' . $settings['param_name'] . '">
        <select name="vc_product_fileds_' . $settings['param_name'] . '" class=" fixed-width-xl" id="vc_product_fileds_' . $settings['param_name'] . '" multiple="true">';
        $allproducts = $this->GetAllProductS();
        foreach ($allproducts as $allprd) {
            if (isset($value)) {
                $settings_def_value = explode(",", $value);
                if (in_array($allprd['id_product'], $settings_def_value)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $_html .= '<option ' . $selected . ' value="' . $allprd['id_product'] . '">' . $allprd['name'] . '</option>';
        }
        $_html .= '</select>
        <input type="hidden" name="' . $settings['param_name'] . '" id="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field">
        <script type="text/javascript">
            $(function(){
                var defVal = $("input#' . $settings['param_name'] . '").val();
                if(defVal.length){
                    var ValArr = defVal.split(\',\');
                    for(var n in ValArr){
                        $( "select#vc_product_fileds_' . $settings['param_name'] . '" ).children(\'option[value="\'+ValArr[n]+\'"]\').attr(\'selected\',\'selected\');
                    }
                }
                $( "select#vc_product_fileds_' . $settings['param_name'] . '" ).select2( { placeholder: "Select Products", width: 200, tokenSeparators: [\',\', \' \'] } ).on(\'change\',function(){
                    var data = $(this).select2(\'data\');
                    var select = $(this);
                    var field = select.next("input#' . $settings['param_name'] . '");
                    var saved = \'\';
                    select.children(\'option\').attr(\'selected\',null);
                    if(data.length)
                        $.each(data, function(k,v){
                            var selected = v.id;   
                            select.children(\'option[value="\'+selected+\'"]\').attr(\'selected\',\'selected\');
                            if(k > 0)
                                saved += \',\';
                            saved += selected;                                
                        });
                     field.val(saved);   
                });
            });
        </script>
        </div>';
        return $_html;
    }

    public function vc_brands_fileds_func($settings, $value)
    {
        $_html = '<div class="' . $settings['param_name'] . '">
        <select name="vc_brand_fileds_' . $settings['param_name'] . '" class=" fixed-width-xl" id="vc_brand_fileds_' . $settings['param_name'] . '" multiple="true">';
        $allbrands = $this->GetAllBrandS();
        foreach ($allbrands as $allbrnd) {
            if (isset($value)) {
                $settings_def_value = explode(",", $value);
                if (in_array($allbrnd['id_manufacturer'], $settings_def_value)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $_html .= '<option ' . $selected . ' value="' . $allbrnd['id_manufacturer'] . '">' . $allbrnd['name'] . '</option>';
        }
        $_html .= '</select>
        <input type="hidden" name="' . $settings['param_name'] . '" id="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field">
        <script type="text/javascript">
            $(function(){
                var defVal = $("input#' . $settings['param_name'] . '").val();
                if(defVal.length){
                    var ValArr = defVal.split(\',\');
                    for(var n in ValArr){
                        $( "select#vc_brand_fileds_' . $settings['param_name'] . '" ).children(\'option[value="\'+ValArr[n]+\'"]\').attr(\'selected\',\'selected\');
                    }
                }
                $( "select#vc_brand_fileds_' . $settings['param_name'] . '" ).select2( { placeholder: "Select Brands", width: 200, tokenSeparators: [\',\', \' \'] } ).on(\'change\',function(){
                    var data = $(this).select2(\'data\');
                    var select = $(this);
                    var field = select.next("input#' . $settings['param_name'] . '");
                    var saved = \'\';
                    select.children(\'option\').attr(\'selected\',null);
                    if(data.length)
                        $.each(data, function(k,v){
                            var selected = v.id;   
                            select.children(\'option[value="\'+selected+\'"]\').attr(\'selected\',\'selected\');
                            if(k > 0)
                                saved += \',\';
                            saved += selected;                                
                        });
                     field.val(saved);   
                });
            });
        </script>
        </div>';
        return $_html;
    }

    public function vc_category_fileds_func($settings, $value)
    {
        $_html = '<div class="' . $settings['param_name'] . '">
        <select name="vc_category_fileds_' . $settings['param_name'] . '" class=" fixed-width-xl" id="vc_category_fileds_' . $settings['param_name'] . '" multiple="true">';
        $allcategories = $this->GetAllCategorieS();
        foreach ($allcategories as $allprd) {
            if (isset($value)) {
                $settings_def_value = explode(",", $value);
                if (in_array($allprd['id_category'], $settings_def_value)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $_html .= '<option ' . $selected . ' value="' . $allprd['id_category'] . '">' . $allprd['name'] . '</option>';
        }
        $_html .= '</select>
        <input type="hidden" name="' . $settings['param_name'] . '" id="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field">
        <script type="text/javascript">
            $(function(){
                var defVal = $("input#' . $settings['param_name'] . '").val();
                if(defVal.length){
                    var ValArr = defVal.split(\',\');
                    for(var n in ValArr){
                        $( "select#vc_category_fileds_' . $settings['param_name'] . '" ).children(\'option[value="\'+ValArr[n]+\'"]\').attr(\'selected\',\'selected\');
                    }
                }
                $( "select#vc_category_fileds_' . $settings['param_name'] . '" ).select2( { placeholder: "Select Categories", width: 200, tokenSeparators: [\',\', \' \'] } ).on(\'change\',function(){
                    var data = $(this).select2(\'data\');
                    var select = $(this);
                    var field = select.next("input#' . $settings['param_name'] . '");
                    var saved = \'\';
                    select.children(\'option\').attr(\'selected\',null);
                    if(data.length)
                        $.each(data, function(k,v){
                            var selected = v.id;   
                            select.children(\'option[value="\'+selected+\'"]\').attr(\'selected\',\'selected\');
                            if(k > 0)
                                saved += \',\';
                            saved += selected;                                
                        });
                     field.val(saved);   
                });
            });
        </script>
        </div>';
        return $_html;
    }

    public function vc_supplier_fileds_func($settings, $value)
    {
        $_html = '<div class="' . $settings['param_name'] . '">
        <select name="vc_supplier_fileds_' . $settings['param_name'] . '" class=" fixed-width-xl" id="vc_supplier_fileds_' . $settings['param_name'] . '" multiple="true">';
        $allsuppliers = $this->GetAllSupplierS();
        foreach ($allsuppliers as $allsplr) {
            if (isset($value)) {
                $settings_def_value = explode(",", $value);
                if (in_array($allsplr['id_supplier'], $settings_def_value)) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
            } else {
                $selected = '';
            }
            $_html .= '<option ' . $selected . ' value="' . $allsplr['id_supplier'] . '">' . $allsplr['name'] . '</option>';
        }
        $_html .= '</select>
        <input type="hidden" name="' . $settings['param_name'] . '" id="' . $settings['param_name'] . '" value="' . $value . '" class="wpb_vc_param_value wpb-textinput ' . $settings['param_name'] . ' ' . $settings['type'] . '_field">
        <script type="text/javascript">
            $(function(){
                var defVal = $("input#' . $settings['param_name'] . '").val();
                if(defVal.length){
                    var ValArr = defVal.split(\',\');
                    for(var n in ValArr){
                        $( "select#vc_supplier_fileds_' . $settings['param_name'] . '" ).children(\'option[value="\'+ValArr[n]+\'"]\').attr(\'selected\',\'selected\');
                    }
                }
                $( "select#vc_supplier_fileds_' . $settings['param_name'] . '" ).select2( { placeholder: "Select Supplier", width: 200, tokenSeparators: [\',\', \' \'] } ).on(\'change\',function(){
                    var data = $(this).select2(\'data\');
                    var select = $(this);
                    var field = select.next("input#' . $settings['param_name'] . '");
                    var saved = \'\';
                    select.children(\'option\').attr(\'selected\',null);
                    if(data.length)
                        $.each(data, function(k,v){
                            var selected = v.id;   
                            select.children(\'option[value="\'+selected+\'"]\').attr(\'selected\',\'selected\');
                            if(k > 0)
                                saved += \',\';
                            saved += selected;                                
                        });
                     field.val(saved);   
                });
            });
        </script>
        </div>';
        return $_html;
    }

    public function vcallmod()
    {
        if (!is_object($this->vccawobj)) {
            $this->vccawobj = new vccontentanywhere();
        }
//        var_dump(__FILE__.__LINE__);
//        echo '<pre>';
//        print_r($_POST);die();
        $vccaw = $this->vccawobj;
        $GetAllmodules_list = array();
        if ($this->is_admin()) {
            if (Tools::getValue('action') == 'wpb_show_edit_form') {
                //do the changes here.
                $params = Tools::getValue('params');
                if (isset($params['execute_module'])) {
                    $GetAllmodules_list[] = array('id' => $params['execute_module'], 'name' => $params['execute_module']);
                }
            } else {
                $GetAllmodules_list = $vccaw->GetAllFilterModules();
            }
        } else {
            $GetAllmodules_list = $vccaw->GetAllModules();
        }
        if (!empty($GetAllmodules_list)) {
            foreach ($GetAllmodules_list as &$value) {
                if (!isset($value['id']) || !isset($value['name'])) {
                    $value = array('id' => $value, 'name' => $value);
                }
                JsComposer::add_shortcode('vc_' . $value['id'], array($this, 'vcallmodcode'));
                if ($this->is_admin()) {
                    if(is_object($vccaw)){
                        $this->vcmaps_init('vc_' . $value['id'], $value['name'], $vccaw);
                    }
                }
            }
        }
    }

    public function vcallmodcode($atts, $content = null)
    {
        extract(JsComposer::shortcode_atts(array(
                'execute_hook' => '',
                'execute_module' => '',
                ), $atts));
        if (!is_object($this->vccawobj)) {
            $this->vccawobj = new vccontentanywhere();
        }
        $vccaw = $this->vccawobj;
        $results = $vccaw->ModHookExec($execute_module, $execute_hook);
        return $results;
    }

    public static function vc_content_filter($content = '')
    {
        $content = JsComposer::do_shortcode($content);
        if ((bool) Module::isEnabled('smartshortcode')) {
            $smartshortcode = Module::getInstanceByName('smartshortcode');
            $content = $smartshortcode->parse($content);
        }
        return $content;
    }

    public function GetSimpleProductS()
    {
        $context = Context::getContext();
        $id_lang = (int) Context::getContext()->language->id;
        $front = true;
        $sql = 'SELECT p.`id_product`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
                ORDER BY pl.`name`';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    public function GetAllProductS()
    {
        $rs = array();
        $rslt = array();
        $rs = $this->getSimpleProducts();
        $i = 0;
        foreach ($rs as $r) {
            $rslt[$i]['id_product'] = $r['id_product'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }

    public function GetAllBrandS()
    {
        $rs = array();
        $rslt = array();
        $rs = Manufacturer::getManufacturers();
        $i = 0;
        foreach ($rs as $r) {
            $rslt[$i]['id_manufacturer'] = $r['id_manufacturer'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }

    public function GetAllSupplierS()
    {
        $rs = array();
        $rslt = array();
        $rs = Supplier::getSuppliers();
        $i = 0;
        foreach ($rs as $r) {
            $rslt[$i]['id_supplier'] = $r['id_supplier'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }

    public function GetAllCategorieS()
    {
        $rs = array();
        $rslt = array();
        $id_lang = Context::getContext()->language->id;
        $rs = Category::getCategories($id_lang, true, false);
        $i = 0;
        foreach ($rs as $r) {
            $rslt[$i]['id_category'] = $r['id_category'];
            $rslt[$i]['name'] = $r['name'];
            $i++;
        }
        return $rslt;
    }

    public function hookvcBeforeInit()
    {
        
        $this->generateImageSizesArray();

        return true;
    }

    public function hookDisplayHeader($params)
    {
        $content = '';
        if(!Tools::getValue('id_cms')){
            ob_start();
            $this->init();
            $content = ob_get_clean();
        }

        if(vc_manager()->moduleFrontendEnable()){
            $this->front_css[] = vc_asset_url('css/js_composer.css');
        }

        if (Tools::getValue('vc_editable')) {
            $this->front_css[] = vc_asset_url('css/js_composer_frontend_editor_iframe.css');
        }
        if (!empty($this->front_css)) {
            foreach ($this->front_css as $css) {
                $this->context->controller->addCSS($css);
            }
        }

//        $this->front_js[] = array('ui.core', 'effects.core', 'ui.effect', 'ui.widget', 'ui.accordion', 'ui.progressbar', 'ui.tabs', 'ui.sortable', 'ui.draggable');
        if(Tools::getValue('vc_editable') == 'true'){
            $this->context->controller->addJqueryUI(array('ui.core', 'effects.core', 'ui.effect', 'ui.widget', 'ui.accordion', 'ui.progressbar', 'ui.tabs', 'ui.sortable', 'ui.draggable'));
        }
            
        if(vc_manager()->moduleFrontendEnable()){
            $this->front_js[] = vc_asset_url('js/js_composer_front.js');
        }

        //$this->context->controller->registerJavascript('jquery', 'js/jquery/jquery-1.11.0.min.js', ['position' => 'head', 'priority' => 0]);

        if (!empty($this->front_js)) {
            foreach ($this->front_js as $js) {
                $this->context->controller->addJS($js);
            }
        }
        if (Tools::getValue('vc_editable')) {
            //$this->context->controller->addJS(vc_asset_url('js/js_composer_front.js'));
            $this->context->controller->addJS(vc_asset_url('lib/php.default/php.default.min.js'));
            $this->context->controller->addJS(vc_asset_url('js/frontend_editor/vc_page_editable.js'));
        }

        $content .= '<script type="text/javascript">';
        
        $content .= 'var SdsJsOnLoadActions = [];';
        $content .= 'window.onload=function(){ $.each(SdsJsOnLoadActions, function(k, func){ func.call(); }); };';
        
        $content .= '</script>';
        
        return $content;
    }

    public function hookDisplayBackOfficeHeader()
    {
        
        
        if(Tools::version_compare(_PS_VERSION_, '1.7.5.0', '>=')){
            if(Tools::getValue('controller')=='AdminProducts'){
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $url_arr = explode('products/',$actual_link);
                if(isset($url_arr[1])){
                    //leave as it is
                }else{
                    return;
                }
             }
        }else{
                if(Tools::getValue('controller')=='AdminProducts'){
                // return true;
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
               // var_dump($actual_link);die();
                if (strpos($actual_link, 'product/form') === false) {

                    return true;
                }else{
                    // leave it as it is
                }
            }
        }
        if (!Module::isEnabled($this->name)) {
            return;
        }
        $this->setCustomControllersCondition();
        Context::getcontext()->controller->addCSS($this->_path . 'assets/css/adminjscomposericon.css');

        if (
            ++self::$backOfficeCalledFor < 3 &&
            Tools::getValue('controller') != 'VC_frontend' && Tools::getValue('controller') != 'Adminvccontentanywhere' && Tools::getValue('controller') != 'Adminvcproducttabcreator' && Tools::getValue('controller') != 'AdminCategories' && Tools::getValue('controller') != 'AdminManufacturers' && Tools::getValue('controller') != 'AdminSuppliers' && Tools::getValue('controller') != 'AdminBlogPost' && Tools::getValue('controller') != 'AdminProducts' && Tools::getValue('controller') != 'AdminVcTemplatera' && !self::$isVcAdminCustomController
        ) {
            return; // This is for AdminCmsController which runs 3 times.
        }

        $content = '';
//        $this->ajaxController = '//' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . $this->context->controller->admin_webpath . '/' . $this->context->link->getAdminLink('VC_ajax');
        $this->ajaxController = $this->context->link->getAdminLink('VC_ajax');
        if (self::condition()) {
            $this->init();
            $modulepath = $this->_path . 'assets/';
            Context::getcontext()->controller->addCSS($modulepath . 'css/select2.css');
            Context::getcontext()->controller->addCSS($modulepath . 'css/select2-bootstrap.css');
            $controller = Tools::getValue('controller');
            $vcActiveTextFieldName = '';
            if (isset($this->contentBoxNamesByController[$controller]) && !empty($this->contentBoxNamesByController[$controller])) {
                $vcActiveTextFieldName = $this->contentBoxNamesByController[$controller];
            }

            if($controller == 'AdminModules'){
                $url_configure = Tools::getValue('configure');
                $modules_configuration = JsComposer::getModulesConfiguration();
                if(is_object($modules_configuration)){
                    foreach ($modules_configuration as $custom_module_name => $value) {
                        if($custom_module_name == $url_configure){
                            $vcActiveTextFieldName = $value->field;
                        }
                    }
                }
            }

            $content = 'var ' . $this->wpb_js_composer_js_view[0] . '=' . Tools::jsonEncode($this->wpb_js_composer_js_view[1]) . ";\n";
            $content .= 'var ' . $this->wpb_js_composer_automapper[0] . '=' . Tools::jsonEncode($this->wpb_js_composer_automapper[1]) . ";\n";
            $content .= 'var vcActiveTextFieldName ="' . $vcActiveTextFieldName . "\";\n";

            if (Tools::getValue('controller') == 'AdminProducts') {
                $content .= 'var vcActiveTextFieldName ="form_step1_description' . "\";\n";
            }else{
                $content .= 'var vcActiveTextFieldName ="' . $vcActiveTextFieldName . "\";\n";
            }

            $content .= 'var vc_ajaxurl="' . $this->ajaxController . "\";\n";
            $content .= 'var wpColorPickerL10n=' . Tools::jsonEncode(array(
                    'clear' => $this->l('Clear'),
                    'defaultString' => $this->l('Default'),
                    'pick' => $this->l('Select Color'),
                    'current' => $this->l('Current Color'))) . ";\n";
            $content .= 'var vc_mediaAjaxUrl = \'' . self::controller_upload_url() . "';\n";
            $content .= "var vc_urlImage = '{$modulepath}';\n";
            $content .= "var id_lang = " . (Tools::getValue('id_lang') ? Tools::getValue('id_lang') : $this->context->language->id) . ";\n";
            $content .= self::$vcBackofficePageIndenfiers . "\n";
            if (Tools::getValue('controller') == 'VC_frontend') {
                //$content .= 'var ad="' . __PS_BASE_URI__ . $this->context->controller->admin_webpath . '";';
                Media::addJsDef(array(
                    'baseAdminDir' => __PS_BASE_URI__ . $this->context->controller->admin_webpath . '/', 
                    'baseDir' => __PS_BASE_URI__. '/', 
                ));
            }

            if (Tools::getValue('controller') != 'VC_frontend')
                $content .= '$(function(){$("body").addClass("vc_backoffice");});' . "\n";
            $content .= 'var vc_user_mapper =' . Tools::jsonEncode(WPBMap::getUserShortCodes()) . ",\n" .
                'vc_mapper = ' . Tools::jsonEncode(WPBMap::getShortCodes()) . ",\n" .
                "vc_mode = '{$this->mode}';\n";


            $frontend_stat = "vc_frontend_enabled = false;\n";
            $backend_stat = "vc_backend_enabled = false;\n";

            if ($this->isLoadJsComposer('frontend')) {
                $frontend_stat = "vc_frontend_enabled = true;\n";
            }
            if ($this->isLoadJsComposer('backend')) {
                $backend_stat = "vc_backend_enabled = true;\n";
            }

            $temp_controller = Tools::getValue('controller');
            $temp_controller = ($temp_controller != '')? substr($temp_controller, 5) : '';
            $temp_controller = strtolower($temp_controller);
            $req = $_REQUEST;
            $is_add = false;
            foreach ($req as $key => $value) {
                $is_add = (substr($key, 0, 3) == 'add')? true: false;
                if($is_add) break;
            }
            $frontend_stat = ($is_add)? "vc_frontend_enabled = false;\n" : $frontend_stat;

            $Smartlisence = new Smartlisence();
            if(Tools::getValue('controller') == 'Adminvccontentanywhere')
            if(!$Smartlisence->isActive()) $frontend_stat = "vc_frontend_enabled = false;\n";

            $content .= $frontend_stat . $backend_stat;

            $this->context->controller->addJquery();
            if (!in_array(Tools::getValue('controller'), array('AdminProducts', 'AdminBlogPost'))) {
                $this->context->controller->addJqueryUI(
                    array(
                        'ui.core',
                        'ui.accordion',
                        'ui.tabs',
                        'ui.widget',
                        'ui.menu',
                        'ui.position',
                        'ui.sortable',
                        'ui.droppable',
                        'ui.draggable',
                        'ui.slider'
                ));
            } else {
                $this->context->controller->addJqueryUI(
                    array(
                        'ui.core',
                        'ui.accordion',
                        'ui.tabs',
                        'ui.widget',
                        'ui.menu',
                        'ui.position',
                        'ui.sortable',
                        'ui.droppable',
                        'ui.draggable',
                        'ui.slider'
                ));
                // $this->context->controller->addJS('https://code.jquery.com/ui/1.10.4/jquery-ui.min.js'); // ui.accordion has conflict with ui.datepicker. Prestashop jquery ui issue, not jscomposer module.
            }
            $this->context->controller->addJqueryPlugin('autocomplete');

//            $this->context->controller->addJS($modulepath.'js/tinymce/tinymce.inc.js');
//            if(Tools::getValue('controller') != 'Adminvccontentanywhere')
//                $this->context->controller->addJqueryUI('ui.autocomplete');
            $js_lang = Language::getLanguages(true);
            ob_start();
            if (Tools::getValue('controller') === 'VC_frontend') {
                $this->loadVcFrontendActionScripts();
            }

            if(count(explode('product/form/', $_SERVER['REQUEST_URI'])) == 1 AND count(explode('product/catalog', $_SERVER['REQUEST_URI'])) == 1){
                
                $this->addCSS($modulepath . 'lib/bootstrap_modals/css/bootstrap.modals.css');
            } 
            $this->addCSS($modulepath . 'css/ui-custom-theme/jquery-ui-less.custom.css');
            $this->addCSS($modulepath . 'css/color-picker.css');
            $this->addCSS($modulepath . 'css/isotope.css');
            // $this->addCSS($modulepath . 'css/animate.css');
            $this->addCSS($modulepath . 'css/js_composer.css');
            $this->addCSS($modulepath . 'css/js_composer_settings.css');
            $this->addCSS($modulepath . 'css/js_composer_backend_editor.css');
            $this->addCSS($modulepath . 'lib/vc_carousel/css/vc_carousel.css');
            $this->addCSS($modulepath . 'css/thickbox.css');

            if (isset(self::$VCBackofficeShortcodesAction['admin_init']) && !empty(self::$VCBackofficeShortcodesAction['admin_init'])) {

                foreach (self::$VCBackofficeShortcodesAction['admin_init'] as $admin_init)
                    call_user_func($admin_init);
            }
            if (!empty(self::$registeredCSS))
                foreach (self::$registeredCSS as $custom_css)
                    $this->addCSS($custom_css);

            if (isset(self::$VCBackofficeShortcodesAction['admin_head']) && !empty(self::$VCBackofficeShortcodesAction['admin_head'])) {

                foreach (self::$VCBackofficeShortcodesAction['admin_head'] as $admin_head)
                    call_user_func($admin_head);
            }
            if (Tools::getValue('controller') == 'VC_frontend') {
                echo "<style type='text/css'>body{overflow-y: hidden;}</style>";
            }
            echo "<script type='text/javascript'>{$content}</script>\n";



            //$this->vcTinymcePluginRemove('beeshortcode');

            $custom_vc_tinymce_plugin = unserialize(Configuration::get('VC_TINYMCE_PLUGIN'));

            $short_code_hook_comma_separated = array();

            if (isset($custom_vc_tinymce_plugin) && is_array($custom_vc_tinymce_plugin))
                $short_code_hook_comma_separated = implode(",", $custom_vc_tinymce_plugin);

            $this->context->smarty->assign(array(
                'plugings' => $short_code_hook_comma_separated,
            ));

            //tinymce css
            $custom_vc_tinymce_plugin_css = unserialize(Configuration::get('VC_TINYMCE_PLUGIN_CSS'));

            $short_code_hook_comma_separated_css = array();

            if (isset($custom_vc_tinymce_plugin_css) && is_array($custom_vc_tinymce_plugin_css))
                $short_code_hook_comma_separated_css = implode(",", $custom_vc_tinymce_plugin_css);

            $this->context->smarty->assign(array(
                'plugings_css' => $short_code_hook_comma_separated_css,
            ));

            echo $this->context->smarty->fetch(dirname(__FILE__) . '/views/templates/admin/tinymce-jscomposer.tpl');
            $this->addJS($modulepath . 'lib/isotope/jquery.isotope.min.js');
            if (
                Tools::getValue('controller') == 'VC_frontend'
            ) { // In order to eliminate "this.slice is not defined" javascript error...
                $this->addJS($modulepath . 'js/backend/jPaq-1.0.6.min.js');
            }
            $this->addJS($modulepath . 'js/backend/jquery.color.min.js');
            $this->addJS($modulepath . 'js/backend/farbtastic.js');
            $this->addJS($modulepath . 'js/backend/iris.min.js');
            $this->addJS($modulepath . 'js/backend/color-picker.js');
            $this->addJS($modulepath . 'lib/scrollTo/jquery.scrollTo.min.js');
            $this->addJS($modulepath . 'lib/php.default/php.default.min.js');
            $this->addJS($modulepath . 'lib/json-js/json2.js');
            $this->addJS($modulepath . 'js/backend/underscore-min.js');
            $this->addJS($modulepath . 'js/backend/backbone-min.js');
            $this->addJS($modulepath . 'js/backend/shortcode.js');
            $this->addJS($modulepath . 'js/backend/thickbox.js');
            ?>
            <script type="text/javascript">
                function sds_id_lang() {
                    if(document.getElementById('form_switch_language')){
                        var js_lang_array = [];
                        <?php
                        foreach ($js_lang as $js_lang_key => $js_lang_value) {
                            echo "js_lang_array['".$js_lang_value['iso_code']."'] = ".$js_lang_value['id_lang'].'; ';
                        }
                        ?>
                        var e = document.getElementById("form_switch_language");
                        var ev = e.options[e.selectedIndex].value;
                        return js_lang_array[ev];
                    }else if(typeof window.id_language != 'undefined'){
                        return window.id_language;
                    }else{
                        return window.id_lang;
                    }
                }
                function changeFrontEditorLang() {
                    var switch_front = $('a.wpb_switch-to-front-composer');
                    var found = sds_id_lang();
                    if (switch_front.length > 0) {
                        var link = switch_front.attr('href');
                        link = link.replace(/id_lang=([0-9]+)/, 'id_lang=' + found);
                        switch_front.attr('href', link);
                        if ($('#wpb-edit-inline').length)
                            $('#wpb-edit-inline').attr('href', link);
                    }
                }
                $(function () {
                    $(document.body).on('focusout', '.translatable-field ul.dropdown-menu li a', function () {
                        changeFrontEditorLang();
                    });
                });

            </script>
            <?php
            if (Tools::getvalue('controller') == 'Adminvccontentanywhere' || Tools::getvalue('controller') == 'Adminvcproducttabcreator') {
                $this->GenerateModuleIcon();
            }
            $content = ob_get_clean();
        }

        return $content;
    }

    public function hookDisplayBackOfficeFooter()
    {

        if (!Module::isEnabled($this->name))
            return;

        $addCustomBoostrap = false;
        if(Tools::version_compare(_PS_VERSION_, '1.7.5.0', '>=')){
            if(Tools::getValue('controller')=='AdminProducts'){
                   $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                   $url_arr = explode('products/',$actual_link);
                   if(isset($url_arr[1])){ 
                       $addCustomBoostrap = true;
                   }else{
                       return;
                   }
                }
        }else{
            if(Tools::getValue('controller')=='AdminProducts'){
            // return true;
                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
               // var_dump($actual_link);die();
                if (strpos($actual_link, 'product/form') === false) { 
                    return true;
                }else{
                    $addCustomBoostrap = true;
                }
            }
        }
        
        
                
        if (self::condition()) {
          //  die('sdfsdf');
            $modulepath = $this->_path . 'assets/';

            ob_start();
            if (isset(JsComposer::$sds_action_hooks['ps_admin_footer']))
                call_user_func(JsComposer::$sds_action_hooks['ps_admin_footer']);

            if (!empty(self::$registeredJS))
                foreach (self::$registeredJS as $custom_js)
                    $this->addJS($custom_js);

            $this->addJS('//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js');
            $this->addJS($modulepath . 'lib/ace-builds/src-min-noconflict/ace.js');
            $this->addJS($modulepath . 'js/backend/composer-tools.js');
            $this->addJS($modulepath . 'js/backend/composer-atts.js');
            $this->addJS($modulepath . 'js/backend/media-editor.js');
            $this->addJS($modulepath . 'lib/autosuggest/jquery.autoSuggest.js');
            $this->addJS($modulepath . 'lib/vc_chart/jquery.vc_chart.js');
            $this->addJS($modulepath . 'js/params/all.js'); 
            if($addCustomBoostrap){
                
                           $this->addJS($modulepath . 'js/1730/bootstrap.min.js');
                
            }
                 
            $this->addJS($modulepath . 'js/editors/panels.js');
            $this->addJS($modulepath . 'js/backend/composer-storage.js');
            $this->addJS($modulepath . 'js/backend/composer-models.js');
            $this->addJS($modulepath . 'js/select2.js');
            if (Tools::getValue('controller') !== 'VC_frontend') {
                $this->addJS($modulepath . 'js/backend/composer-view.js');
                $this->addJS($modulepath . 'js/backend/composer-custom-views.js');
                $this->addJS($modulepath . 'js/backend/deprecated.js');
            } else {
                $this->addJS(vc_asset_url('js/frontend_editor/shortcodes_builder.js'));
                $this->addJS(vc_asset_url('js/frontend_editor/models.js'));
                $this->addJS(vc_asset_url('js/frontend_editor/frontend_editor.js'));
                $this->addJS(vc_asset_url('js/frontend_editor/custom_views.js'));
                $this->addJS(vc_asset_url('js/frontend_editor/build.js'));
            }
            $this->addJS($modulepath . 'lib/vc_carousel/js/transition.js');
            $this->addJS($modulepath . 'lib/vc_carousel/js/vc_carousel.js');
            $this->addJS($modulepath . 'lib/progress-circle/ProgressCircle.js');


            return ob_get_clean();
        }
        return '';
    }

    public function hookdisplayBackOfficeTop($params)
    {
        return '<input type="file" name="import_vcc_anywhere" data-url="' . $this->context->link->getAdminLink('Adminvccontentanywhere') . '&ajax=1&action=ImportVccontent" id="import_vcc_anywhere" style="display:none;" />';
    }




    public function hookdisplayProductTab($params)
    {
        $id_product = Tools::getValue('id_product');
        $compileid = 'vcprdtb_'.pSQL($id_product);
        if (!$this->isCached('vc_prd_tab_title.tpl', $this->getCacheId(), $compileid)) {
            $vc_product_tab_style = Configuration::get('vc_product_tab_style');
            if($vc_product_tab_style == 'general'){
                return ;
            }
            if (!is_object($this->vctcbj)) {
                $this->vctcbj = new vcproducttabcreator();
            }
            $vctc = $this->vctcbj;
            if (!is_object($this->vccawobj)) {
                $this->vccawobj = vccontentanywhere::GetInstance();
            }
            $vcaw = $this->vccawobj;
            $results = $vctc->GetTabContentByPRDID($id_product, 'title');
            $this->context->smarty->assign(array(
                'vc_tab_title_results' => $results,
                'vccontentanywhereobj' => $vcaw,
                'vc_product_tab_style' => $vc_product_tab_style
            ));
        }
        return $this->display(__FILE__, 'views/templates/front/vc_prd_tab_title.tpl', $this->getCacheId(), $compileid);
    }

    public function hookdisplayProductTabContent($params)
    {
        $id_product = Tools::getValue('id_product');
        $compileid = 'prdtabcontent_' . pSQL($id_product);

        if (!$this->isCached('vc_prd_tab_content.tpl', $this->getCacheId(), $compileid)) {

            if (!is_object($this->vctcbj)) {
                $this->vctcbj = new vcproducttabcreator();
            }
            $vctc = $this->vctcbj;
            if (!is_object($this->vccawobj)) {
                $this->vccawobj = vccontentanywhere::GetInstance();
            }
            $vcaw = $this->vccawobj;
            $results = $vctc->GetTabContentByPRDID($id_product, 'content');
            $this->context->smarty->assign(array(
                'vccontentanywhereobj' => $vcaw,
                'vc_tab_content_results' => $results,
                'vc_product_tab_style' => Configuration::get('vc_product_tab_style')
            ));
        }
        return $this->display(__FILE__, 'views/templates/front/vc_prd_tab_content.tpl', $this->getCacheId(), $compileid);
    }
    

    public function hookDisplayFooter($params)
    {
        $rsts = $this->contenthookvalue('displayFooter');
        return $rsts;
    }

    public function hookdisplayLeftColumn()
    {
        $rsts = $this->contenthookvalue('displayLeftColumn');
        return $rsts;
    }

    public function hookdisplayBanner()
    {
        $rsts = $this->contenthookvalue('displayBanner');
        return $rsts;
    }

    public function hookdisplayTopColumn($params)
    {

        $rsts = $this->contenthookvalue('displayTopColumn');
        return $rsts;
    }

    public function hookdisplayTop($params)
    {
        $rsts = $this->contenthookvalue('displayTop');
        return $rsts;
    }

    public function hookdisplayHome($params)
    {
        $rsts = $this->contenthookvalue('displayHome');
        return $rsts;
    }

    public function hookdisplayLeftColumnProduct($params)
    {
        $rsts = $this->contenthookvalue('displayLeftColumnProduct');
        return $rsts;
    }

    public function hookdisplayRightColumnProduct($params)
    {
        $rsts = $this->contenthookvalue('displayRightColumnProduct');
        return $rsts;
    }

    public function hookdisplayRightColumn($params)
    {
        $rsts = $this->contenthookvalue('displayRightColumn');
        return $rsts;
    }

    public function hookdisplayProductContent($params)
    {
        $rsts = $this->contenthookvalue('displayProductContent');
        return $rsts;
    }

    public function hookdisplaySmartBlogLeft($params)
    {
        $rsts = $this->contenthookvalue('displaySmartBlogLeft');
        return $rsts;
    }

    public function hookdisplaySmartBlogRight($params)
    {
        $rsts = $this->contenthookvalue('displaySmartBlogRight');
        return $rsts;
    }

    public function hookdisplayFooterTop($params)
    {
        $rsts = $this->contenthookvalue('displayFooterTop');
        return $rsts;
    }

    public function hookdisplaySidearea($params)
    {
        $rsts = $this->contenthookvalue('displaySidearea');
        return $rsts;
    }

    public function hookdisplaynav($params)
    {
        $rsts = $this->contenthookvalue('displayNav');
        return $rsts;
    }

    public function hookdisplayMyAccountBlockfooter($params)
    {
        $rsts = $this->contenthookvalue('displayMyAccountBlockfooter');
        return $rsts;
    }

    public function hookdisplayMyAccountBlock($params)
    {
        $rsts = $this->contenthookvalue('displayMyAccountBlock');
        return $rsts;
    }

    public function hookdisplayMaintenance($params)
    {
        $rsts = $this->contenthookvalue('displayMaintenance');
        return $rsts;
    }

    public function hookdisplayFooterProduct($params)
    {
        $rsts = $this->contenthookvalue('displayFooterProduct');
        return $rsts;
    }

    public function hookactionObjectvccontentanywhereAddAfter($params)
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionObjectvccontentanywhereUpdateAfter($params)
    {
//        $this->_clearCache('jscomposer.tpl');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionObjectvccontentanywhereDeleteAfter($params)
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionObjectvcproducttabcreatorAddAfter($params)
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionObjectvcproducttabcreatorUpdateAfter($params)
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionObjectvcproducttabcreatorDeleteAfter($params)
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionAdminPerformanceControllerAfter($params)
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionvcproducttabcreatorUpdate()
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public function hookactionvccontentanywhereUpdate()
    {
//        $this->_clearCache('*');
        $this->vccClearCache();
        $this->vcProTabClearCache();
    }

    public static function GetLinkobj()
    {
        if (Tools::usingSecureMode())
            $useSSL = true;
        else
            $useSSL = false;

        $protocol_link = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
        $protocol_content = (isset($useSSL) AND $useSSL AND Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
        $link = new Link($protocol_link, $protocol_content);
        return $link;
    }

    public function getProductsList()
    {
        $vcc = new vccontentanywhere();
        echo $vcc->getProductsByName();
    }

    public static function getPsImgSizesOption()
    {
        $db = Db::getInstance();
        $tablename = _DB_PREFIX_ . 'image_type';
        $sizes = $db->executeS("SELECT name FROM {$tablename} ORDER BY name ASC");
        $options = array('Default' => '');
        if (!empty($sizes)) {
            foreach ($sizes as $size) {
                $options[$size['name']] = $size['name'];
            }
        }
        return $options;
    }

    private static function productCategoryWalaker($children, &$options)
    {

        foreach ($children as $cat) {
            $options[$cat['name']] = $cat['id_category'];
            if (isset($cat['children']) && !empty($cat['children']))
                self::productCategoryWalaker($cat['children'], $options);
        }
    }

    public static function getCategoriesOption()
    {

        $categories = Category::getNestedCategories();

        $options = array('Default' => '');
        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $options[$cat['name']] = $cat['id_category'];
                if (isset($cat['children']) && !empty($cat['children']))
                    self::productCategoryWalaker($cat['children'], $options);
            }
        }

        return $options;
    }

    /**
     * @since 4.4
     */
    public function vc_get_autocomplete_suggestion()
    {
        $q = Tools::getValue('q');
        $type = Tools::getValue('vc_catalog_type');
        $limit = Tools::getValue('limit');
        $query = array(
            'keyword' => $q,
            'type' => $type,
            'limit' => $limit,
        );

        $this->vc_render_suggestion($query);
    }

    /**
     * @since 4.4
     *
     * @param $query
     * @param $tag
     * @param $param_name
     *
     * vc_filter: vc_autocomplete_{tag}_{param_name}_callback - hook to get suggestions from ajax. (here you need to hook).
     */
    private function vc_render_suggestion($query)
    {

        $this->productIdAutocompleteSuggester($query);
        die(''); // if nothing found..
    }

    private function productIdAutocompleteSuggester($query)
    {

        switch ($query['type']) {
            case 'product':
                $this->getProductsList();
                break;
            case 'category':
                $vcc = new vccontentanywhere();
                echo $vcc->getCatsByName();
                break;
            case 'manufacturer':
                $vcc = new vccontentanywhere();
                echo $vcc->getManufacturersByName();
                break;
            case 'supplier':
                $vcc = new vccontentanywhere();
                echo $vcc->getSuppliersByName();
                break;
        }
    }

    public static function productIdAutocompleteRender($query)
    {
        if (!empty($query['value'])) {
            $elemid = $elemName = '';
            $context = Context::getContext();
            switch ($query['type']) {
                case 'product':
                    $product = new Product((int) $query['value']);

                    if (!empty($product) && isset($product->name)) {
                        $elemid = (int) $query['value'];
                        $elemName = $product->name[$context->language->id];
                    }
                    break;
                case 'category':
                    $cat = new Category((int) $query['value']);

                    if (!empty($cat) && isset($cat->name)) {
                        $elemid = (int) $query['value'];
                        $elemName = $cat->name[$context->language->id];
                    }
                    break;
                case 'manufacturer':
                    $man = new Manufacturer((int) $query['value']);

                    if (!empty($man) && isset($man->name)) {
                        $elemid = (int) $query['value'];
                        $elemName = $man->name;
                    }
                    break;
                case 'supplier':
                    $sup = new Supplier((int) $query['value']);

                    if (!empty($sup) && isset($sup->name)) {
                        $elemid = (int) $query['value'];
                        $elemName = $sup->name;
                    }
                    break;
            }
            if (!empty($elemid))
                return array($elemid, $elemName);
        }
        return false;
    }

    // new method added
    public function hookVcShortcodesCssClass($params)
    {
        
    }

    /**
     * register custom controllers to vc engine
     * @param type $attribs
     * @return boolean
     */
    public static function AddVcExternalControllers($attribs)
    {
        /* $attribs = array(
         * 'test' => array(
         *  'controller' => 'AdminTest',
         *  'identifier' => 'id_test',
         *  'shortname' => 'tst',
         *  'field' => 'content',
         * ))
         */
        if (!empty($attribs)) {

            $controllers = Configuration::get('VC_ENQUEUED_CONTROLLERS');
            $controllers = Tools::jsonDecode($controllers, true);

            if (empty($controllers)) {
                $controllers = array();
            }
            $key = false;
            foreach ($attribs as $id => $attr) {
                if (!array_key_exists($id, $controllers)) {
                    $key = $id;
                    $controllers[$id] = $attr;
                }
            }
            if($key) $controllers[$key]['module_status'] = '1';
            if($key) $controllers[$key]['module_frontend_status'] = '0';
            if($key) $controllers[$key]['module_backend_status'] = '0';
            if($key) $controllers[$key]['type'] = 'custom';
            Configuration::updateValue('VC_ENQUEUED_CONTROLLERS', Tools::jsonEncode($controllers));
            return true;
        }
        return false;
    }

    /**
     * deregister custom controllers from vc engine
     * @param type $attribs
     * @return boolean
     */
    public static function RemoveVcExternalControllers($attribs)
    {

        if (!empty($attribs)) {
            $controllers = Configuration::get('VC_ENQUEUED_CONTROLLERS');
            $controllers = Tools::jsonDecode($controllers, true);
            if (empty($controllers)) {
                $controllers = array();
            }
            foreach ($attribs as $id) {
                if (isset($controllers[$id])) {
                    unset($controllers[$id]);
                }
            }
            Configuration::updateValue('VC_ENQUEUED_CONTROLLERS', Tools::jsonEncode($controllers));
            return true;
        }
        return false;
    }

    /**
     * return full image path url
     * @param type $img_id integer
     * @return string
     */
    public static function getFullImageUrl($img_id)
    {
        $link_to = self::$_url . 'uploads/' . self::get_media_thumbnail_url($img_id);
        $link_to = self::ModifyImageUrl($link_to);
        return $link_to;
    }

    // /* Please uncomment this bellow function for using any custom hook */
    public function __call($function, $args)
    {
        $hook = substr($function, 0, 4);
        if ($hook == 'hook') {
            $hook_name = substr($function, 4);

            return $this->contenthookvalue($hook_name);
        } else {
            return false;
        }
    }

    public static function vcTinymcePluginAdd($name)
    {

        $old_vc_tinymce_plugins = unserialize(Configuration::get('VC_TINYMCE_PLUGIN'));

        if (isset($old_vc_tinymce_plugins) && ($old_vc_tinymce_plugins == '')) {
            $old_vc_tinymce_plugins = array();
        }

        if (in_array($name, $old_vc_tinymce_plugins)) {
            $name = '';
        } else {
            $old_vc_tinymce_plugins[] = $name;
        }


        $updated_hook_list = serialize($old_vc_tinymce_plugins);
        Configuration::updateValue('VC_TINYMCE_PLUGIN', $updated_hook_list);
    }

    public static function vcTinymcePluginCssAdd($name)
    {

        $old_vc_tinymce_plugins = unserialize(Configuration::get('VC_TINYMCE_PLUGIN_CSS'));

        if (isset($old_vc_tinymce_plugins) && ($old_vc_tinymce_plugins == '')) {
            $old_vc_tinymce_plugins = array();
        }

        if (in_array($name, $old_vc_tinymce_plugins)) {
            $name = '';
        } else {
            $old_vc_tinymce_plugins[] = $name;
        }


        $updated_hook_list = serialize($old_vc_tinymce_plugins);
        Configuration::updateValue('VC_TINYMCE_PLUGIN_CSS', $updated_hook_list);
    }

    public static function vcTinymcePluginRemove($name)
    {

        if ($name != '') {

            $old_vc_tinymce_plugins = unserialize(Configuration::get('VC_TINYMCE_PLUGIN'));

            if ($old_vc_tinymce_plugins == '') {
                $old_vc_tinymce_plugins = array();
            }


            $key = array_search($name, $old_vc_tinymce_plugins);

            unset($old_vc_tinymce_plugins[$key]);

            $updated_vc_tinymce_plugins = serialize($old_vc_tinymce_plugins);
            Configuration::updateValue('VC_TINYMCE_PLUGIN', $updated_vc_tinymce_plugins);
        }
    }
    
    public static function vcTinymcePluginCssRemove($name)
    {

        if ($name != '') {

            $old_vc_tinymce_plugins = unserialize(Configuration::get('VC_TINYMCE_PLUGIN_CSS'));

            if ($old_vc_tinymce_plugins == '') {
                $old_vc_tinymce_plugins = array();
            }


            $key = array_search($name, $old_vc_tinymce_plugins);

            unset($old_vc_tinymce_plugins[$key]);

            $updated_vc_tinymce_plugins = serialize($old_vc_tinymce_plugins);
            Configuration::updateValue('VC_TINYMCE_PLUGIN_CSS', $updated_vc_tinymce_plugins);
        }
    }
    public function vccClearCache()
    {
        $this->_clearCache('jscomposer.tpl');
    }
    public function vcProTabClearCache()
    {
        $this->_clearCache('vc_prd_tab_title.tpl');
        $this->_clearCache('vc_prd_tab_content.tpl');
    }
    public function hookVcAllowedImgAttrs($params)
    {
        
    }
    public function vcTranslate($key)
    {
        if(isset(self::$vc_translations[$key])){
            return self::$vc_translations[$key];
        }
        return $key;
    }
    /* new fixings added*/
    public function getInnerActions($actionname)
    {
        if(isset(self::$front_editor_actions[$actionname]) && self::$front_editor_actions[$actionname]){
            return self::$front_editor_actions[$actionname];
        }
    }
}
