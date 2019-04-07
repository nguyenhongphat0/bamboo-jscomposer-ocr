<?php

$vc_main = vc_manager();

extract(SmartShortCode::shortcode_atts(array(
          'id_supplier' => '1',
          'title' => '',
          'page' => '1',
          'per_page' => '12',
          'orderby' => 'position',
          'order' => 'DESC',
          'display_type' => 'grid',
        ), $atts));
        
    	$context = Context::getContext();         
    	$out_put = '';
        

        $cache_products = Supplier::getProducts($id_supplier, $context->language->id, $page, $per_page, $orderby, $order, false);
            
    	  if(is_array($cache_products) && !empty($cache_products)){
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
              
                if($display_type == 'grid'){
                    
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
                         'new_products' => $products_for_template,     
                         'sds_title' => $title
	                     )
	                );
                        $template_file_name = JsComposer::getTPLPath('blocknewproducts.tpl');
                        $out_put = $context->smarty->fetch($template_file_name);
                }else{
                    //$thecats = Category::getCategoryInformations(array($id_category));
                    $sds_title = "";
//                    if(isset($thecats[(int)$id_category])){
//                        $sds_title .= $thecats[(int)$id_category]['name'].' ';
//                    }
                    $sds_title .= $vc_main->l('Products');
                    $context->smarty->assign(
                        array(
                            'productsViewedObj' => $products_for_template,
                            'sds_title' => $title
                        )
                    );
                    $template_file_name = JsComposer::getTPLPath('blockviewed.tpl');
		 			$out_put = $context->smarty->fetch($template_file_name);
                }
        }else{
            $out_put = $vc_main->l('No products have been found.');
        }
          echo $out_put;