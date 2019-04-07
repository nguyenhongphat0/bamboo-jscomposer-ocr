<?php
class WPBakeryShortCode_VC_Facebook extends WPBakeryShortCode {
    protected function contentInline( $atts, $content = null ) {
        extract(JsComposer::shortcode_atts(array(
            'type' => 'standard',//standard, button_count, box_count
            'url' => ''
        ), $atts));
        
        $id_cms = Tools::getValue('id_cms');
        
        if ( $url == '') $url = JsComposer::getCMSLink($id_cms);
        
//        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_social-placeholder fb_like wpb_content_element vc_socialtype-' . $type, $this->settings['base'], $atts );
        $css_class = 'vc_social-placeholder fb_like wpb_content_element vc_socialtype-' . $type;
        return '<a href="'.$url.'" class="'.$css_class.'"></a>';
    }
}