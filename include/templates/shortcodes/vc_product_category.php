<?php

extract(JsComposer::shortcode_atts(array(
            'id_category' => null,
            'title' => '',
            'page' => '0',
            'per_page' => '12',
            'orderby' => 'position',
            'order' => 'DESC',
            'display_type' => 'grid',
                ), $atts));
if (empty($id_category))
    return;
$context = Context::getContext();

$cache_products = vccontentanywhere::getProductsByCategoryID($id_category, null, null, $per_page, $orderby, $order);
if(!$cache_products){
    return false;
}
$context->controller->addCSS(_THEME_CSS_DIR_ . 'product.css');
$context->controller->addCSS(_THEME_CSS_DIR_ . 'product_list.css');
$context->controller->addCSS(_THEME_CSS_DIR_ . 'print.css', 'print');
$context->controller->addJqueryPlugin(array('fancybox', 'idTabs', 'scrollTo', 'serialScroll', 'bxslider'));
$context->controller->addJS(array(
    _THEME_JS_DIR_ . 'tools.js', // retro compat themes 1.5
//    _THEME_JS_DIR_ . 'product.js'
));

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

foreach ($cache_products as $rawProduct) {
    $products_for_template[] = $presenter->present(
        $presentationSettings,
        $assembler->assembleProduct($rawProduct),
        $context->language
    );
}
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
            'elementprefix' => 'productbycat',
        )
);
if ($display_type == 'sidebar')
    $output = $context->smarty->fetch(JsComposer::getTPLPath('blockviewed.tpl'));
else
    $output = $context->smarty->fetch(JsComposer::getTPLPath('blocknewproducts.tpl'));

echo $output;