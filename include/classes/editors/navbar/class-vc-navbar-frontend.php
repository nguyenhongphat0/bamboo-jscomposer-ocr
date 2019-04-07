<?php
require_once vc_path_dir('EDITORS_DIR', 'navbar/class-vc-navbar.php');
/**
 *
 */
Class Vc_Navbar_Frontend extends Vc_Navbar {
	protected $controls = array(
		'add_element',
		'templates',
		'view_post',
		'save_update',
		'screen_size',
		'guides_switch',
		'custom_css'
	);
	protected $controls_filter_name = 'vc_nav_front_controls';
	protected $brand_url = 'http://vc.wpbakery.com/?utm_campaign=VCplugin_header&utm_source=vc_user&utm_medium=frontend_editor';
	public function getControlGuidesSwitch() {
                $vc_manager = vc_manager();
		return '<li class="vc_pull-right">'
		  .'<button id="vc_guides-toggle-button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn"'
		  .' title="'.$vc_manager->esc_attr( "Toggle editor's guides" ).'">'
		  .$vc_manager->l('Guides ON')
		  .'</button>'
		  .'</li>';
	}
	public function getControlScreenSize() {
		$disable_responsive = vc_settings()->get( 'not_responsive_css' );
                $vc_manager = vc_manager();
		if($disable_responsive !== '1') {
			$screen_sizes = array(
				array(
					'title' => $vc_manager->l('Desktop'),
					'size'  => '100%',
					'key'   => 'default',
					'active' => true
				),
				array(
					'title' => $vc_manager->l('Tablet landscape mode'),
					'size'  => '1024px',
					'key'   => 'landscape-tablets'
				),
				array(
					'title' => $vc_manager->l('Tablet portrait mode'),
					'size'  => '768px',
					'key'   => 'portrait-tablets'
				),
				array(
					'title' => $vc_manager->l('Smartphone landscape mode'),
					'size'  => '480px',
					'key'   => 'landscape-smartphones'
				),
				array(
					'title' => $vc_manager->l('Smartphone portrait mode'),
					'size'  => '320px',
					'key'   => 'portrait-smartphones'
				),
			);
			$output = '<li class="vc_pull-right">'
			  .'<div class="vc_dropdown" id="vc_screen-size-control">'
			  .'<a href="#" class="vc_dropdown-toggle"'
			  .' title="'.$vc_manager->l("Responsive preview").'"><i class="vc_icon default"'
			  .' id="vc_screen-size-current"></i><b class="vc_caret"></b></a>'
			  .'<ul class="vc_dropdown-list">';
			while($screen = current($screen_sizes)) {
				$output .= '<li><a href="#" title="'.$vc_manager->esc_attr($screen['title']).'"'
					.' class="vc_screen-width '.$screen['key']
					.(isset($screen['active']) && $screen['active'] ? ' active' : '')
					.'" data-size="'.$screen['size'].'"></a></li>';
				next($screen_sizes);
			}
			$output .= '</ul></div></li>';
			return $output;
		}
		return '';
	}
	public function getControlSaveUpdate() {
		$post = $this->post();
                $vc_manager = vc_manager();
                $params = array('id_cms'=>Tools::getValue('id_cms'),'updatecms'=>'');
		ob_start();
		?>
	<li class="vc_show-mobile vc_pull-right">
            <button data-url="<?php $vc_manager->esc_attr_e(JsComposer::backToAdminLink()) ?>"
				class="vc_btn vc_btn-default vc_btn-sm vc_navbar-btn vc_btn-backend-editor" id="vc_button-cancel"
				title="<?php echo $vc_manager->l("Cancel all changes and return to WP dashboard") ?>"><?php echo $vc_manager->l('Backend editor') ?></button>
		
		<button type="button" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn vc_btn-save" id="vc_button-update"
				title="<?php $vc_manager->esc_attr_e("Update") ?>"><?php echo $vc_manager->l('Update') ?></button>
		
	</li>
	<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	public function getControlViewPost() {
                $vc_manager = vc_manager();
		return '<li class="vc_pull-right">'
		  .'<a href="'.$vc_manager->esc_attr(JsComposer::getCMSLink((int)Tools::getValue('id_cms'),null,null,intval(vc_get_cms_lang_id()))).'" class="vc_icon-btn vc_back-button"'
		  .' title="'. $vc_manager->esc_attr( $vc_manager->l( "Exit Visual Composer edit mode")).'"></a>'
		  .'</li>';
	}
}