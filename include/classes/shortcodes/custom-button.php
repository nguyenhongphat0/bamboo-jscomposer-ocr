<?php

class WPBakeryShortCode_custom_button extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $output = 'No param';
        if (isset($_GET['param'])) {
            $output = $_GET['param'];
        }
        $img = wpb_getImageBySize( array( 'attach_id' => $atts['image'] ) );
        echo $img['thumbnail'];
        return $output;
    }
}
