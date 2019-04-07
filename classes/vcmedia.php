<?php
class vcmedia extends ObjectModel
{
        public $id_vc_media;	
        public $file_name;
        public $subdir;
        public $legend;
	public static $definition = array(
		'table' => 'vc_media',
		'primary' => 'id_vc_media',
        'multilang'=>true,
		'fields' => array(
            'file_name' => array('type' => self::TYPE_STRING, 'validate' => 'isString') ,
            'subdir' => array('type' => self::TYPE_STRING,'validate' => 'isString') ,
            'legend' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true),
		),
	);
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
         
        parent::__construct($id, $id_lang, $id_shop);
    }
   
}