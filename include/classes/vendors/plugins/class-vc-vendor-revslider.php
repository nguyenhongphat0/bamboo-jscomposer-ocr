<?php
  /**
   * JWPLayer loader.
   *
   */
class Vc_Vendor_Revslider implements Vc_Vendor_Interface {
	protected static $instanceIndex = 1;
        public function load() {
            $this->buildShortcode();
	}
	public function buildShortcode() {
		if((bool)Module::isEnabled('revsliderprestashop') && (bool)Module::isInstalled('revsliderprestashop')){

            $vc_manager = vc_manager();

            $rs = Db::getInstance()->executeS("SELECT * FROM "._DB_PREFIX_.'revslider_sliders WHERE params LIKE \'%"template":"false"%\' OR params NOT LIKE \'%"template"\'', true, false);
			$revsliders = array();
			if ( $rs ) {
				foreach ( $rs as $slider ) {
                    $slider = (object)$slider;
					$revsliders[$slider->title] = $slider->alias;
				}
			} else {
				$revsliders[$vc_manager->l('No sliders found')] = 0;
			}
                        
                        
			vc_map( array(
				'base' => 'rev_slider_vc',
				'name' => $vc_manager->l('Revolution Slider'),
				'icon' => 'icon-wpb-revslider',
				'category' => $vc_manager->l('Content'),
				'description' => $vc_manager->l('Place Revolution slider'),
				"params" => array(
					array(
						'type' => 'textfield',
						'heading' => $vc_manager->l('Widget title'),
						'param_name' => 'title',
						'description' => $vc_manager->l('Enter text which will be used as widget title. Leave blank if no title is needed.')
					),
					array(
						'type' => 'dropdown',
						'heading' => $vc_manager->l('Revolution Slider'),
						'param_name' => 'alias',
						'admin_label' => true,
						'value' => $revsliders,
						'description' => $vc_manager->l('Select your Revolution Slider.')
					),
					array(
						'type' => 'textfield',
						'heading' => $vc_manager->l('Extra class name'),
						'param_name' => 'el_class',
						'description' => $vc_manager->l('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.')
					)
				)
			) );
		}
		if(vc_is_frontend_ajax() || vc_is_frontend_editor()) {

		}
	}
	public function setId($output) {
		return preg_replace('/rev_slider_(\d+)_(\d+)/', 'rev_slider_$1_$2'.time().'_'.self::$instanceIndex++, $output);
	}

}