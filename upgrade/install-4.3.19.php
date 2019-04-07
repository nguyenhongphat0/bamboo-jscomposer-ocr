<?php
if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_4_3_19($object)
{

    $sql_filter = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vccontentanywhere_filter` (
	  `id_vccontentanywhere` int(10) unsigned NOT NULL,
	  `id_specify_page` int(10) unsigned NOT NULL,
      `page` int(2) unsigned NOT NULL,
	   PRIMARY KEY (`id_vccontentanywhere`, `id_specify_page`, `page`),
            KEY `id_specify_page` (`id_specify_page`),
            KEY `page` (`page`)
	)  ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';



    Db::getInstance()->execute($sql_filter);


    //-------------------------------------------------------------------------------------
    $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT  id_vccontentanywhere,prd_page, prd_specify,cat_page,cat_specify,cms_page,cms_specify
			 FROM ' . _DB_PREFIX_ . 'vccontentanywhere  WHERE  prd_page = 0 OR cat_page = 0 OR cms_page=0');

    $items = array(
        1 => array('prd_page', 'prd_specify'),
        2 => array('cat_page', 'cat_specify'),
        3 => array('cms_page', 'cms_specify'),
    );
    foreach ($results as $result) {

        $id_specify_page = array();

        foreach($items as $key=>$item){
            if($result[$item[0]] == 0){
                $page = $key;
                $delimiter = ',';
                if($page == 1){
                    $delimiter = '-';
                }
                $id_specify_page = explode($delimiter, $result[$item[1]]);
                if(!empty($id_specify_page) && is_array($id_specify_page)){
                    foreach($id_specify_page as $value){
                        if($value != 0){
                            Db::getInstance()->insert('vccontentanywhere_filter', array(
                                'id_vccontentanywhere' => (int) $result['id_vccontentanywhere'],
                                'id_specify_page' => (int) $value,
                                'page' => $page
                            ));
                        }
                    }
                }
            }
        }
    }

    return true;
}
