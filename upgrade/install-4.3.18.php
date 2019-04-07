<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_4_3_18($object)
{
    $db = Db::getInstance();
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'vc_media` ADD `subdir` TEXT NULL AFTER `file_name`;';
    $db->execute($sql);

    if (Module::isInstalled('smartshortcode') && Module::isEnabled('smartshortcode')) {
	    $object->vcTinymcePluginAdd ('shortcode');    
	}
    return true;
}