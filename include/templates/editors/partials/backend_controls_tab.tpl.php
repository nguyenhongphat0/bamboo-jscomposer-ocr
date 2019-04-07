<div class="vc_controls<?php echo !empty($extended_css) ? ' '.$extended_css : '' ?>">
	<div class="vc_controls-<?php echo $position ?>">
		<a class="vc_element-name">
				<span class="vc_btn-content"><?php echo $name; ?></span>
		</a>
		<?php foreach($controls as $control): ?>
		<?php if($control === 'add'): ?>
			<a class="vc_control-btn vc_control-btn-prepend vc_edit" href="#"
			   title="<?php printf(  'Prepend to %s', $name ) ?>"><span
			  class="vc_btn-content"><span class="icon"></span></span></a>
			<?php elseif($control === 'edit'): ?>
			<a class="vc_control-btn vc_control-btn-edit" href="#"
			   title="<?php printf(  'Edit %s', $name ) ?>"><span
			  class="vc_btn-content"><span class="icon"></span></span></a>
			<?php elseif($control === 'clone'): ?>
			<a class="vc_control-btn vc_control-btn-clone" href="#"
			   title="<?php printf(  'Clone %s', $name ) ?>"><span
			  class="vc_btn-content"><span class="icon"></span></span></a>
			<?php elseif($control === 'delete'): ?>
			<a class="vc_control-btn vc_control-btn-delete" href="#"
			   title="<?php printf(  'Delete %s', $name ) ?>"><span
			  class="vc_btn-content"><span class="icon"></span></span></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>