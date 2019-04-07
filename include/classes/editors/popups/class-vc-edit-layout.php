<?php
/**
 * WPBakery Visual Composer main class.
 *
 * @package WPBakeryVisualComposer
 * @since   4.3
 */
/**
 * Edit row layout
 *
 * @since   4.3
 */
class Vc_Edit_Layout {
	public function render($editor) {
//		global $vc_row_layouts;
		$editor->vc_include_template('editors/popups/panel_edit_layout.tpl.php', array(
			'vc_row_layouts' => $editor->vc_row_layouts,
                        'editor' => $editor
		));
	}
}