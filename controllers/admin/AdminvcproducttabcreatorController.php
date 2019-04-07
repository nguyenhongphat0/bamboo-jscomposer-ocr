<?php

require_once (dirname(__FILE__) . '/../../classes/vcproducttabcreator.php');

class AdminvcproducttabcreatorController extends ModuleAdminController {

    protected $countries_array = array();
    protected $position_identifier = 'id_vcproducttabcreator';
    public $asso_type = 'shop';
    private $original_filter = '';

    public function __construct() {
        $this->table = 'vcproducttabcreator';
        $this->className = 'vcproducttabcreator';
        $this->lang = true;
        $this->deleted = false;
        $this->module = 'jscomposer';
        $this->explicitSelect = true;
        $this->_defaultOrderBy = 'position';
        $this->allow_export = true;
        $this->bootstrap = true;
        $this->_defaultOrderWay = 'DESC';
        $this->context = Context::getContext();
        if (Shop::isFeatureActive())
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        parent::__construct();
        $this->fields_list = array(
            'id_vcproducttabcreator' => array(
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
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 270,
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'filter_key' => 'a!position',
                'position' => 'position',
                'align' => 'center'
            )
        );

        parent::__construct();
    }

    public function init() {
        parent::init();
        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'vcproducttabcreator_shop sbs ON a.id_vcproducttabcreator=sbs.id_vcproducttabcreator && sbs.id_shop IN(' . implode(',', Shop::getContextListShopID()) . ')';
        $this->_select = 'sbs.id_shop';
        $this->_defaultOrderBy = 'a.position';
        $this->_defaultOrderWay = 'DESC';
        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $this->_group = 'GROUP BY a.id_vcproducttabcreator';
        }
        $this->_select = 'a.position position';
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
        $vc_is_edit = false;
        $vccanywhere = '';
        $prd_specify_values = '';
        $prd_page_values = '';
        $products_list_array = array();
        if (Tools::getvalue('id_vcproducttabcreator')) {
            $vc_is_edit = true;
            $vcproducttabcreator = new vcproducttabcreator(Tools::getvalue('id_vcproducttabcreator'));
            $vccanywhere = $vcproducttabcreator;
            $prd_specify_values = $vcproducttabcreator->prd_specify;
            $prd_page_values = $vcproducttabcreator->prd_page;            
            $vccontentanywhere = new vccontentanywhere();
            $products_list_array = $vccontentanywhere->getProductsById($prd_specify_values);            
        }
//        $vccaw = new vcproducttabcreator();
        
//      $prd = $vccontentanywhere->getAllProductsByCats();
//      $GetAllmodules_list = $vccontentanywhere->GetAllFilterModules();
//      if(Tools::getvalue('id_vcproducttabcreator')){
//        $module_hook_list = $vccontentanywhere->getModuleHookbyedit($vccanywhere->modules_list);
//      }else{
//        $module_hook_list = $vccontentanywhere->GetAllHooks();
//      }
//        $GetAllHook = $vccontentanywhere->GetAllHooks();
        $vc_ajax_url = Context::getContext()->link->getAdminLink('VC_ajax') . '&hook_filter=1';
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('VC Product Tab Creator'),
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
                array(
                    'type' => 'vc_content_mod_type',
                    'name' => 'vc_content_mod_name',
                    'vc_is_edit' => $vc_is_edit,
                    'vc_ajax_url' => $vc_ajax_url,
                ),
//                array(
//                       'type' => 'switch',
//                       'label' => $this->l('Simple Content Type'),
//                       'name' => 'content_type',
//                       'required' => false,
//                       'class' => 'content_type_class',
//                       'is_bool' => true,
//                       'values' => array(
//                              array(
//                                'id' => 'content_type_id_1',
//                                'value' => 1,
//                                'label' => $this->l('Enabled')
//                              ),
//                              array(
//                                'id' => 'content_type_id_0',
//                                'value' => 0,
//                                'label' => $this->l('Disabled')
//                              )
//                       )
//                  ),
//                array(
//                    'type' => 'select',
//                    'name' => 'modules_list',
//                    'label' => $this->l('Select Module'),
//                    'options' => array(
//                            'query' => $GetAllmodules_list,
//                            'id' => 'id',
//                            'name' => 'name'
//                          )
//                ),
//                array(
//                    'type' => 'select',
//                    'name' => 'module_hook_list',
//                    'label' => $this->l('Available Module Hook'),
//                    'options' => array(
//                            'query' => $module_hook_list,
//                            'id' => 'id',
//                            'name' => 'name'
//                          )
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
                    'desc' => $this->l('Enter Your Description')
                ),
                array(
                    'type' => 'vc_content_type',
                    'name' => 'title',
                    'vc_is_edit' => $vc_is_edit,
                    'prd_specify_values' => $prd_specify_values,
                    'prd_page_values' => $prd_page_values,
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
                array(
                    'type' => 'ajaxproducts',
                    'label' => $this->l('Select Products'),
                    'name' => 'prd_specify_temp',
                    'class' => 'prd_specify_class',
                    'id' => 'prd_specify_id',
                    'multiple' => true,
                    'saved' => $products_list_array,
                ),
//                  array(
//                  'type' => 'select',
//                  'label' => $this->l('Select Product'),
//                  'name' => 'prd_specify_temp',
//                  'class' => 'prd_specify_class',
//                  'id' => 'prd_specify_id',
//                  'multiple' => true,
//                  'options' => array(
//                          'query' => $prd,
//                          'id' => 'id_product',
//                          'name' => 'name'
//                        )
//                ),
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
                'class' => 'btn btn-default pull-right'
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
        if (!($vcproducttabcreator = $this->loadObject(true)))
            return;
        $this->fields_form['submit'] = array(
            'title' => $this->l('Save And Close'),
            'class' => 'btn btn-default pull-right'
        );
        if (!Tools::getvalue('id_vcproducttabcreator')) {
            $this->fields_value['content_type'] = 1;
            $this->fields_value['prd_page'] = 1;
        } else {
            $vcproducttabcreator = new vcproducttabcreator(Tools::getvalue('id_vcproducttabcreator'));
            $this->fields_value['prd_specify_temp'] = $vcproducttabcreator->prd_specify;
            $this->fields_value['content_type'] = $vcproducttabcreator->content_type;
            $this->fields_value['prd_page'] = $vcproducttabcreator->prd_page;
        }
        return parent::renderForm();
    }

    public function initToolbar() {
        parent::initToolbar();
    }

    public function processPosition() {
        if ($this->tabAccess['edit'] !== '1')
            $this->errors[] = Tools::displayError('You do not have permission to edit this.');
        else if (!Validate::isLoadedObject($object = new vcproducttabcreator((int) Tools::getValue($this->identifier, Tools::getValue('id_vcproducttabcreator', 1)))))
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.') . ' <b>' .
                    $this->table . '</b> ' . Tools::displayError('(cannot load object)');
        if (!$object->updatePosition((int) Tools::getValue('way'), (int) Tools::getValue('position')))
            $this->errors[] = Tools::displayError('Failed to update the position.');
        else {
            $object->regenerateEntireNtree();
            Tools::redirectAdmin(self::$currentIndex . '&' . $this->table . 'Orderby=position&' . $this->table . 'Orderway=asc&conf=5' . (($id_vcproducttabcreator = (int) Tools::getValue($this->identifier)) ? ('&' . $this->identifier . '=' . $id_vcproducttabcreator) : '') . '&token=' . Tools::getAdminTokenLite('Adminvcproducttabcreator'));
        }
    }

    public function ajaxProcessUpdatePositions() {
        $id_vcproducttabcreator = (int) (Tools::getValue('id'));
        $way = (int) (Tools::getValue('way'));
        $positions = Tools::getValue($this->table);
        if (is_array($positions))
            foreach ($positions as $key => $value) {
                $pos = explode('_', $value);
                if ((isset($pos[1]) && isset($pos[2])) && ($pos[2] == $id_vcproducttabcreator)) {
                    $position = $key + 1;
                    break;
                }
            }

        $vcproducttabcreator = new vcproducttabcreator($id_vcproducttabcreator);
        if (Validate::isLoadedObject($vcproducttabcreator)) {
            if (isset($position) && $vcproducttabcreator->updatePosition($way, $position)) {
                Hook::exec('actionvcproducttabcreatorUpdate');
                die(true);
            }
            else
                die('{"hasError" : true, errors : "Can not update vcproducttabcreator position"}');
        }
        else
            die('{"hasError" : true, "errors" : "This vcproducttabcreator can not be loaded"}');
    }
    public function processSave()
    {
        
        if (
                Tools::isSubmit('submitAddvcproducttabcreatorAndStay') ||
                Tools::isSubmit('submitAddvcproducttabcreator')                 
        )
        {
            $object = parent::processSave();
            
            if(Tools::isSubmit('inputAccessories')){
                $object->prd_specify = Tools::getValue('inputAccessories');
                $object->update();
            }
            vc_manager()->vcProTabClearCache();
            
            return $object;
        }

        return true;
    }
}

