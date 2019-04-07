<?php

extract(JsComposer::shortcode_atts(
    array(
        'title' => '',
        'speed'=>'600',
        'maxslide'=>'6',
        'img_size'=>'',
        'slider_type' => 'bxslider'
    ),$atts
));
$context = Context::getContext(); 
$manufacturers = Manufacturer::getManufacturers(false,$context->language->id, true);
$context->smarty->assign(
    array(
        'title' => $title,
        'manufacturers' => $manufacturers,
        'speed' => $speed,
        'maxslide' => $maxslide,
        'man_img_size' => $img_size,
        'img_manu_dir' => _PS_IMG_.'m/',
        'slider_type' => $slider_type,
        'type' => 'manufacturer',
        
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

$output = $context->smarty->fetch(JsComposer::getTPLPath('vc_product_manufacturers.tpl'));

echo $output;