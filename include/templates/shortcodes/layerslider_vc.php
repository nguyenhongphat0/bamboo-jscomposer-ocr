<?php
$output = $title = $id = $el_class = '';
extract( JsComposer::shortcode_atts( array(
    'title' => '',
    'id' => '',
    'el_class' => ''
), $atts ) );

$el_class = $this->getExtraClass($el_class);
$css_class =  'wpb_layerslider_element wpb_content_element' . $el_class;

$output .= '<div class="'.$css_class.'">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_layerslider_heading'));
$output .= apply_filters('vc_layerslider_shortcode', do_shortcode('[layerslider id="'.$id.'"]'));
$output .= '</div>'.$this->endBlockComment('wpb_layerslider_element')."\n";

echo $output;