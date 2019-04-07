<div id="vc_no-content-helper" class="vc_welcome">
	<span class="icon"></span>
	<h5><?php 
        if(!isset($editor) || !$editor instanceof JsComposer)
            $editor = vc_manager ();
        echo $editor->l('You have blank page <br>
Start adding content or templates '); ?></h5>
	
	<div class="vc_buttons">
	    <a id="vc_not-empty-add-element" class="vc_add-element-not-empty-button vc_add-element-action"
		   title="<?php echo $editor->l( 'Add element' ) ?>"></a>
		<a id="vc_no-content-add-element"
		   class="vc_add-element-button vc_add-element-action vc_btn vc_btn-grace vc_btn-md vc_btn_3d" href="#"
		   title="<?php echo $editor->l('Add Element') ?>"><i class="vc_ui-icon-pixel vc_ui-icon-pixel-control-plus"></i><?php echo $editor->l('Add Element') ?></a>
		<a id="vc_no-content-add-text-block" class="vc_add-text-block-button vc_btn vc_btn-sky vc_btn-md vc_btn_3d"
		   href="#"
		   title="<?php echo $editor->l('Add Text Block')?>"><i class="vc_ui-icon-pixel vc_ui-icon-pixel-control-edit"></i><?php echo $editor->l('Add Text Block')?></a>
        <a id="vc_no-content-add-text-block" class="vc_add-text-block-button vc_btn vc_ui-button-warning vc_btn-md vc_btn_3d"
		   href="#"
		   title="<?php echo $editor->l('Add Template')?>"><i class="vc_ui-icon-pixel vc_ui-icon-pixel-control-template"></i><?php echo $editor->l('Add Template')?></a>
	</div>
	<?php $templates = $editor->loadDefaultTemplates(); ?>
	<?php if( is_array( $templates ) && !empty( $templates ) ): ?>
	<div class="vc_default-templates-separator vc_element vc_vc_text_separator"><div class="vc_separator vc_sep_dashed vc_separator_align_center vc_el_width_100 vc_sep_color_outline_grey">
		<span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span>
		<h4 class="normal"><?php echo $editor->l('Choose Your Layout') ?></h4><span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
	</div>
	</div>
	<div class="vc_default-templates">
		<div class="wpb_row vc_row-fluid">

			<?php foreach( $templates as $key => $template ): ?>
			<?php if( isset( $template['show_on_welcome_block'] ) && false === $template['show_on_welcome_block'] ) { continue; } ?>
			<div class="vc_template<?php if( isset( $template['custom_class'] ) && strlen( trim( $template['custom_class'] ) ) > 0 ): echo ' '.$template['custom_class']; endif; ?>" data-template_name="<?php echo $key; ?>">
				<div class="wpb_wrapper">

					<div class="wpb_single_image">
						<div class="wpb_wrapper">
							<div class="vc_templates-image"<?php if( isset( $template['image_path'] ) ): ?> style="background-image:url('<?php echo $template['image_path']; ?>');"<?php endif; ?>></div>
						</div>
					</div>

					<div class="wpb_text_column">
						<div class="wpb_wrapper">
							<p><?php echo $template['name']; ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
</div>