<?php
class vcproducttabcreator extends ObjectModel
{
        public $id_vcproducttabcreator;	
        public $active = 1;
        public $prd_page;
        public $prd_specify;
        public $content_type;
        public $modules_list;
        public $module_hook_list;
        public $position;
        //lang field
	    public $title;
        public $content;

	public static $definition = array(
		'table' => 'vcproducttabcreator',
		'primary' => 'id_vcproducttabcreator',
        'multilang'=>true,
		'fields' => array(
            'content_type' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'modules_list' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'module_hook_list' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'prd_page' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'prd_specify' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'position' => array('type' => self::TYPE_INT),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool','required' => true),
            'title' => array('type' => self::TYPE_STRING, 'lang'=>true, 'validate' => 'isString','required' => true),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString')
		),
	);
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('vcproducttabcreator', array('type' => 'shop'));
                parent::__construct($id, $id_lang, $id_shop);
    }
    public function add($autodate = true, $null_values = false)
    {
        if ($this->position <= 0)
            $this->position = vcproducttabcreator::getHigherPosition() + 1;
        if (!parent::add($autodate, $null_values) || !Validate::isLoadedObject($this))
            return false;
        return true;
    }
    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`)
                FROM `'._DB_PREFIX_.'vcproducttabcreator`';
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }
    public static function GetInstance()
    {
        $ins = new vcproducttabcreator();
        return $ins;
    }
    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
            SELECT `id_vcproducttabcreator`, `position`
            FROM `'._DB_PREFIX_.'vcproducttabcreator`
            ORDER BY `position` ASC'
        ))
            return false;
        foreach ($res as $vcproducttabcreator)
            if ((int)$vcproducttabcreator['id_vcproducttabcreator'] == (int)$this->id)
                $moved_vcproducttabcreator = $vcproducttabcreator;
        if (!isset($moved_vcproducttabcreator) || !isset($position))
            return false;
        $query_1 = ' UPDATE `'._DB_PREFIX_.'vcproducttabcreator`
        SET `position`= `position` '.($way ? '- 1' : '+ 1').'
        WHERE `position`
        '.($way
        ? '> '.(int)$moved_vcproducttabcreator['position'].' AND `position` <= '.(int)$position
        : '< '.(int)$moved_vcproducttabcreator['position'].' AND `position` >= '.(int)$position.'
        ');
        $query_2 = ' UPDATE `'._DB_PREFIX_.'vcproducttabcreator`
        SET `position` = '.(int)$position.'
        WHERE `id_vcproducttabcreator` = '.(int)$moved_vcproducttabcreator['id_vcproducttabcreator'];
        return (Db::getInstance()->execute($query_1)
        && Db::getInstance()->execute($query_2));
    }
    public function GetTabContentByPRDID($id_product = 1)
    {
        $reslt = array();
        $id_lang = (int)Context::getContext()->language->id;
        $id_shop = (int)Context::getContext()->shop->id;
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'vcproducttabcreator` v 
                INNER JOIN `'._DB_PREFIX_.'vcproducttabcreator_lang` vl ON (v.`id_vcproducttabcreator` = vl.`id_vcproducttabcreator` AND vl.`id_lang` = '.$id_lang.')
                INNER JOIN `'._DB_PREFIX_.'vcproducttabcreator_shop` vs ON (v.`id_vcproducttabcreator` = vs.`id_vcproducttabcreator` AND vs.`id_shop` = '.$id_shop.')
                WHERE ';
        $sql .= ' v.`active` = 1 ORDER BY v.`position` ASC';
        $cache_id = md5($sql);
        if(!Cache::isStored($cache_id))
        {
            $results = Db::getInstance()->executeS($sql);
            if(isset($results) && !empty($results)){
                
                foreach($results as $i=>$result){
                    if(isset($result['prd_page']) && $result['prd_page'] == 1){
                        $reslt[$i] = $result;
                    }else{
//                        $vccontentany = new vccontentanywhere();
//                        $id_prd_cats = $vccontentany->getProductCategories($id_product);
                        $prd_specify_arr = explode('-',$result['prd_specify']);
                        if(isset($prd_specify_arr) && !empty($prd_specify_arr)){
                            unset($prd_specify_arr[count($prd_specify_arr) - 1]);
                            if(in_array($id_product, $prd_specify_arr)){
//                                $result['content'] = JsComposer::do_shortcode($result['content']);
                                $reslt[$i] = $result;
                            }
                        }
                    }
                }
            }
            $outputs = $this->ContentFilterEngine($reslt);
            Cache::store($cache_id, $outputs);
        }
        return Cache::retrieve($cache_id);
    }
    public function ContentFilterEngine($results = array())
    {
        $outputs = array();
        if(isset($results) && !empty($results)){
            $i = 0;
            foreach($results as $vcvalues){
                foreach($vcvalues as $vckey => $vcval){
                    if($vckey == 'content'){
                        $outputs[$i]['content'] = JsComposer::vc_content_filter($vcval);
                    }
                    if($vckey == 'title'){
                        $outputs[$i]['title'] = $vcval;
                    }
                    if($vckey == 'id_vcproducttabcreator'){
                        $outputs[$i]['id_vcproducttabcreator'] = $vcval;
                    }
                    if($vckey == 'content_type'){
                        $outputs[$i]['content_type'] = $vcval;
                    }
                    if($vckey == 'modules_list'){
                        $outputs[$i]['modules_list'] = $vcval;
                    }
                    if($vckey == 'module_hook_list'){
                        $outputs[$i]['module_hook_list'] = $vcval;
                    }
                }
                $i++;
            }
        }
        return $outputs;
    }
    
    
}