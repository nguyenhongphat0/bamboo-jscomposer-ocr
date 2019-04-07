<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . JsComposer::$VC_MEDIA.'`(
      id_vc_media INT(10) NOT NULL AUTO_INCREMENT,
      file_name VARCHAR(100) NOT NULL,
      subdir TEXT,
      PRIMARY KEY (id_vc_media)
  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[]=  'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . JsComposer::$VC_MEDIA.'_lang` (
  `id_vc_media` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `legend` varchar(255) NOT NULL,
  PRIMARY KEY (`id_vc_media`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
 
       

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vccontentanywhere`(
  `id_vccontentanywhere` int(11) NOT NULL auto_increment,
  `hook_name` varchar(200) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `display_type` varchar(150) DEFAULT NULL,
  `content_type` varchar(150) DEFAULT NULL,
  `modules_list` varchar(150) DEFAULT NULL,
  `module_hook_list` varchar(150) DEFAULT NULL,
  `prd_page` varchar(150) DEFAULT NULL,
  `prd_specify` MEDIUMTEXT DEFAULT NULL,
  `cat_page` varchar(150) DEFAULT NULL,
  `cat_specify` MEDIUMTEXT DEFAULT NULL,
  `cms_page` varchar(150) DEFAULT NULL,
  `cms_specify` MEDIUMTEXT DEFAULT NULL,
  `blg_page` varchar(150) DEFAULT NULL,
  `blg_specify` varchar(150) DEFAULT NULL,
  `exception_type` varchar(150) DEFAULT NULL,
  `exception` longtext,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_vccontentanywhere`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vccontentanywhere_lang` (
  `id_vccontentanywhere` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id_vccontentanywhere`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vccontentanywhere_shop` (
  `id_vccontentanywhere_shop`  int(11) NOT NULL auto_increment,
  `id_vccontentanywhere`  int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  KEY(`id_vccontentanywhere_shop`),
  PRIMARY KEY (`id_vccontentanywhere`,`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vccontentanywhere_filter` (
    `id_vccontentanywhere` int(10) unsigned NOT NULL,
    `id_specify_page` int(10) unsigned NOT NULL,
    `page` int(2) unsigned NOT NULL,
     PRIMARY KEY (`id_vccontentanywhere`, `id_specify_page`, `page`),
          KEY `id_specify_page` (`id_specify_page`),
          KEY `page` (`page`)
    )  ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vcproducttabcreator`(
  `id_vcproducttabcreator` int(11) NOT NULL auto_increment,
  `active` int(11) DEFAULT NULL,
  `content_type` varchar(150) DEFAULT NULL,
  `modules_list` varchar(150) DEFAULT NULL,
  `module_hook_list` varchar(150) DEFAULT NULL,
  `prd_page` varchar(150) DEFAULT NULL,
  `prd_specify` varchar(150) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_vcproducttabcreator`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vcproducttabcreator_lang` (
  `id_vcproducttabcreator` int(11) NOT NULL,
  `id_lang` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id_vcproducttabcreator`,`id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vcproducttabcreator_shop` (
  `id_vcproducttabcreator_shop`  int(11) NOT NULL auto_increment,
  `id_vcproducttabcreator`  int(11) NOT NULL,
  `id_shop` int(11) NOT NULL,
  KEY(`id_vcproducttabcreator_shop`),
  PRIMARY KEY (`id_vcproducttabcreator`,`id_shop`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = "CREATE TABLE IF NOT EXISTS `". _DB_PREFIX_ ."vc_image_type` (
  `id_vc_image_type` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',  
  PRIMARY KEY (`id_vc_image_type`),
  KEY `image_type_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";

$sql[] = 'INSERT INTO '. _DB_PREFIX_ .'vc_image_type(`name`,`width`,`height`,`active`) VALUES("vc_media_thumbnail",150,150,1);';