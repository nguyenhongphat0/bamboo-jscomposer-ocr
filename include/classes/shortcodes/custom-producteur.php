<?php
class WPBakeryShortCode_custom_producteur extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract(JsComposer::shortcode_atts(array(
            'title' => 'No title',
            'link' => '',
            'image' => ''
        ), $atts));

        $img = wpb_getImageBySize( array( 'attach_id' => $atts['image'] ) );

        $output = '<div class="a-producteur wpb_wrapper bamboo" data-name="' . $title . '" style="display: none">
            <h4 class="wpb_toggle">' . $title . '</h4>
            <div class="wpb_toggle_content" style="display: none;"> ' 
                . '<div class="col-md-6">' 
                    . $img['thumbnail'] 
                . '</div>'
                . '<div class="col-md-6">'
                    . wpb_js_remove_wpautop($content, true)
                    . '<a class="orange-link" href="' . $link . '">voir les produits</a>'
                . '</div>
            </div>
        </div>';

        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}