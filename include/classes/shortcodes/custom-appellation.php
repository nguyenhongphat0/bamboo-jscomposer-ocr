<?php
class WPBakeryShortCode_custom_appellation extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract(JsComposer::shortcode_atts(array(
            'title' => 'No title',
            'link' => ''
        ), $atts));

        if ($link == '') {
            $link = 'default-link';
        }

        $output  = '<div class="an-appellation bamboo">';
            $output .= '<div class="content">';
	        $output .= '<h1>' . $title . '</h1>';
            $output .= wpb_js_remove_wpautop($content, true);
	        $output .= '<a class="blue-btn" href="'. $link .'">Read more</a>';
            $output .= '</div>';
        $output .= '</div>';

        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}