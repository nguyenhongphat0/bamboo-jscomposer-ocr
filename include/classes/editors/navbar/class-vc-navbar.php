<?php

/**
 * Renders navigation bar for Editors.
 */
Class Vc_Navbar {
	protected $controls = array(
		'add_element',
		'templates',
		'save_backend',
		'preview',
		'frontend',
		'custom_css'
	);
	protected $brand_url = 'http://vc.wpbakery.com/?utm_campaign=VCplugin_header&utm_source=vc_user&utm_medium=backend_editor';
	protected $css_class = 'vc_navbar';
	protected $controls_filter_name = 'vc_nav_controls';
	protected $post = false;

	public function __construct($post = '') {
//            if(Tools::getValue('controller') == 'AdminCmsContent' && Tools::getValue('id_cms')){
            if(JsComposer::condition()){
                $this->post = $post;
            }
		
	}

	/**
	 * Generate array of controls by iterating property $controls list.
	 *
	 * @return array - list of arrays witch contains key name and html output for button.
	 */
	public function getControls() {
		$list = array();
                $jscomposer = JsComposer::getInstance();
		foreach($this->controls as $control) {
			$method = $jscomposer->vc_camel_case('get_control_'.$control);
			if( method_exists($this, $method) ) {
				$list[] = array($control, $this->$method()."\n");
			}
		}
		return $list;
	}

	/**
	 * Get current post.
	 * @return null|WP_Post
	 */
	public function post() {
                $id = Tools::getValue('id_cms');
		if($this->post) return $this->post;
//		return get_post();
		return new CMS($id);
	}
	/**
	 * Render template.
	 */
	public function render() {
                $jscomposer = vc_manager();
		$jscomposer->vc_include_template('editors/navbar/navbar.tpl.php', array(
			'css_class' => $this->css_class,
			'controls' => $this->getControls(),
			'nav_bar' => $jscomposer,
			'post' => ''
		));                
	}
	public function getLogo() {
                $jscomposer = JsComposer::getInstance();
		$output = '<a id="vc_logo" class="vc_navbar-brand" title="'.$jscomposer->esc_attr('Visual Composer', 'js_composer')
		  .'" href="'.$jscomposer->esc_attr($this->brand_url).'" target="_blank">'
		  .$jscomposer->l('Visual Composer').'</a>';
		return $output;
	}
	public function getControlCustomCss() {
            $jscomposer = JsComposer::getInstance();
		return '<li class="vc_pull-right"><a id="vc_post-settings-button" class="vc_icon-btn vc_post-settings" title="'
		  .$jscomposer->esc_attr( 'Page settings', 'js_composer' ).'">'
		  .'<span id="vc_post-css-badge" class="vc_badge vc_badge-custom-css" style="display: none;">'.$jscomposer->l('CSS').'</span></a>'
		  .'</li>';
	}
	public function getControlAddElement() {
                $jscomposer = JsComposer::getInstance();
		return '<li class="vc_show-mobile">'
		  .'	<a href="javascript:;" class="vc_icon-btn vc_element-button" data-model-id="vc_element" id="vc_add-new-element" title="'
		  		.''. $jscomposer->l('Add new element') . '">'
		  .'	</a>'
		  .'</li>';
	}
	public function getControlTemplates() {
                $jscomposer = JsComposer::getInstance();
		return '<li><a href="javascript:;" class="vc_icon-btn vc_templates-button vc_navbar-border-right"  id="vc_templates-editor-button" title="'
		  . $jscomposer->l('Templates') . '"></a></li>';
	}
	public function getControlFrontend() {
//            return '';
		// if( !vc_enabled_frontend() ) return '';
                
//                return '';
                
                $jscomposer = vc_manager();

        if (!$jscomposer->isLoadJsComposer('frontend')) {
            return false;
        }

		return '<li class="vc_pull-right">'
		  .'<a href="' . vc_frontend_editor()->getInlineUrl() . '" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn" id="wpb-edit-inline">' . $jscomposer->l('Frontend') . '</a>'
		  .'</li>';
	}
	public function getControlPreview() {
		return '';/*<li class="vc_pull-right vc_preview-backend">'
		  //added to getControlsaveBacked() //
		  .'</li>';*/
	}
	public function getControlSaveBackend() {
            $jscomposer = JsComposer::getInstance();
            
		return '<li class="vc_pull-right vc_save-backend">'
		  .'<a href="javascript:;" class="vc_btn vc_btn-grey vc_btn-sm vc_navbar-btn vc_control-preview">' . $jscomposer->l('Preview') . '</a>'
		  .'<a class="vc_btn vc_btn-sm vc_navbar-btn vc_btn-primary vc_control-save" id="wpb-save-post">' . $jscomposer->l('Update') . '</a>'
		  .'</li>';
	}
}