<?php

global $menu, $submenu, $parent_file, $post_ID, $post;
$vc_manager = vc_manager();

$post_ID = $editor->post_id;
$post = $editor->post;
//$post_type = $editor->post->post_type;
$user_ID = Context::getContext()->employee->id;
// $user_ID = 1;

$nonce_action = 'update-post_' . $editor->post_id;
//$user_ID = isset($editor->current_user) && isset($editor->current_user->ID) ? (int) $editor->current_user->ID : 0;
$form_action = 'editpost';
$menu = array();
//add_thickbox();
//wp_enqueue_media( array( 'post' => $editor->post_id ) );
//require_once( $editor->adminFile('admin-header.php') );
$title = $editor->post->meta_title[vc_get_cms_lang_id()];



?>
<div id="vc_preloader"></div>
<script type="text/javascript">
	document.getElementById('vc_preloader').style.height = window.screen.availHeight;
	var vc_mode = '<?php echo vc_mode() ?>';
</script>
<input type="hidden" name="vc_post_title" id="vc_title-saved" value="<?php $vc_manager->esc_attr_e($title)?>" />
<input type="hidden" name="vc_post_id" id="vc_post-id" value="<?php echo $editor->post_id ?>" />
<?php
	require_once vc_path_dir('EDITORS_DIR', 'navbar/class-vc-navbar-frontend.php');
	$nav_bar = new Vc_NavBar_Frontend($post);
	$nav_bar->render();
?>
<div id="vc_inline-frame-wrapper">  
<?php global $smarty; ?>
<iframe src="<?php echo $smarty->tpl_vars['base_dir_ssl']->value; ?>index.php?fc=module&controller=test&module=jscomposer&vc_editable=true&frontend_module_name=<?php echo Tools::getValue('frontend_module_name'); ?>&val_identifier=<?php echo Tools::getValue('val_identifier'); ?>&id_lang=<?php echo Tools::getValue('id_lang'); ?>" scrolling="auto" style="width: 100%;" id="vc_inline-frame"></iframe>      
    <!-- <iframe src="http://localhost/ps-jscomposer/1.7.26.10.17/index.php?id_cms=1&controller=cms&vc_editable=true" scrolling="auto" style="width: 100%;"
            id="vc_inline-frame"></iframe> -->
	<!-- <iframe src="<?php $vc_manager->esc_attr_e( $editor->url ) ?>" scrolling="auto" style="width: 100%;"
			id="vc_inline-frame"></iframe> -->
</div>
<?php
// Add element popup
require_once vc_path_dir('EDITORS_DIR', 'popups/class-vc-add-element-box.php');
$add_element_box = new Vc_Add_Element_Box($editor);
$add_element_box->render($vc_manager);

// Edit form for mapped shortcode.
visual_composer()->editForm()->render();
// Templates manager
visual_composer()->templatesEditor()->render($vc_manager);
require_once vc_path_dir('EDITORS_DIR', 'popups/class-vc-post-settings.php');
$post_settings = new Vc_Post_Settings($editor);
$post_settings->render();
require_once vc_path_dir('EDITORS_DIR', 'popups/class-vc-edit-layout.php');
$edit_layout = new Vc_Edit_Layout();
$edit_layout->render($vc_manager);
vc_include_template('editors/partials/frontend_controls.tpl.php');

$id_lang = Tools::getValue('id_lang');
$page_type = 'cms';
$post_id = Tools::getValue('id_cms');

$optname = "_wpb_{$page_type}_{$post_id}_{$id_lang}_css";

$custom_css = Configuration::get($optname);
$custom_css = str_replace('"', '&quot;', $custom_css);
if(!$custom_css)
    $custom_css = '';

?>
<input type="hidden" class="vc_post-custom-css" value="<?php echo $custom_css ?>" autocomplete="off" />


<div data-type="text/html" id="vc_template-post-content" style="display: none;">
    <?php $editor->getPageShortcodes(); ?>
    <?php vc_include_template('editors/partials/vc_welcome_block.tpl.php') ?>
</div>
<script type="text/javascript">
    vc_post_shortcodes = <?php echo json_encode($editor->post_shortcodes); ?>
</script>
<script type="text/html" id="vc_settings-image-block">
    <li class="added">
        <div class="inner" style="width: 75px; height: 75px; overflow: hidden;text-align: center;">
            <img rel="<%= id %>" src="<%= url %>" />
        </div>
        <a href="#" class="icon-remove"></a>
    </li>
</script>
<div style="height: 1px; visibility: hidden; overflow: hidden;">
    
<?php


echo '<textarea id="content" name="content_'.  vc_get_cms_lang_id().'" style="display:none;">'.$post->content[vc_get_cms_lang_id()].'</textarea>';


?>
    <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" />
    <input type="hidden" id="hiddenaction" name="action" value="<?php echo $vc_manager->esc_attr( $form_action ) ?>" />
    <input type="hidden" id="controller_name" name="controller_name" value="<?php echo $vc_manager->esc_attr( Tools::getValue('frontend_module_name') ) ?>" />
    <input type="hidden" id="val_identifier" name="val_identifier" value="<?php echo $vc_manager->esc_attr( Tools::getValue('val_identifier') ) ?>" />
	<input type="hidden" id="originalaction" name="originalaction" value="<?php echo $vc_manager->esc_attr( $form_action ) ?>"/>
	<input type="hidden" id="id_lang" name="id_lang" value="<?php echo vc_get_cms_lang_id() ?>"/>


</div>
