<?php

extract(JsComposer::shortcode_atts(
    array(
        'title' => '',
        'id_categories' => null,
        'speed'=>'600',
        'maxslide'=>'6',
        'img_size'=>'',
        'slider_type' => 'bxslider'
    ),$atts
));

if(empty($id_categories)) return ;

$exid = str_replace('-',',',$id_categories);
$exid = substr($exid,strlen($exid)-1) == ',' ? substr($exid,0, -1) : $exid;

$context = Context::getContext(); 

$categories = vccontentanywhere::getSelectedCategories($exid);

$context->smarty->assign(
    array(
        'title' => $title,
        'categories' => $categories,
        'speed' => $speed,
        'maxslide' => $maxslide,
        'cat_img_size' => $img_size,
        'img_cat_dir' => _PS_IMG_.'c/',
        'slider_type' => $slider_type,
        'type' => 'category',        
    )
);
if($slider_type == 'flexslider'){
    if(Configuration::get('vc_load_flex_css') != 'no'){
            $context->controller->addCSS(vc_asset_url( 'lib/flexslider/flexslider.css' ));
    }
    if(Configuration::get('vc_load_flex_js') != 'no'){
            $context->controller->addJS(vc_asset_url( 'lib/flexslider/jquery.flexslider-min.js' ));
    }    
}else{
    $context->controller->addJqueryPlugin(array('bxslider'));
}

$output = $context->smarty->fetch(JsComposer::getTPLPath('vc_product_categories.tpl'));

echo $output;