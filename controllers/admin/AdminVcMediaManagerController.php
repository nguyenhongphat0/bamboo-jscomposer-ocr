<?php
require_once (dirname(__FILE__) . '/../../jscomposer.php');
require_once (dirname(__FILE__) . '/../../classes/vcmedia.php');

class AdminVcMediaManagerController extends ModuleAdminController {

    protected $countries_array = array();
    protected $position_identifier = 'id_vc_media';
    private $original_filter = '';
    public $vc;

    /**
     *  @var object Category() instance for navigation
     */
  

    public function __construct() {
        $this->table = 'vc_media';
        $this->className = 'vcmedia';
        $this->lang = true;
        $this->deleted = false;
        $this->module = 'jscomposer';
        $this->explicitSelect = true;
        $this->bootstrap = true;
        $this->_defaultOrderWay = 'DESC';
        $this->context = Context::getContext();
        $this->_defaultOrderBy = 'id_vc_media';
        $this->image_dir = '../modules/jscomposer/uploads';
        $this->vc = jscomposer::getInstance();

        parent::__construct();
        $this->fields_list = array(
            'id_vc_media' => array(
                'title' => $this->l('Id'),
                'width' => 100,
                'type' => 'text',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
 
            'file_name' => array(
                'title' => $this->l('Image'),
                'align' => 'center',
                'type' => 'text',
                'callback'  => 'getStickerImage'
            ),
            
           /* 'file_name' => array(
                'title' => $this->l('File Name'),
                'width' => 440,
                'type' => 'text',
                'lang' => true,
                'orderby' => false,
                'filter' => false,
                'search' => false
            ), */
            'legend' => array(
                'title' => $this->l('Caption'),
                'width' => 440,
                'type' => 'text',
                'lang' => true,
                'orderby' => false,
                'filter' => false,
                'search' => false
            )
        );

        parent::__construct();
    }

    public function getStickerImage($echo, $row )
    {
        
      
        $db = Db::getInstance();

        $imgdir = vc_manager()->composer_settings['UPLOADS_DIR'];

       
        $tablename = _DB_PREFIX_.'vc_media' ;

        $image_folder = $db->getValue("SELECT subdir FROM {$tablename} WHERE file_name='{$row['file_name']}'");

 
        if (file_exists("{$imgdir}{$image_folder}{$row['file_name']}")) {

            if ($row['file_name'] != '')
                return '<img src="'.$this->image_dir.'/'. $image_folder .$row['file_name'].'" style="width:100px" />';
            }
    }

    public function init() {

           // context->shop is set in the init() function, so we move the _category instanciation after that
        if (($id_vc_media = Tools::getvalue('id_vc_media')) && $this->action != 'select_delete') {
            $this->_vcmedia = new vcmedia($id_vc_media);
        }

        parent::init();
       
    }

    public function setMedia($isNewTheme = false) {
        parent::setMedia();
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        $this->addJqueryPlugin('autocomplete');
    }

    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false) {
        if ($order_way == null)
            $order_way = 'ASC';
        return parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
    }

    public function renderList() {
        if (isset($this->_filter) && trim($this->_filter) == '')
            $this->_filter = $this->original_filter;
 

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
 
    public function renderForm() {

         /** @var Category $obj */
        $obj = $this->loadObject(true);

        $vc_is_edit = false;
 
        $vc_ajax_url = Context::getContext()->link->getAdminLink('VC_ajax') . '&hook_filter=1';


        if (!($obj = $this->loadObject(true))) {
            return;
        }


        $db = Db::getInstance();

        $imgdir = vc_manager()->composer_settings['UPLOADS_DIR'];

       
        $tablename = _DB_PREFIX_.'vc_media' ;

        $image_folder = $db->getValue("SELECT subdir FROM {$tablename} WHERE file_name='{$obj->file_name}'");

        $image = $this->vc->composer_settings['UPLOADS_DIR'] .'/'. $image_folder .$obj->file_name;
      
        $image_url = ImageManager::thumbnail($image, $this->table.'_'.(int)$obj->id.'.'.$this->imageType, 150,
        $this->imageType, true, true);
        $image_size = file_exists($image) ? filesize($image) / 1000 : false;

        

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Image Caption Manager'),
            ),
            'input' => array(

                /* array(
                    'type' => 'file',
                    'label' => $this->l('Image'),
                    'name' => 'image',
                    'display_image' => true,
                    'image' => $image_url ? $image_url : false,
                    'size' => $image_size,
                    
                    
                ),
            */
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'legend',
                    'lang' => true,
                    'required' => true,
                    'desc' => $this->l('Enter Your Image Caption')
                ),
               ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ),

        );
         
        if (!($vc_media = $this->loadObject(true)))
            return;


        return parent::renderForm();
    }

    public function initToolbar() {
        parent::initToolbar();
    }
 

    
}

