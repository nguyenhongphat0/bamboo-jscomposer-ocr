<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_4_3_5($object)
{
    $db = Db::getInstance();
    
    $db->execute('ALTER TABLE `'._DB_PREFIX_.'vccontentanywhere_lang` CHANGE `content` `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL');
    
	return true;
}