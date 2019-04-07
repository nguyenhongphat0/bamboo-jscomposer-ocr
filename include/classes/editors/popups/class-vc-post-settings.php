<?php
  /**
   * WPBakery Visual Composer main class.
   *
   * @package WPBakeryVisualComposer
   * @since   4.3
   */
  /**
   * Post settings like custom css for page are displayed here.
   *
   * @since   4.3
   */

class Vc_Post_Settings {
	protected  $editor;
	public function __construct($editor) {
		$this->editor = $editor;
	}
	public function editor() {
		return $this->editor;
	}
	public function render() {
//            var_dump('I am here...');
		vc_include_template('editors/popups/panel_post_settings.tpl.php', array(
			'box' => $this
		));
	}
}