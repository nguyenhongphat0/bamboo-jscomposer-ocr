<?php
/**
 * WPBakery Visual Composer main class.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
/**
 * Edit form for shortcodes with ability to manage shortcode attributes in more convenient way.
 *
 * @since   4.2
 */
class Vc_Shortcode_Edit_Form {
        public static $actions = array();
        public function __construct(){
            
        }
	public function init() {
//		add_action( 'wp_ajax_wpb_show_edit_form', array( &$this, 'build' ) );

        JsComposer::$sds_action_hooks['wpb_show_edit_form'] =  array( &$this, 'build' );
                
//		add_filter( 'vc_single_param_edit', array( &$this, 'changeEditFormFieldParams' ) );
//		add_filter( 'vc_edit_form_class', array( &$this, 'changeEditFormParams' ) );

	}
	public function render() {
                $editor = vc_manager();
		$editor->vc_include_template('editors/popups/panel_shortcode_edit_form.tpl.php', array(
			'box' => $this,
                        'editor' => $editor
		));
	}
	public function build() {
        $vc_main = vc_manager();
		$element = $vc_main->vc_post_param( 'element' );

		$shortCode = stripslashes($vc_main->vc_post_param( 'shortcode' ));

        $params = $vc_main->vc_post_param( 'params' );
        
//        if(!empty($params)){
//            $shortCode = "[{$element} ";
//            $c = 0;
//            foreach($params as $k=>$v){
//                if($c > 0)
//                    $shortCode .= ' ';
//                $shortCode .= "{$k}=\"".  str_replace('"','&quot;',$v).'"';
//                $c++;
//new changes
//                if(is_string($v) && preg_match('/[\[\]]/',$v)){
//                    $params[$k] = str_replace(array('[',']'), array('&#91;','&#93;'), $v);
//                }
//            }
//            $shortCode .= "][/{$element}]";
//        }

		visual_composer()->removeShortCode( $element );
		$settings = WPBMap::getShortCode( $element );
		$WPS = new WPBakeryShortCode_Settings( $settings );
        echo $WPS->contentAdmin($params);
//		echo JsComposer::do_shortcode( $shortCode );
		die();
	}

	public static function changeEditFormFieldParams( $param ) {
		$css = $param['vc_single_param_edit_holder_class'];
		if ( isset( $param['edit_field_class'] ) ) {
			$new_css = $param['edit_field_class'];
		} else {
			switch ( $param['type'] ) {
				case 'attach_image':
				case 'attach_images':
				case 'textarea_html':
					$new_css = 'vc_col-sm-12 vc_column';
					break;
				default:
					$new_css = 'vc_col-sm-12 vc_column';
			}
		}
		array_unshift( $css, $new_css );
		$param['vc_single_param_edit_holder_class'] = $css;
		return $param;
	}

	public function changeEditFormParams( $css_classes ) {
		$css = 'vc_row';
		array_unshift( $css_classes, $css );
		return $css_classes;
	}
}