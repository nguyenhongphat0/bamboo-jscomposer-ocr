<?php
$sql = array();

$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.JsComposer::$VC_MEDIA.'`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vc_image_type`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vccontentanywhere`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vccontentanywhere_lang`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vccontentanywhere_shop`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vcproducttabcreator`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vcproducttabcreator_lang`';
$sql[] = 'DROP TABLE IF EXISTS  `'._DB_PREFIX_.'vcproducttabcreator_shop`';

