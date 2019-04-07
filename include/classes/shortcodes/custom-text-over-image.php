<?php
class WPBakeryShortCode_custom_text_over_image extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract(JsComposer::shortcode_atts(array(
            'heading' => 'No Heading',
            'image' => ''
        ), $atts));

        $img = wpb_getImageBySize( array( 'attach_id' => $atts['image'] ) );

        $output  = '<div class="text-over-image bamboo overlay">';
            $output .= '<div class="content">';
	        $output .= '<h1 class="tttab-desc">' . $heading . '</h1>';
            $output .= $content;
            $output .= '</div>';
	        $output .=  $img['thumbnail'];
        $output .= '</div>';

        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}