<?php
if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_4_4_8($object){

    $controllers = Configuration::get('VC_ENQUEUED_CONTROLLERS');
    $controllers = Tools::jsonDecode($controllers, true);

    $controller_exists = false;
    foreach ($controllers as $key => $controller) {
        if('vccontentanywhere' == $key){
            if($controller['controller'] == 'Adminvccontentanywhere'){
                unset($controllers[$key]);
            }
            if($controller['controller'] == 'AdminProducts'){
                unset($controllers[$key]);
            }
        }
    }

    Configuration::updateValue('VC_ENQUEUED_CONTROLLERS', Tools::jsonEncode($controllers));
    

    $controllers = Configuration::get('VC_ENQUEUED_CONTROLLERS');
    $controllers = Tools::jsonDecode($controllers, true);

    $new_controller['vccontentanywhere'] = array(
        'type' => 'core',
        'shortname' => 'vccaw',
        'controller' => 'Adminvccontentanywhere',
        'dbtable' => 'vccontentanywhere',
        'identifier' => 'id_vccontentanywhere',
        'field' => 'content',
        'module_status' => 1,
        'module_frontend_status' => 0,
        'module_backend_status' => 1,
        'module_frontend_enable' => 0
    );

    $controllers = array_merge($controllers,$new_controller);
    Configuration::updateValue('VC_ENQUEUED_CONTROLLERS', Tools::jsonEncode($controllers));


    $controllers = Configuration::get('VC_ENQUEUED_CONTROLLERS');
    $controllers = Tools::jsonDecode($controllers, true);

    $new_controller['products'] = array(
        'type' => 'core',
        'shortname' => 'product',
        'controller' => 'AdminProducts',
        'context_controller' => 'product',
        'dbtable' => 'product',
        'identifier' => 'id_product',
        'field' => 'description',
        'module_status' => 1,
        'module_frontend_status' => 1,
        'module_backend_status' => 1,
        'module_frontend_enable' => 0
    );

    $controllers = array_merge($controllers,$new_controller);
    Configuration::updateValue('VC_ENQUEUED_CONTROLLERS', Tools::jsonEncode($controllers));


    return $object->registerHook('displayAdminProductsExtra');

    return true;
}
