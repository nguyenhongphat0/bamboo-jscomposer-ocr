<?php
$output = $title = $type = $onclick = $custom_links = $img_size = $custom_links_target = $images = $el_class = $interval = '';
extract( JsComposer::shortcode_atts( array(
	'title' => '',
	'type' => 'flexslider',
	'eventclick' => 'link_image',
	'custom_links' => '',
	'custom_links_target' => '',
	'img_size' => 'thumbnail',
	'images' => '',
	'el_class' => '',
	'onclick' => '',
	'interval' => '5',
), $atts ) );
$gal_images = '';
$link_start = '';
$link_end = '';
$el_start = '';
$el_end = '';
$slides_wrap_start = '';
$slides_wrap_end = '';
$onclick =$eventclick;
$el_class = $this->getExtraClass( $el_class );
if ( $type == 'nivo' ) {
	$type = ' wpb_slider_nivo theme-default';
//	wp_enqueue_script( 'nivo-slider' );
//	wp_enqueue_style( 'nivo-slider-css' );
//	wp_enqueue_style( 'nivo-slider-theme' );
        if(Configuration::get('vc_load_nivo_js') != 'no'){
        	// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<script type="text/javascript" src="'.vc_asset_url( 'lib/nivoslider/jquery.nivo.slider.pack.js' ).'"></script>'; }
        	// else 
            Context::getContext()->controller->addJS(vc_asset_url( 'lib/nivoslider/jquery.nivo.slider.pack.js' ));
        }
        if(Configuration::get('vc_load_nivo_css') != 'no'){
        	// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<link rel="stylesheet" href="'.vc_asset_url( 'lib/nivoslider/nivo-slider.css' ).'" type="text/css" media="all">'; }
        	// else
        	Context::getContext()->controller->addCSS(vc_asset_url( 'lib/nivoslider/nivo-slider.css' ));
    	}
    	// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<link rel="stylesheet" href="'.vc_asset_url( 'lib/nivoslider/themes/default/default.css' ).'" type="text/css" media="all">'; }
    	// else 
        Context::getContext()->controller->addCSS(vc_asset_url( 'lib/nivoslider/themes/default/default.css' ));
        

	$slides_wrap_start = '<div class="nivoSlider">';
	$slides_wrap_end = '</div>';
} else if ( $type == 'flexslider' || $type == 'flexslider_fade' || $type == 'flexslider_slide' || $type == 'fading' ) {
	$el_start = '<li>';
	$el_end = '</li>';
	$slides_wrap_start = '<ul class="slides">';
	$slides_wrap_end = '</ul>';
	if(Configuration::get('vc_load_flex_css') != 'no'){
		// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<link rel="stylesheet" href="'.vc_asset_url( 'lib/flexslider/flexslider.css' ).'" type="text/css" media="all">'; }
		// else 
		Context::getContext()->controller->addCSS(vc_asset_url( 'lib/flexslider/flexslider.css' ));
	}
	if(Configuration::get('vc_load_flex_js') != 'no'){
		// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<script type="text/javascript" src="'.vc_asset_url( 'lib/flexslider/jquery.flexslider-min.js' ).'"></script>'; }
		// else 
		Context::getContext()->controller->addJS(vc_asset_url( 'lib/flexslider/jquery.flexslider-min.js' ));
	}

} else if ( $type == 'image_grid' ) {
//	wp_enqueue_script( 'isotope' );
    	// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<script type="text/javascript" src="'.vc_asset_url( 'lib/isotope/dist/isotope.pkgd.min.js' ).'"></script>'; }
    	// else 
        Context::getContext()->controller->addJS(vc_asset_url( 'lib/isotope/dist/isotope.pkgd.min.js' ));
//        Context::getContext()->controller->addJS(vc_asset_url( 'lib/isotope/jquery.isotope.min.js' ));
	$el_start = '<li class="isotope-item">';
	$el_end = '</li>';
	$slides_wrap_start = '<ul class="wpb_image_grid_ul">';
	$slides_wrap_end = '</ul>';
}

if ( $eventclick == 'link_image' ) {
//	wp_enqueue_script( 'prettyphoto' );
//	wp_enqueue_style( 'prettyphoto' );
		// if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') { echo '<link rel="stylesheet" href="'.vc_asset_url( 'lib/prettyphoto/css/prettyPhoto.css' ).'" type="text/css" media="all">'; }
		// else
        Context::getContext()->controller->addCSS(vc_asset_url( 'lib/prettyphoto/css/prettyPhoto.css' ));
        // if (Tools::getValue('controller') == 'VC_ajax' OR Tools::getValue('controller') == 'VC_frontend') {  echo '<script type="text/javascript" src="'.vc_asset_url( 'lib/prettyphoto/js/jquery.prettyPhoto.js' ).'"></script>'; }
        // else
        Context::getContext()->controller->addJS(vc_asset_url( 'lib/prettyphoto/js/jquery.prettyPhoto.js' ));
}

$flex_fx = '';
if ( $type == 'flexslider' || $type == 'flexslider_fade' || $type == 'fading' ) {
	$type = ' wpb_flexslider flexslider_fade flexslider';
	$flex_fx = ' data-flex_fx="fade"';
} else if ( $type == 'flexslider_slide' ) {
	$type = ' wpb_flexslider flexslider_slide flexslider';
	$flex_fx = ' data-flex_fx="slide"';
} else if ( $type == 'image_grid' ) {
	$type = ' wpb_image_grid';
}


/*
 else if ( $type == 'fading' ) {
    $type = ' wpb_slider_fading';
    $el_start = '<li>';
    $el_end = '</li>';
    $slides_wrap_start = '<ul class="slides">';
    $slides_wrap_end = '</ul>';
    wp_enqueue_script( 'cycle' );
}*/

//if ( $images == '' ) return null;
if ( $images == '' ) $images = '-1,-2,-3';

$pretty_rel_random = ' rel="prettyPhoto[rel-' . rand() . ']"'; //rel-'.rand();

if ( $onclick == 'custom_link' ) {
	$custom_links = explode( ',', $custom_links );
}
// var_dump($onclick =$eventclick);
$images = explode( ',', $images );
$i = - 1;

foreach ( $images as $attach_id ) {
	$i ++;
	if ( $attach_id > 0 ) {
		$post_thumbnail = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $img_size ) );
	} else {
		$post_thumbnail = array();
//		$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
//		$post_thumbnail['p_img_large'] = vc_asset_url( 'vc/no_image.png' );
	}

	$thumbnail = isset($post_thumbnail['thumbnail']) ? $post_thumbnail['thumbnail'] : '';
	$p_img_large = isset($post_thumbnail['p_img_large']) ? $post_thumbnail['p_img_large'] : '';
	$link_start = $link_end = '';

	if ( $onclick == 'link_image' ) {
		$link_start = '<a class="prettyphoto" href="' . $p_img_large . '"' . $pretty_rel_random . '>';
		$link_end = '</a>';
	} else if ( $onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != '' ) {
		$link_start = '<a href="' . $custom_links[$i] . '"' . ( ! empty( $custom_links_target ) ? ' target="' . $custom_links_target . '"' : '' ) . '>';
		$link_end = '</a>';
	}
	$gal_images .= $el_start . $link_start . $thumbnail . $link_end . $el_end;
}
$css_class =  'wpb_gallery wpb_content_element' . $el_class . ' vc_clearfix';
$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_gallery_heading' ) );
$output .= '<div class="wpb_gallery_slides' . $type . '" data-interval="' . $interval . '"' . $flex_fx . '>' . $slides_wrap_start . $gal_images . $slides_wrap_end . '</div>';
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_gallery' );

echo $output;