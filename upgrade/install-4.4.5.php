<?php
if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_4_4_5($object){


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

    $id_tab = (int) Tab::getIdFromClassName('AdminJsComposerAjax');
    $id_parent = -1;

    if (!$id_tab) {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminJsComposerAjax';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Media Manager';
        }
        $tab->id_parent = $id_parent;
        $tab->module = $object->name;

        $tab->add();
    }

    return true;
}
