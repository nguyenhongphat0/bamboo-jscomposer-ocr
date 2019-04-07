<?php
require_once (dirname(__FILE__) . '/AdminVcImagesController.php');
require_once (dirname(__FILE__) . '/../../classes/VcImageType.php');
require_once (dirname(__FILE__) . '/../../classes/vccontentanywhere.php');

if (!defined('_PS_JSCOMPOSER_IMPORT_DIR_'))
    define('_PS_JSCOMPOSER_IMPORT_DIR_', _PS_ROOT_DIR_ . '/modules/jscomposer/import/');

class AdminvccontentanywhereController extends ModuleAdminController
{

    protected $countries_array = array();
    protected $position_identifier = 'id_vccontentanywhere';
    public $asso_type = 'shop';
    private $original_filter = '';
    private $exportzippath, $zipArc;

    public function __construct()
    {
        $this->table = 'vccontentanywhere';
        $this->className = 'vccontentanywhere';
        $this->lang = true;
        $this->deleted = false;
        $this->module = 'jscomposer';
        $this->explicitSelect = true;
        $this->_defaultOrderBy = 'position';
        $this->allow_export = false;
        $this->_defaultOrderWay = 'DESC';
        $this->bootstrap = true;
        $this->context = Context::getContext();
        if (Shop::isFeatureActive())
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        parent::__construct();
        $this->fields_list = array(
            'id_vccontentanywhere' => array(
                'title' => $this->l('Id'),
                'width' => 100,
                'type' => 'text',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'width' => 440,
                'type' => 'text',
                'lang' => true,
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
            'hook_name' => array(
                'title' => $this->l('Hook'),
                'type' => 'text',
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'position' => 'position',
                'align' => 'center'
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => '70',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'filter' => false,
                'search' => false
            )
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );

   
          $this->checkUpdate();

    }

   private function checkUpdate(){
        // $vc = new JsComposer();
        $Smartlisence = new Smartlisence();
        $this_val = array(
                'version' => JsComposer::$vc_version,
                'module_name' => JsComposer::$vc_mode_name,
                'theme_name'=> basename(_THEME_DIR_),
            );
        $Smartlisence->checkUpdate($this_val);
    }
    

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        $this->addJqueryPlugin('autocomplete');
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        if ($order_way == null)
            $order_way = 'ASC';

        return parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
    }

    public function init()
    {
        parent::init();
        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'vccontentanywhere_shop sbs ON a.id_vccontentanywhere=sbs.id_vccontentanywhere && sbs.id_shop IN(' . implode(',', Shop::getContextListShopID()) . ')';
        $this->_select = 'sbs.id_shop';
        $this->_defaultOrderBy = 'a.position';
        $this->_defaultOrderWay = 'DESC';
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $this->_group = 'GROUP BY a.id_vccontentanywhere';
        }
        $this->_select = 'a.position position';
        if (Tools::isSubmit('exportvccontentanywhere')) {
            $this->exportVCCanywhere();
        }
    }

    public function replaceImageIdsDuringExport($matches)
    {

        if(!(bool)preg_match('/(\d+,?)/', $matches[2]) || empty($matches[2])){ // to prevent unusual sql breakup. here id must be set.
            return "{$matches[1]}=\"\"";
        }

        $db = Db::getInstance();
        $images = $db->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'vc_media WHERE id_vc_media IN(' . $matches[2] . ') ORDER BY FIELD( id_vc_media, ' . $matches[2] . ')', true, false);

        $upload_path = vc_manager()->composer_settings['UPLOADS_DIR'];

        $imagenames = '';

        foreach ($images as $k => $image) {
            $current_path = $upload_path.$image['subdir'];
            
            if ($k > 0)
                $imagenames .= ',';

            if(!empty($image['subdir'])){
                $subdirs = explode(DIRECTORY_SEPARATOR, $image['subdir']);
                $series = '';
                foreach($subdirs as $sd){
                    $series .= $sd;
                    if(!empty($sd) && $this->zipArc->locateName("uploads/{$series}") === FALSE){
                        $this->zipArc->addEmptyDir("uploads/{$series}");
                    }
                }
            }
            $this->zipArc->addFile($current_path . $image['file_name'], 'uploads/' . $image['subdir'] . $image['file_name']);

            $imagenames .= $image['subdir'].$image['file_name'];
        }

        return "{$matches[1]}=\"{$imagenames}\"";
    }

    public function exportVCCanywhere()
    {
        $db = Db::getInstance();

        $data = array();
        $id_shop = (int) Context::getContext()->shop->id;
        $mainContents = $db->executeS('SELECT v.* FROM ' . _DB_PREFIX_ . 'vccontentanywhere v INNER JOIN `' . _DB_PREFIX_ . 'vccontentanywhere_shop` vs ON (v.`id_vccontentanywhere` = vs.`id_vccontentanywhere` AND vs.`id_shop` = ' . $id_shop . ')');

        if (!empty($mainContents)) {
            $vc_image_allowed_attr = JsComposer::$vc_image_allowed_attr . Hook::exec('VcAllowedImgAttrs');
            $filename = 'exportvccanywhere' . uniqid() . '.zip';
            $this->exportzippath = _PS_ROOT_DIR_ . "/upload/$filename";
            $pattern = '/(' . $vc_image_allowed_attr . ')\=\"([^"]+)\"+/';


            $this->zipArc = new ZipArchive();
            if ($this->zipArc->open($this->exportzippath, ZipArchive::CREATE) === TRUE) {

                $this->zipArc->addEmptyDir('uploads');

                foreach ($mainContents as $index => $content) {
                    $id = $content['id_vccontentanywhere'];
                    unset($content['id_vccontentanywhere']);
                    $data[$index] = $content;
                    $langContent = $db->executeS('SELECT title, content FROM ' . _DB_PREFIX_ . 'vccontentanywhere_lang WHERE id_vccontentanywhere=' . $id);

                    foreach ($langContent as $n => $lang) {

                        $langContent[$n]['content'] = preg_replace_callback($pattern, array($this, 'replaceImageIdsDuringExport'), $lang['content']);
                    }

                    $data[$index]['lang'] = $langContent;
                }
                
                $str = urlencode(Tools::jsonEncode($data));
                $this->zipArc->addFromString('export.txt', $str);
                $this->zipArc->close();
                $zipContent = Tools::file_get_contents($this->exportzippath);
                @unlink($this->exportzippath);
                header('Content-Description: File Transfer');
                header('Content-Type: application/zip; charset=UTF-8');
                header("Content-Disposition: attachment; filename=" . $filename . ";");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . strlen($zipContent));
                echo $zipContent;
            }
        } else {
            $url = $this->context->link->getAdminLink('Adminvccontentanywhere');
            Tools::redirectAdmin($url);
        }
        die();
    }

    public function putImageIdsDuringImport($matches)
    {
        if (empty($matches[2]))
            return "{$matches[1]}=\"\"";
        $db = Db::getInstance();
        $imagelists = explode(',', $matches[2]);
        $upload_path = vc_manager()->composer_settings['UPLOADS_DIR'];
        $root_path = $this->exportzippath;
        $imagenames = '';
        $vcImages = new AdminVcImagesController();
        foreach ($imagelists as $k => $singlename) {
            $fname = basename($singlename);
//            $fname = "'{$singlename}'";
            $folders = false;
            if(strpos($singlename, '/') !== FALSE){
                $folders = substr($singlename, 0, strrpos($singlename, $fname));
                $foldersarr = explode('/', $folders);
                $series = '';
                foreach($foldersarr as $index => $folder){
                    if(!empty($folder)){
                        $series .= $folder;
                        if(!is_dir($upload_path.$series)){
                            @mkdir($upload_path.$series);
                        }
                        $series .= '/';
                    }
                }
            }
            $search = 'SELECT * FROM ' . _DB_PREFIX_ . 'vc_media WHERE file_name="' . $fname.'"';
            if($folders){
                $search .= " AND subdir='{$folders}'";
            }
            
            if ($k > 0)
                $imagenames .= ',';
            $result = $db->getRow($search, false);
            if (!empty($result)) {
                $imagenames .= $result['id_vc_media'];
                if (!file_exists($upload_path . $singlename))
                    @copy("{$root_path}/uploads/{$singlename}", $upload_path . $singlename);
            }else {
                if (file_exists("{$root_path}/uploads/{$singlename}")) {
                    if($folders){
                        $db->execute("INSERT INTO " . _DB_PREFIX_ . "vc_media(file_name, subdir) VALUES('{$fname}', '{$folders}')");
                    }else{
                        $db->execute("INSERT INTO " . _DB_PREFIX_ . "vc_media(file_name) VALUES('{$fname}')");
                    }
                    $imagenames .= $db->Insert_ID();
                    @copy("{$root_path}/uploads/{$singlename}", $upload_path . $singlename);
                }
            }
        }

        $formats = VcImageType::getImagesTypes('active');
        $vcImages->_regenerateNewImages($upload_path, $formats);

        return "{$matches[1]}=\"{$imagenames}\"";
    }

    public function ImportVccontent($theme_zip_file, $sandbox)
    {

        $this->exportzippath = $sandbox . 'uploaded/';

        if (!Tools::ZipExtract($theme_zip_file, $this->exportzippath))
            $this->errors[] = $this->l('Error during zip extraction');
        else {

            if (!file_exists($sandbox . 'uploaded/export.txt'))
                $this->errors[] = $this->l('Bad configuration file');
            else {
                $vc_image_allowed_attr = JsComposer::$vc_image_allowed_attr . Hook::exec('VcAllowedImgAttrs');
                $pattern = '/(' . $vc_image_allowed_attr . ')\=\"([^"]+)\"+/';

                $str = Tools::file_get_contents($this->exportzippath . '/export.txt');
                $db = Db::getInstance();
                $contents = Tools::jsonDecode(urldecode($str), true);

                $vccanywhere = _DB_PREFIX_ . 'vccontentanywhere';
                $vccanywhere_lang = _DB_PREFIX_ . 'vccontentanywhere_lang';
                $vccanywhere_shop = _DB_PREFIX_ . 'vccontentanywhere_shop';

                if (!empty($contents)) {
                    $id_shop = $this->context->shop->id;
                    $languages = Language::getLanguages();

                    foreach ($contents as $content) {

                        //start from here...
                        $langarray = $content['lang'];

                        unset($content['lang']);

                        if (isset($content['blg_page']))
                            unset($content['blg_page']);

                        if (isset($content['blg_specify']))
                            unset($content['blg_specify']);

                        $fields = array_keys($content);

                        $mainsql = "INSERT INTO {$vccanywhere}(" . implode(',', $fields) . ") VALUES";
                        $mainsql .= '(';
                        $loop = 0;

                        foreach ($content as $colname => $coldata) {

                            if ($loop > 0)
                                $mainsql .= ',';
                            if (is_numeric($coldata)) {
                                if ($colname == 'position') {
                                    $mainsql .= vccontentanywhere::getHigherPosition() + 1;
                                } else
                                    $mainsql .= $coldata;
                            }elseif (is_string($coldata)) {
                                $mainsql .= "'{$coldata}'";
                            } elseif (empty($coldata)) {
                                $mainsql .= '" "';
                            }
                            $loop++;
                        }
                        $mainsql .= ')';

                        $db->execute($mainsql);
                        $id_vccontentanywhere = $db->Insert_ID();
                        $shopsql = "INSERT INTO {$vccanywhere_shop}(`id_vccontentanywhere`,`id_shop`) VALUES({$id_vccontentanywhere},{$id_shop})";
                        $db->execute($shopsql);

                        $langsql = "INSERT INTO {$vccanywhere_lang} VALUES";
                        foreach ($languages as $ind => $lang) {

                            if ($ind > 0)
                                $langsql .= ',';

                            if (isset($langarray[$ind]) && !empty($langarray[$ind])) {
                                $importlang = $langarray[$ind];
                            } else
                                $importlang = $langarray[0];

                            $importlang['content'] = preg_replace_callback($pattern, array($this, 'putImageIdsDuringImport'), $importlang['content']);

                            $langsql .= "({$id_vccontentanywhere},{$lang['id_lang']},'{$importlang['title']}','" . addcslashes($importlang['content'], '\'') . "')";
                        }
                        $db->execute($langsql);
                    }
                }

                $this->removeTheExportDir($this->exportzippath);
            }
        }
    }

    private function removeTheExportDir($path)
    {
        $files = scandir($path);
        foreach ($files as $ff) {
            if ($ff != '.' && $ff != '..') {
                if (is_dir("{$path}/{$ff}")) {
                    $this->removeTheExportDir("{$path}/{$ff}");
                } else
                    @unlink("{$path}/{$ff}");
            }
        }
        return @rmdir($path);
    }

    public function ProcessVccontent()
    {
        
    }

    public function initContent()
    {
        if ($this->display == 'list')
            $this->display = '';
        if (isset($this->display) && method_exists($this, 'render' . $this->display)) {
            $this->content .= $this->initPageHeaderToolbar();

            $this->content .= $this->{'render' . $this->display}();
            $this->context->smarty->assign(array(
                'content' => $this->content,
                'show_page_header_toolbar' => $this->show_page_header_toolbar,
                'page_header_toolbar_title' => $this->page_header_toolbar_title,
                'page_header_toolbar_btn' => $this->page_header_toolbar_btn
            ));
        } else {
            return parent::initContent();
        }
    }

    public function renderImportContent()
    {
        $toolbar_btn['save'] = array(
            'href' => '#',
            'desc' => $this->l('Save')
        );

        $fields_form[0] = array(
            'form' => array(
                'tinymce' => false,
                'legend' => array(
                    'title' => $this->l('Import from your computer'),
                    'icon' => 'icon-picture'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Zip file'),
                        'desc' => $this->l('Browse your computer files and select the Zip file for your new theme.'),
                        'name' => 'contentanywherearchive'
                    ),
                ),
                'submit' => array(
                    'id' => 'zip',
                    'title' => $this->l('Save'),
                )
            ),
        );

        /*   $fields_form[1] = array(
          'form' => array(
          'tinymce' => false,
          'legend' => array(
          'title' => $this->l('Import from the web'),
          'icon' => 'icon-picture'
          ),
          'input' => array(
          array(
          'type' => 'text',
          'label' => $this->l('Archive URL'),
          'desc' => $this->l('Indicate the complete URL to an online Zip file that contains your new theme. For instance, "http://example.com/files/contentanywhere.zip".'),
          'name' => 'contentanywherearchiveUrl'
          ),
          ),
          'submit' => array(
          'title' => $this->l('Save'),
          )
          ),
          );
         */

        $jscomposer_archive_server = array();
        $files = scandir(_PS_JSCOMPOSER_IMPORT_DIR_);
        $jscomposer_archive_server[] = '-';

        foreach ($files as $file) {
            if (is_file(_PS_JSCOMPOSER_IMPORT_DIR_ . $file) && substr(_PS_JSCOMPOSER_IMPORT_DIR_ . $file, -4) == '.zip') {
                $jscomposer_archive_server[] = array(
                    'id' => basename(_PS_JSCOMPOSER_IMPORT_DIR_ . $file),
                    'name' => basename(_PS_JSCOMPOSER_IMPORT_DIR_ . $file)
                );
            }
        }

        $fields_form[2] = array(
            'form' => array(
                'tinymce' => false,
                'legend' => array(
                    'title' => $this->l('Import from FTP'),
                    'icon' => 'icon-picture'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select the archive'),
                        'name' => 'jscomposer_archive_server',
                        'desc' => $this->l('This selector lists the Zip files that you uploaded in the \'/import\' folder.'),
                        'options' => array(
                            'id' => 'id',
                            'name' => 'name',
                            'query' => $jscomposer_archive_server,
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );


        $helper = new HelperForm();

        $helper->currentIndex = $this->context->link->getAdminLink('Adminvccontentanywhere', false) . '&action=importcontent';
        $helper->token = Tools::getAdminTokenLite('Adminvccontentanywhere');
        $helper->show_toolbar = true;
        $helper->toolbar_btn = $toolbar_btn;
        $helper->fields_value['contentanywherearchiveUrl'] = '';
        $helper->fields_value['jscomposer_archive_server'] = array();
        $helper->multiple_fieldsets = true;
        $helper->override_folder = $this->tpl_folder;
        $helper->languages = $this->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm($fields_form);
    }

    public function processImportContent()
    {

// Array
// (
//     [controller] => Adminvccontentanywhere
//     [action] => importcontent
//     [token] => cf15657848babf311769487d908e123a
//     [submitAddconfiguration] => 1
//     [contentanywherearchive] => Animation Block.zip
//     [jscomposer_archive_server] => 
// )

        $this->display = 'importcontent';
        if (defined('_PS_HOST_MODE_'))
            return true;


        if (isset($_FILES['contentanywherearchive']) && Tools::isSubmit('jscomposer_archive_server')) {

        // echo '<pre>';
        // print_r($_REQUEST);
        // echo '</pre>';

            $uniqid = uniqid();
            $sandbox = _PS_CACHE_DIR_ . 'sandbox' . DIRECTORY_SEPARATOR . $uniqid . DIRECTORY_SEPARATOR;
            mkdir($sandbox);
            $archive_uploaded = false;

            if (Tools::getValue('contentanywherearchive') != '') {
                $uploader = new Uploader('contentanywherearchive');
                $uploader->setAcceptTypes(array('zip'));
                $uploader->setSavePath($sandbox);
                $file = $uploader->process('uploaded.zip');

                if ($file[0]['error'] === 0) {
                    if (Tools::ZipTest($sandbox . 'uploaded.zip'))
                        $archive_uploaded = true;
                    else
                        $this->errors[] = $this->l('Zip file seems to be broken');
                } else
                    $this->errors[] = $file[0]['error'];
            }
            elseif (Tools::getValue('contentanywherearchiveUrl') != '') {

                if (!Validate::isModuleUrl($url = Tools::getValue('contentanywherearchiveUrl'), $this->errors))
                    $this->errors[] = $this->l('Only zip files are allowed');
                elseif (!Tools::copy($url, $sandbox . 'uploaded.zip'))
                    $this->errors[] = $this->l('Error during the file download');
                elseif (Tools::ZipTest($sandbox . 'uploaded.zip'))
                    $archive_uploaded = true;
                else
                    $this->errors[] = $this->l('Zip file seems to be broken');
            }
            elseif (Tools::getValue('jscomposer_archive_server') != '') {
                $filename = _PS_JSCOMPOSER_IMPORT_DIR_ . Tools::getValue('jscomposer_archive_server');
                if (substr($filename, -4) != '.zip')
                    $this->errors[] = $this->l('Only zip files are allowed');
                elseif (!copy($filename, $sandbox . 'uploaded.zip'))
                    $this->errors[] = $this->l('An error has occurred during the file copy.');
                elseif (Tools::ZipTest($sandbox . 'uploaded.zip'))
                    $archive_uploaded = true;
                else
                    $this->errors[] = $this->l('Zip file seems to be broken');
            } else
                $this->errors[] = $this->l('You must upload or enter a location of your zip');

            if ($archive_uploaded)
                $this->ImportVccontent($sandbox . 'uploaded.zip', $sandbox);

            Tools::deleteDirectory($sandbox);

            if (count($this->errors) > 0)
                $this->display = 'importcontent';
            else
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('Adminvccontentanywhere') . '&conf=18');
        }
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if (!isset($this->display)) {
            $this->page_header_toolbar_btn['import_vccontentanywhere'] = array(
                'href' => self::$currentIndex . '&action=importcontent&token=' . $this->token,
                'desc' => $this->l('Import', null, null, false),
                'icon' => 'process-icon-import'
            );

            $this->page_header_toolbar_btn['export_vccontentanywhere'] = array(
                'href' => self::$currentIndex . '&export' . $this->table . '&token=' . $this->token,
                'desc' => $this->l('Export', null, null, false),
                'icon' => 'process-icon-export'
            );
        }
        if ($this->display == 'edit') {
            if (($vccontentanywhere = $this->loadObject(true)) && $vccontentanywhere->isAssociatedToShop()) {
                if (isset($this->tabAccess['add']) && $this->tabAccess['add'])
                    $this->page_header_toolbar_btn['duplicate'] = array(
                        'short' => $this->l('Duplicate', null, null, false),
                        'href' => $this->context->link->getAdminLink('Adminvccontentanywhere', true) . '&id_vccontentanywhere=' . (int) $vccontentanywhere->id . '&duplicatevccontentanywhere',
                        'desc' => $this->l('Duplicate', null, null, false),
                    );
            }
        }
        if ($this->display == 'export')
            $this->toolbar_title[] = $this->l('Export theme');
    }

    public function renderList()
    {
        if (isset($this->_filter) && trim($this->_filter) == '')
            $this->_filter = $this->original_filter;
        $this->addRowAction('edit');
        $this->addRowAction('duplicate');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function renderForm()
    {
        $vc_is_edit = false;
        $vccanywhere = '';
        $prd_specify_values = '';
        $cat_specify_values = '';
        $cms_specify_values = '';
        $display_type_values = '';
        $prd_page_values = '';
        $cat_page_values = '';
        $cms_page_values = '';
        $exception_values = '';
        $products_list_array = array();
        $exception = vccontentanywhere::displayModuleExceptionList();
        if (Tools::getvalue('id_vccontentanywhere')) {
            $vc_is_edit = true;
            $vccontentanywhere = new vccontentanywhere(Tools::getvalue('id_vccontentanywhere'));
            $vccanywhere = $vccontentanywhere;
            $prd_specify_values = $vccontentanywhere->prd_specify;

            $prd_specify_values = $vccontentanywhere->getFilterValueByContentAnyWhereId($vccontentanywhere->id, 1);
            $products_list_array = $vccontentanywhere->getProductsById($prd_specify_values);
            $cat_specify_values = $vccontentanywhere->getFilterValueByContentAnyWhereId($vccontentanywhere->id, 2);
            // print_r( $cat_specify_values);
            //print_r( $vccontentanywhere->cat_specify);
            $cat_sqlids = '';
            foreach ($cat_specify_values as $k => $id) {
                // print_r($id['id_specify_page']);
                if ($k > 0)
                    $cat_sqlids .= ',';
                $cat_sqlids .= $id['id_specify_page'];
            }
            // print_r( $cat_sqlids);
//            $prd_specify_values = $prd_specify_values;
            $cat_specify_values = $cat_sqlids;
            $cms_specify_values = $vccontentanywhere->cms_specify;
            $display_type_values = $vccontentanywhere->display_type;

            $prd_page_values = $vccontentanywhere->prd_page;
            $cat_page_values = $vccontentanywhere->cat_page;
            $cms_page_values = $vccontentanywhere->cms_page;
            $exception_values = $vccontentanywhere->exception;
        }
        $vccaw = new vccontentanywhere();
        $getAllCMSPage = $vccaw->getAllCMSPage();
//        $prd = $vccaw->getAllProductsByCats();
        $cat = $vccaw->generateCategoriesOption(Category::getNestedCategories(null, (int) Context::getContext()->language->id, true));

//        $GetAllmodules_list = $vccaw->GetAllFilterModules();
//        if (Tools::getvalue('id_vccontentanywhere')) {
//            $module_hook_list = $vccaw->getModuleHookbyedit($vccanywhere->modules_list);
//        } else {
//            $module_hook_list = $vccaw->GetAllHooks();
//        }
        $GetAllHook = $vccaw->GetAllHooks();
        $vc_ajax_url = Context::getContext()->link->getAdminLink('VC_ajax') . '&hook_filter=1';
        $GetAlldisplayHooks = array();

        require_once(dirname(__FILE__) . '/../../include/helpers/hook.php');



        $customhooks = $this->get_all_hooks_handle();


        $i = 0;
        if (isset($customhooks) && !empty($customhooks)) {
            foreach ($customhooks as $values) {

                $GetAlldisplayHooks[] = array('id' => $values, 'name' => $values);

                $i++;
            }
        }


        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('VC Content Anywhere'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'lang' => true,
                    'required' => true,
                    'desc' => $this->l('Enter Your Title')
                ),
//                array(
//                    'type' => 'vc_content_mod_type',
//                    'name' => 'vc_content_mod_name',
//                    'vc_is_edit' => $vc_is_edit,
//                    'vc_ajax_url' => $vc_ajax_url,
//                ),
//                array(
//                    'type' => 'switch',
//                    'label' => $this->l('Simple Content Type'),
//                    'name' => 'content_type',
//                    'required' => false,
//                    'class' => 'content_type_class',
//                    'is_bool' => true,
//                    'values' => array(
//                        array(
//                            'id' => 'content_type_id_1',
//                            'value' => 1,
//                            'label' => $this->l('Enabled')
//                        ),
//                        array(
//                            'id' => 'content_type_id_0',
//                            'value' => 0,
//                            'label' => $this->l('Disabled')
//                        )
//                    )
//                ),
//                array(
//                    'type' => 'select',
//                    'name' => 'modules_list',
//                    'label' => $this->l('Select Module'),
//                    'options' => array(
//                        'query' => $GetAllmodules_list,
//                        'id' => 'id',
//                        'name' => 'name'
//                    )
//                ),
//                array(
//                    'type' => 'select',
//                    'name' => 'module_hook_list',
//                    'label' => $this->l('Available Module Hook'),
//                    'options' => array(
//                        'query' => $module_hook_list,
//                        'id' => 'id',
//                        'name' => 'name'
//                    )
//                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'rows' => 10,
                    'cols' => 62,
                    'class' => 'vc_content_class rte',
                    'lang' => true,
                    'autoload_rte' => true,
                    // 'required' => true,
                    'desc' => $this->l('Enter Your Description')
                ),
                array(
                    'type' => 'exceptionfieldtype',
                    'name' => 'exceptionfieldtype',
                    'vc_is_edit' => $vc_is_edit,
                    'exception_values' => $exception_values,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Set Exception'),
                    'name' => 'exception_type',
                    'required' => false,
                    'class' => 'exception_class',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'exception_id_1',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'exception_id_0',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Exceptions'),
                    'name' => 'exception_temp',
                    'class' => 'exception_class',
                    'id' => 'exception_id',
                    'multiple' => true,
                    'options' => array(
                        'query' => $exception,
                        'id' => 'id_exception',
                        'name' => 'name'
                    )
                ),
                // exception
                array(
                    'type' => 'vc_content_type',
                    'name' => 'title',
                    'vc_is_edit' => $vc_is_edit,
                    'prd_specify_values' => $prd_specify_values,
                    'cat_specify_values' => $cat_specify_values,
                    'cms_specify_values' => $cms_specify_values,
                    'display_type_values' => $display_type_values,
                    'prd_page_values' => $prd_page_values,
                    'cat_page_values' => $cat_page_values,
                    'cms_page_values' => $cms_page_values,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show All Page'),
                    'name' => 'display_type',
                    'required' => false,
                    'class' => 'display_type_class',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'display_type_id_1',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'display_type_id_0',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ), array(
                    'type' => 'switch',
                    'label' => $this->l('Show All Product Page'),
                    'name' => 'prd_page',
                    'required' => false,
                    'class' => 'prd_page_class',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'prd_page_id_1',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'prd_page_id_0',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
//                array(
//                    'type' => 'select',
//                    'label' => $this->l('Select Product'),
//                    'name' => 'prd_specify_temp',
//                    'class' => 'prd_specify_class',
//                    'id' => 'prd_specify_id',
//                    'multiple' => true,
//                    'options' => array(
//                        'query' => $prd,
//                        'id' => 'id_product',
//                        'name' => 'name'
//                    )
//                ),
                array(
                    'type' => 'ajaxproducts',
                    'label' => $this->l('Select Products'),
                    'name' => 'prd_specify_temp',
                    'class' => 'prd_specify_class',
                    'id' => 'prd_specify_id',
                    'multiple' => true,
                    'saved' => $products_list_array,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show All Category Page'),
                    'name' => 'cat_page',
                    'required' => false,
                    'class' => 'cat_page_class',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'cat_page_id_1',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'cat_page_id_0',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Category'),
                    'name' => 'cat_specify_temp',
                    'class' => 'cat_specify_class',
                    'id' => 'cat_specify_id',
                    'multiple' => true,
                    'options' => array(
                        'query' => $cat,
                        'id' => 'id_category',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show All CMS Page'),
                    'name' => 'cms_page',
                    'required' => false,
                    'class' => 'cms_page_class',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'cms_page_id_1',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'cms_page_id_0',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('CMS Page'),
                    'name' => 'cms_specify_temp',
                    'class' => 'cms_specify_class',
                    'id' => 'cms_specify_id',
                    'multiple' => true,
                    'options' => array(
                        'query' => $getAllCMSPage,
                        'id' => 'id_cms',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Display Hook'),
                    'name' => 'hook_name',
                    'options' => array(
                        'query' => $GetAlldisplayHooks,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select Your Hook Position where you want to show this!')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Save And Close'),
                'class' => 'btn btn-default pull-right',
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'name' => 'submitAdd' . $this->table . 'AndStay',
                    'type' => 'submit',
                    'title' => $this->l('Save And Stay'),
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                )
            )
        );

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }
        if (!($vccontentanywhere = $this->loadObject(true)))
            return;
        $this->fields_form['submit'] = array(
            'title' => $this->l('Save And Close'),
            'class' => 'btn btn-default pull-right'
        );
        if (!Tools::getValue('id_vccontentanywhere')) {
            $this->fields_value['content_type'] = 1;
            $this->fields_value['display_type'] = 1;
            $this->fields_value['prd_page'] = 1;
            $this->fields_value['cat_page'] = 1;
            $this->fields_value['cms_page'] = 1;
            $this->fields_value['prd_specify_temp'] = '';
            $this->fields_value['cat_specify_temp[]'] = '';
            $this->fields_value['cms_specify_temp[]'] = '';
            $this->fields_value['exception_temp[]'] = '';
        } else {
            $vccontentanywhere = new vccontentanywhere(Tools::getValue('id_vccontentanywhere'));
            $this->fields_value['prd_specify_temp'] = $vccontentanywhere->prd_specify;
            $this->fields_value['cat_specify_temp[]'] = $vccontentanywhere->cat_specify;
            $this->fields_value['cms_specify_temp[]'] = $vccontentanywhere->cms_specify;
            $this->fields_value['exception_temp[]'] = $vccontentanywhere->exception;
        }
        return parent::renderForm();
    }

    public function initToolbar()
    {
        parent::initToolbar();
    }

    public function processPosition()
    {
        if ($this->tabAccess['edit'] !== '1')
            $this->errors[] = Tools::displayError('You do not have permission to edit this.');
        else if (!Validate::isLoadedObject($object = new vccontentanywhere((int) Tools::getValue($this->identifier, Tools::getValue('id_vccontentanywhere', 1)))))
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.') . ' <b>' .
                $this->table . '</b> ' . Tools::displayError('(cannot load object)');
        if (!$object->updatePosition((int) Tools::getValue('way'), (int) Tools::getValue('position')))
            $this->errors[] = Tools::displayError('Failed to update the position.');
        else {
            $object->regenerateEntireNtree();
            Tools::redirectAdmin(self::$currentIndex . '&' . $this->table . 'Orderby=position&' . $this->table . 'Orderway=asc&conf=5' . (($id_vccontentanywhere = (int) Tools::getValue($this->identifier)) ? ('&' . $this->identifier . '=' . $id_vccontentanywhere) : '') . '&token=' . Tools::getAdminTokenLite('Adminvccontentanywhere'));
        }
    }

    public function ajaxProcessUpdatePositions()
    {
        $id_vccontentanywhere = (int) (Tools::getValue('id'));
        $way = (int) (Tools::getValue('way'));
        $positions = Tools::getValue($this->table);
        if (is_array($positions))
            foreach ($positions as $key => $value) {
                $pos = explode('_', $value);
                if ((isset($pos[1]) && isset($pos[2])) && ($pos[2] == $id_vccontentanywhere)) {
                    $position = $key + 1;
                    break;
                }
            }
        $vccontentanywhere = new vccontentanywhere($id_vccontentanywhere);
        if (Validate::isLoadedObject($vccontentanywhere)) {
            if (isset($position) && $vccontentanywhere->updatePosition($way, $position)) {
                Hook::exec('actionvccontentanywhereUpdate');
                die(true);
            } else
                die('{"hasError" : true, errors : "Can not update vccontentanywhere position"}');
        } else
            die('{"hasError" : true, "errors" : "This vccontentanywhere can not be loaded"}');
    }

    public function get_all_hooks_handle()
    {
        $fonts = array();
        $font = @unserialize(Configuration::get('vc_custom_hook'));

        if (!empty($font)) {
            foreach ($font as $key => $value) {

                $fonts[] = $value;
            }
        }

        return $fonts;
    }

    public function initProcess()
    {

        if (Tools::getIsset('duplicate' . $this->table)) {
            if ($this->tabAccess['add'] === '1')
                $this->action = 'duplicate';
            else
                $this->errors[] = Tools::displayError('You do not have permission to add this.');
        }
        if (!$this->action)
            parent::initProcess();
        else
            $this->id_object = (int) Tools::getValue($this->identifier);
    }

    public function processDuplicate()
    {
        if (Validate::isLoadedObject($vccontentanywhere = new vccontentanywhere((int) Tools::getValue('id_vccontentanywhere')))) {
            $id_vccontentanywhere = (int) Tools::getValue('id_vccontentanywhere');
            unset($vccontentanywhere->id);
            $vccontentanywhere->active = 0;
            $vccontentanywhere->position = vccontentanywhere::getHigherPosition() + 1;
            if ($vccontentanywhere->add())
                $this->redirect_after = self::$currentIndex . '&conf=19&token=' . $this->token;
            else
                $this->errors[] = Tools::displayError('An error occurred while creating an object.');
        }
    }

    public function processSave()
    {

        if (
            Tools::isSubmit('submitAddvccontentanywhereAndStay') ||
            Tools::isSubmit('submitAddvccontentanywhere')
        ) {
            $object = parent::processSave();
            if(!is_object($object)) return true;
            $exception_type = Tools::getValue('exception_type');
            if ($exception_type == 0)
                $object->exception = "";

            //prd_page
            $prd_page = Tools::getValue('prd_page');
            $object->prd_specify = "";
            if ($prd_page != 1)
                if (Tools::isSubmit('inputAccessories') && is_object($object)) {
                    $prd_specify = Tools::getValue('inputAccessories');
                    //$id_vccontentanywhere = Tools::getValue('id_vccontentanywhere');

                    $accessories_id = array_unique(explode('-', $prd_specify));
                    $this->changeAccessories($object, 1, $accessories_id);
                    // here we need to save this value in filter table
                }

            $cat_page = Tools::getValue('cat_page');

            if ($cat_page == 1)
                $object->cat_specify = "";
            else
                if (Tools::isSubmit('cat_specify_temp') && is_object($object)) {
                    $cat_specify = Tools::getValue('cat_specify');

                    $cat_accessories_id = explode(',', $cat_specify);
                    $this->changeAccessories($object, 2, $cat_accessories_id);
                    // here we need to save this value in filter table
                }


            $cms_page = Tools::getValue('cms_page');

            if ($cms_page == 1)
                $object->cms_specify = "";
            else
                if (Tools::isSubmit('cms_specify_temp') && is_object($object)) {
                    $prd_specify = Tools::getValue('cms_specify');
                    $accessories_id = array_unique(explode(',', $prd_specify));
                    $this->changeAccessories($object, 3, $accessories_id);
                    // here we need to save this value in filter table
                }

            $object->update();
            
            vc_manager()->vccClearCache();
            
            return $object;
        }

        return true;
    }

    /**
     * Link accessories with product
     *
     * @param array $accessories_id Accessories ids
     */
    public function changeAccessories($vccontentanywhere, $option_page, $accessories_id)
    {

        Db::getInstance()->delete($this->table.'_filter', '`'.bqSQL($this->identifier).'` = '.(int)$vccontentanywhere->id." AND `page` = {$option_page}");
//        $vccontentanywhere->deleteContentAnywherProductAccessories($option_page);


        if (count($accessories_id)) {
//            array_pop($accessories_id);

            foreach ($accessories_id as $id_specify_page)
                if((int)$id_specify_page > 0)
                Db::getInstance()->insert('vccontentanywhere_filter', array(
                    'id_vccontentanywhere' => (int) $vccontentanywhere->id,
                    'id_specify_page' => $id_specify_page,
                    'page' => $option_page
                ));
        }
    }

    public function processDelete()
    {
        $object = parent::processDelete();
        $id = $object->id_vccontentanywhere;
        Db::getInstance()->delete('vccontentanywhere_filter',"id_vccontentanywhere={$id}");
        return $object;
    }
    public function processBulkDelete()
    {
        $result = parent::processBulkDelete();
        if (is_array($this->boxes) && !empty($this->boxes)) {
            foreach ($this->boxes as $id) {
                Db::getInstance()->delete('vccontentanywhere_filter',"id_vccontentanywhere={$id}");
            }
        }
        return $result;
    }
}
