<?php

extract(JsComposer::shortcode_atts(array(
            'id' => null,
            'title' => ''
                ), $atts));


if(empty($id)) return ;

$context = Context::getContext();
$out_put = '';

$exid = str_replace('-',',',$id);
$exid = substr($exid,strlen($exid)-1) == ',' ? substr($exid,0,-1) : $exid;


$context->controller->addCSS(_THEME_CSS_DIR_ . 'product.css');
$context->controller->addCSS(_THEME_CSS_DIR_ . 'product_list.css');
$context->controller->addCSS(_THEME_CSS_DIR_ . 'print.css', 'print');
$context->controller->addJqueryPlugin(array('fancybox', 'idTabs', 'scrollTo', 'serialScroll', 'bxslider'));
$context->controller->addJS(array(
    _THEME_JS_DIR_ . 'tools.js', // retro compat themes 1.5
//    _THEME_JS_DIR_ . 'product.js'
));

$product = vccontentanywhere::getSelectedProducts($exid);

if(!$product){
    return false;
}
$assembler = new ProductAssembler($context);

$presenterFactory = new ProductPresenterFactory($context);
$presentationSettings = $presenterFactory->getPresentationSettings();
$presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
    new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
        $context->link
    ),
    $context->link,
    new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
    new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
    $context->getTranslator()
);

$products_for_template = [];

foreach ($product as $rawProduct) {
    $products_for_template[] = $presenter->present(
        $presentationSettings,
        $assembler->assembleProduct($rawProduct),
        $context->language
    );
}

global $cookie;
$iso_code = $context->language->iso_code;
$iso_code = (in_array($iso_code, explode("/",$_SERVER['REQUEST_URI'])))? $iso_code : '';
$urls['pages']['cart'] = __PS_BASE_URI__. $iso_code . '/cart';

$page= array();
if(Tools::getValue('controller')=='cms'){
    $page['page_name'] = Tools::getValue('controller');
    $context->smarty->assign(
            array( 
                'page' => $page, 
            )
       );
} 
$context->smarty->assign(
        array(
            'vc_products' => $products_for_template,
            'vc_title' => $title,
            'urls' => $urls, 
            'elementprefix' => 'single-product',
        )
);

$template_file_name = JsComposer::getTPLPath('blocknewproducts.tpl');
$out_put .= $context->smarty->fetch($template_file_name);


echo $out_put;