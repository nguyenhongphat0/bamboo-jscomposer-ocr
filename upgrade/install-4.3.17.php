<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_4_3_17($object)
{


  $sql = 'ALTER TABLE `'._DB_PREFIX_.'vc_media` CHANGE `ID` `id_vc_media` INT(10) NOT NULL AUTO_INCREMENT; ';
 
  Db::getInstance()->execute($sql);


    $sql_lang=  'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'vc_media_lang` (
       `id_vc_media` int(11) NOT NULL,
       `id_lang` int(11) NOT NULL,
       `legend` varchar(255) NOT NULL,
       PRIMARY KEY (`id_vc_media`,`id_lang`)
     ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

   Db::getInstance()->execute($sql_lang);



    $id_tab = (int) Tab::getIdFromClassName('AdminVcMediaManager');
    $id_parent = (int) Tab::getIdFromClassName('Adminjscomposer');

    if (!$id_tab) {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminVcMediaManager';
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