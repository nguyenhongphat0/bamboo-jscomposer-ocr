<div id="titlediv"></div>
  
<div id="postbox-container-2" class="postbox-container">        
    <div id="normal-sortables" class="meta-box-sortables">
        <div id="wpb_visual_composer" class="postbox">
            <div class="inside">

                <?php
                $post = '';
                require_once vc_path_dir('EDITORS_DIR', 'navbar/class-vc-navbar.php');
                $nav_bar = new Vc_Navbar($post);
                $nav_bar->render();
                ?>
                <div class="metabox-composer-content">
                        <div id="visual_composer_content" class="wpb_main_sortable main_wrapper"></div>
                        <?php require vc_path_dir("TEMPLATES_DIR", 'editors/partials/vc_welcome_block.tpl.php'); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php


if ( !isset($wpb_vc_status) ) {
	$wpb_vc_status = false;
}

?>
<input type="hidden" name="vc_js_composer_group_access_show_rule" class="vc_js_composer_group_access_show_rule" value="" />
<input type="hidden" id="wpb_vc_js_status" name="wpb_vc_js_status" value="<?php $editor->esc_attr_e($wpb_vc_status) ?>" />
<input type="hidden" id="wpb_vc_loading" name="wpb_vc_loading" value="<?php echo $editor->l("Loading, please wait...") ?>" />
<input type="hidden" id="wpb_vc_loading_row" name="wpb_vc_loading_row" value="<?php echo $editor->l("Crunching...") ?>" />
<input type="hidden" id="wpb_vc_js_interface_version" name="wpb_vc_js_interface_version" value="<?php $editor->esc_attr_e('2') ?>" />


<?php 
foreach($editor->post_custom_css as $id_lang => $custom_css){
    if(!$custom_css){
        $custom_css = '';
    }
?>
<?php
if(Tools::getValue('controller') == 'AdminBlogPost'){
    $page_type = 'smartblog';
    $page_id = Tools::getValue('id_smart_blog_post') ? Tools::getValue('id_smart_blog_post') : "null";
}
elseif(Tools::getValue('controller') == 'AdminSuppliers'){
    $page_type = 'sup';
    $page_id = Tools::getValue('id_supplier') ? Tools::getValue('id_supplier') : "null";
}
elseif(Tools::getValue('controller') == 'AdminManufacturers'){
    $page_type = 'man';
    $page_id = Tools::getValue('id_manufacturer') ? Tools::getValue('id_manufacturer') : "null";
}
elseif(Tools::getValue('controller') == 'AdminCategories'){
    $page_type = 'cat';
    $page_id = Tools::getValue('id_category') ? Tools::getValue('id_category') : "null";
}
elseif(Tools::getValue('controller') == 'AdminCmsContent'){
    $page_id = Tools::getValue('id_cms') ? Tools::getValue('id_cms') : "null";
    $page_type = 'cms';
}elseif(Tools::getValue('controller') =='Adminvccontentanywhere'){
    $page_id = Tools::getValue('id_vccontentanywhere') ? Tools::getValue('id_vccontentanywhere') : "null";
    $page_type = 'vccaw';
}elseif(Tools::getValue('controller') == 'Adminvcproducttabcreator'){
    $page_id = Tools::getValue('id_vcproducttabcreator') ? Tools::getValue('id_vcproducttabcreator') : "null";
    $page_type = 'vctc';
}else{
    $page_id = Tools::getValue('id_cms') ? Tools::getValue('id_cms') : "null";
    $page_type = 'cms';
}

if(!empty(JsComposer::$vcCustomPageId)){
    $page_id = JsComposer::$vcCustomPageId;
    $page_type = JsComposer::$vcCustomPageType;
}


$vc_optname = "_wpb_{$page_type}_{$page_id}_{$id_lang}_css";
$vc_css_custom = Configuration::get($vc_optname);
$vc_css_custom_out = (isset($vc_css_custom) && !empty($vc_css_custom)) ? $vc_css_custom : $custom_css;

$vc_css_custom_out = str_replace('"', '&quot;', $vc_css_custom_out);
?>
<input type="hidden" class="vc_post-custom-css" data-id_lang="<?php echo $id_lang?>" value="<?php echo $vc_css_custom_out ?>" autocomplete="off" />

<?php }?>