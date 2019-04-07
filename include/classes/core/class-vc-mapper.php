<?php
/**
 * WPBakery Visual Composer Main manager.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
/**
 * Vc mapper new class. On maintenance
 * Allows to bind hooks for shortcodes.
 */
class Vc_Mapper {
	/**
	 * Stores mapping activities list which where called before initialization
	 * @var array
	 */
	protected $init_activity = array();

	function __construct() {
	}

	/**
	 * Include params list objects and calls all stored activity methods.
	 *
	 * @since  4.2
	 * @access public
	 */
	public function init() {
            
		$vc_main = vc_manager();
		require_once $vc_main->vc_path_dir( 'PARAMS_DIR', 'load.php' );
//		require_once $vc_main->vc_path_dir( 'CORE_DIR', 'class-wpb-map.php' );
		WPBMap::setInit();
		require_once $vc_main->vc_path_dir( 'CONFIG_DIR', 'map.php' );
                
		$this->callActivities();
		
	}

	/**
	 * This method is called by VC objects methods if it is called before VC initialization.
	 *
	 * @see WPBMAP
	 * @since  4.2
	 * @access public
	 * @param $object - mame of class object
	 * @param $method - method name
	 * @param array $params - list of attributes for object method
	 */
	public function addActivity( $object, $method, $params = array() ) {
		$this->init_activity[] = array( $object, $method, $params );
	}

	/**
	 * Call all stored activities.
	 *
	 * Called by init method. List of activities stored by $init_activity are created by other objects called after
	 * initialization.
	 *
	 * @since  4.2
	 * @access public
	 */
	protected function callActivities() {
                                        
//		while ( $activity = each( $this->init_activity ) ) {
//			list( $object, $method, $params ) = $activity[1];
//			if ( $object == 'mapper' ) {
//                            
//				switch ( $method ) {
//					case 'map':
//						WPBMap::map( $params['tag'], $params['attributes'] );
//						break;
//					case 'drop_param':
//						WPBMap::dropParam( $params['name'], $params['attribute_name'] );
//						break;
//					case 'add_param':
//						WPBMap::addParam( $params['name'], $params['attribute'] );
//						break;
//					case 'mutate_param':
//						WPBMap::mutateParam( $params['name'], $params['attribute'] );
//						break;
//					case 'drop_shortcode':
//						WPBMap::dropShortcode( $params['name'] );
//						break;
//					case 'modify':
//						WPBMap::modify( $params['name'], $params['setting_name'], $params['value'] );
//						break;
//				}
//			}
//		}
                
                foreach($this->init_activity as $key => $activity){
                    list( $object, $method, $params ) = $activity;
			if ( $object == 'mapper' ) {
                            
				switch ( $method ) {
					case 'map':
						WPBMap::map( $params['tag'], $params['attributes'] );
						break;
					case 'drop_param':
						WPBMap::dropParam( $params['name'], $params['attribute_name'] );
						break;
					case 'add_param':
						WPBMap::addParam( $params['name'], $params['attribute'] );
						break;
					case 'mutate_param':
						WPBMap::mutateParam( $params['name'], $params['attribute'] );
						break;
					case 'drop_shortcode':
						WPBMap::dropShortcode( $params['name'] );
						break;
					case 'modify':
						WPBMap::modify( $params['name'], $params['setting_name'], $params['value'] );
						break;
				}
			}
                }
	}
}