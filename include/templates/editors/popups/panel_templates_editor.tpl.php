<div id="vc_templates-editor" class="vc_panel vc_templates-editor" style="display: none;">
	<div class="vc_panel-heading">
		<a title="<?php echo $editor->l( 'Close panel' ); ?>" href="#" class="vc_close" data-dismiss="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>
		<a title="<?php echo $editor->l( 'Hide panel' ); ?>" href="#" class="vc_transparent" data-transparent="panel"
		   aria-hidden="true"><i class="vc_icon"></i></a>

		<h3 class="vc_panel-title"><?php echo $editor->l('Templates') ?></h3>
	</div>
	<div class="vc_panel-body wpb-edit-form vc_templates-body vc_properties-list vc_with-tabs">
		<div class="vc_row wpb_edit_form_elements">
			<div class="vc_column">
					<div id="vc_tabs-templates" class="vc_panel-tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
						<?php $templates = $editor->loadDefaultTemplates();  $templates_exists = is_array( $templates ) && !empty( $templates ); ?>
						<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
							<li><a href="#tabs-templates-tabs-1"><?php echo $editor->l('My templates'); ?></a></li>
							<?php if( $templates_exists ): ?>
							<li><a href="#tabs-templates-tabs-2"><?php echo $editor->l('Default templates'); ?></a></li>
							<?php endif; ?>
						</ul>
						<div id="tabs-templates-tabs-1">
							<div class="vc_col-sm-12 vc_column inside">
								<div class="wpb_element_label"><?php echo $editor->l('Save current layout as a template') ?></div>
								<div class="edit_form_line">
									<input name="padding" class="wpb-textinput vc_title_name" type="text" value="" id="vc_template-name"
										   placeholder="<?php echo $editor->l( 'Template name' ) ?>">
									<button id="vc_template-save"
											class="vc_btn vc_btn-primary vc_btn-sm"><?php echo $editor->l( 'Save template' ) ?></button>
								</div>
								<span
				  					class="vc_description"><?php echo $editor->l( 'Save your layout and reuse it on different sections of your website' ) ?></span>
							</div>
							<div class="vc_col-sm-12 vc_column">
								<div class="wpb_element_label"><?php echo $editor->l('Load Template') ?></div>
								<span
									class="vc_description"><?php echo $editor->l( 'Append previosly saved template to the current layout' ) ?></span>
								<ul class="wpb_templates_list" id="vc_template-list">
									<?php $box->renderMenu(true) ?>
								</ul>
							</div>
						</div>
						<?php if( $templates_exists ): ?>
						<div id="tabs-templates-tabs-2">
							<div class="vc_col-sm-12 vc_column inside">

								<div class="wpb_element_label"><?php echo $editor->l('Load Template'); ?></div>
								<span class="description"><?php echo $editor->l('Append default template to the current layout'); ?></span>
								<ul id="vc_default-template-list" class="wpb_templates_list">
									<?php foreach( $templates as $key => $template ): ?>
									<li class="wpb_template_li"><a href="#" data-template_name="<?php echo $key; ?>"><?php echo $template['name']; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
		</div>
	</div>
	<div class="vc_panel-footer">
		<button type="button" class="vc_btn vc_btn-default vc_close"
				data-dismiss="panel"><?php echo $editor->l( 'Close' ) ?></button>
	</div>
</div>