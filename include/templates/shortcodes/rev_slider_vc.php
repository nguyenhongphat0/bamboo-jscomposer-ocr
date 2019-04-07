<?php
$output = $title = $alias = $el_class = '';
extract( JsComposer::shortcode_atts( array(
    'title' => '',
    'alias' => '',
    'el_class' => ''
), $atts ) );

if(!Module::isInstalled('revsliderprestashop') || !Module::isEnabled('revsliderprestashop')) return false;

$el_class = $this->getExtraClass($el_class);
$css_class =  'wpb_revslider_element wpb_content_element' . $el_class;

$output .= '<div class="'.$css_class.'">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_revslider_heading'));
//$output .= apply_filters('vc_revslider_shortcode', JsComposer::do_shortcode('[rev_slider '.$alias.']'));

if(!method_exists('RevsliderPrestashop', 'revSliderShortcode')){
	Module::getInstanceByName('revsliderprestashop');
}

if(method_exists('RevsliderPrestashop', 'revSliderShortcode')){
    $output .= RevsliderPrestashop::revSliderShortcode(array($alias));
}elseif(method_exists('RevsliderPrestashop', 'rev_slider_shortcode')){
    $output .= RevsliderPrestashop::rev_slider_shortcode(array($alias));
}

//ob_start();
//RevSliderOutput::putSlider($alias);
//$output .= ob_get_clean();

$output .= '</div>'.$this->endBlockComment('wpb_revslider_element')."\n";

echo $output;