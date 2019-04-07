<?php
class WPBakeryShortCode_custom_region extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract(JsComposer::shortcode_atts(array(
            'title' => 'No title',
            'link' => '',
            'image' => ''
        ), $atts));

        $img = wpb_getImageBySize( array( 'attach_id' => $atts['image'] ) );

        $output  = '<div class="a-region bamboo overlay">';
            $output .= '<div class="content">';
	        $output .= '<h1>' . $title . '</h1>';
            $output .= wpb_js_remove_wpautop($content, true);
	        $output .= '<a class="blue-btn" href="'. $link .'">En savoir plus</a>';
            $output .= '</div>';
	        $output .=  $img['thumbnail'];
        $output .= '</div>';

        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}