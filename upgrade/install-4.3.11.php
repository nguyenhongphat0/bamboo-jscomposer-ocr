<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_4_3_11($object)
{
    
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'vccontentanywhere` CHANGE `prd_specify` `prd_specify` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL; ';
    Db::getInstance()->execute($sql);
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'vccontentanywhere` CHANGE `cat_specify` `cat_specify` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL; ';    
    Db::getInstance()->execute($sql);
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'vccontentanywhere` CHANGE `cms_specify` `cms_specify` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL; ';
    Db::getInstance()->execute($sql);
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'vccontentanywhere` DROP `blg_page`;';
    Db::getInstance()->execute($sql);
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'vccontentanywhere` DROP `blg_specify`;';
    Db::getInstance()->execute($sql);
    
    return true;
    
}