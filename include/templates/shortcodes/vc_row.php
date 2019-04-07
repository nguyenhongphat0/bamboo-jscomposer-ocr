<?php
if (!defined('_PS_VERSION_')){
	exit;
}

//$full_height = $full_width = $content_placement = $parallax = $parallax_image = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = '';
//$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = '';
//$after_output = '';

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $full_width
 * @var $full_height
 * @var $equal_height
 * @var $columns_placement
 * @var $content_placement
 * @var $parallax
 * @var $parallax_image
 * @var $css
 * @var $el_id
 * @var $video_bg
 * @var $video_bg_url
 * @var $video_bg_parallax
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Row
 */

$el_class = $full_height = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = '';
$output = $after_output = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract($atts);

if(!vc_manager()->is_admin()){
    Context::getContext()->controller->addJS(vc_asset_url( 'js/js_composer_front.js' ));
}
$el_class = $this->getExtraClass($el_class);

if(isset($css) && !empty($css)){
	$css_out = '<style>'.$css.'</style>';
	$output .= $css_out;
}

$css_classes = array(
	'vc_row',
	'wpb_row', //deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

if (vc_shortcode_custom_css_has_property( $css, array('border', 'background') ) || $video_bg || $parallax) {
	$css_classes[]='vc_row-has-fill';
}

if (!empty($atts['gap'])) {
	$css_classes[] = 'vc_column-gap-'.$atts['gap'];
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . Tools::htmlentitiesUTF8( $el_id ) . '"';
}

if ( ! empty( $full_width ) ) {
	$wrapper_attributes[] = 'data-vc-full-width="true"';
	$wrapper_attributes[] = 'data-vc-full-width-init="false"';
	if ( 'stretch_row_content' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
	} elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
		$wrapper_attributes[] = 'data-vc-stretch-content="true"';
		$css_classes[] = 'vc_row-no-padding';
	}
	$after_output .= '<div class="vc_row-full-width"></div>';
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = ' vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = ' vc_row-o-columns-' . $columns_placement;
	}
}

if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = ' vc_row-o-equal-height';
}

if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = ' vc_row-o-content-' . $content_placement;
}

if ( ! empty( $flex_row ) ) {
	$css_classes[] = ' vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

if ( $has_video_bg ) {
	$parallax = $video_bg_parallax;
	$parallax_image = $video_bg_url;
	$css_classes[] = ' vc_video-bg-container';
//	wp_enqueue_script( 'vc_youtube_iframe_api_js' );
    Context::getContext()->controller->addJS('https://www.youtube.com/iframe_api');
}

if ( ! empty( $parallax ) ) {
//	wp_enqueue_script( 'vc_jquery_skrollr_js' );
    Context::getContext()->controller->addJS(vc_asset_url( 'lib/bower/skrollr/dist/skrollr.min.js' ));
	$wrapper_attributes[] = 'data-vc-parallax="1.5"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
	if ( $has_video_bg ) {
		$parallax_image_src = $parallax_image;
	} else {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = JsComposer::getFullImageUrl( $parallax_image_id );
	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . Tools::htmlentitiesUTF8( $parallax_image_src ) . '"';
}
if ( ! $parallax && $has_video_bg ) {
	$wrapper_attributes[] = 'data-vc-video-bg="' . Tools::htmlentitiesUTF8( $video_bg_url ) . '"';
}

//$css_class =  'vc_row wpb_row '. get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' );

$hook_args = array('atts'=>$atts, 'base' => $this->settings['base'], 'css_classes' => '');
$css_class = implode( ' ', array_filter( $css_classes ));
$css_class .= preg_replace( '/\s+/', ' ', Hook::exec('VcShortcodesCssClass', $hook_args)) ;
$wrapper_attributes[] = 'class="' . Tools::htmlentitiesUTF8( trim( $css_class ) ) . '"';

//$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);
//$wrapper_attributes[] = $style;

//$output .= '<div class="'.$css_class.'"'.$style.'>';
$output .= '<div '.implode( ' ', $wrapper_attributes ).'>';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>'.$after_output.$this->endBlockComment('row');

echo $output;