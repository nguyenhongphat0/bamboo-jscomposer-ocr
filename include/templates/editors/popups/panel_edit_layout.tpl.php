<div id="vc_row-layout-panel" class="vc_panel" style="display: none;">
	<div class="vc_panel-heading">
		<a title="<?php echo $editor->l('Close panel'); ?>" href="#" class="vc_close" data-dismiss="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>
		<a title="<?php echo $editor->l('Hide panel'); ?>" href="#" class="vc_transparent" data-transparent="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>

		<h3 class="vc_panel-title"><?php echo $editor->l('Row layout') ?></h3>
	</div>
	<div class="vc_panel-body vc_properties-list wpb-edit-form">
		<div class="vc_row wpb_edit_form_elements">
			<div class="vc_col-sm-12 vc_column vc_layout-panel-switcher">
				<div class="wpb_element_label"><?php echo $editor->l('Row layout') ?></div>
				<?php foreach($vc_row_layouts as $layout): ?>
				<a class="vc_layout-btn <?php echo $layout['icon_class']
				  .'" data-cells="'.$layout['cells']
				  .'" data-cells-mask="'.$layout['mask']
				  .'" title="'.$layout['title'] ?>"><span class="icon"></span></a>
				<?php endforeach; ?>
				<span
				  class="vc_description vc_clearfix"><?php echo $editor->l('Choose row layout from predefined options.'); ?></span>
			</div>
			<div class="vc_col-sm-12 vc_column">
				<div class="wpb_element_label"><?php echo $editor->l('Enter custom layout for your row') ?></div>
				<div class="edit_form_line">
					<input name="padding" class="wpb-textinput vc_row_layout" type="text" value="" id="vc_row-layout">
					<button id="vc_row-layout-update"
							class="vc_btn vc_btn-primary vc_btn-sm"><?php echo $editor->l('Update') ?></button>
					<span
					  class="vc_description vc_clearfix"><?php echo $editor->l('Change particular row layout manually by specifying number of columns and their size value.'); ?></span>
				</div>
			</div>
		</div>
	</div>
</div>