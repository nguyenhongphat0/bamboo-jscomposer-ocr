<?php
/**
 */
define( 'SLIDE_TITLE', vc_manager()->l( "Slide") );
require_once vc_path_dir('SHORTCODES_DIR', 'vc-tabs.php');
class WPBakeryShortCode_VC_Tour extends WPBakeryShortCode_VC_Tabs {
	protected $predefined_atts = array(
		'tab_id' => SLIDE_TITLE,
		'title' => ''
	);

	protected function getFileName() {
		return 'vc_tabs';
	}

	public function getTabTemplate() {
		return '<div class="wpb_template">' . JsComposer::do_shortcode( '[vc_tab title="' . SLIDE_TITLE . '" tab_id=""][/vc_tab]' ) . '</div>';
	}

}