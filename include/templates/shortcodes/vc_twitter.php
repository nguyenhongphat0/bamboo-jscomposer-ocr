<?php
$output = $width = $el_class = $title = $twitter_name = $tweet_count = $el_position = $tweets_count = '';

extract( JsComposer::shortcode_atts( array(
	'title' => '',
	'twitter_name' => 'twitter',
	'tweets_count' => 5,
	'el_class' => ''
), $atts ) );

//wp_enqueue_script( 'tweet' );
Context::getContext()->controller->addJS(vc_asset_url( 'lib/jquery.tweet/jquery.tweet.js' ));

$ssl_enable = Configuration::get('PS_SSL_ENABLED');
$base = ($ssl_enable == 1) ? 'https://' : 'http://';

$el_class = $this->getExtraClass( $el_class );
$css_class =  'wpb_twitter_widget wpb_content_element' . $el_class;

$output .= "\n\t" . '<div class="' . $css_class . '">';
$output .= "\n\t\t" . '<div class="wpb_wrapper">';
'';
$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_twitter_heading' ) );
$output .= "\n\t\t\t" . '<div class="tweets" data-tw_name="' . $twitter_name . '" data-tw_count="' . $tweets_count . '"></div> <p class="twitter_follow_button_wrap"><a class="wpb_follow_btn twitter_follow_button" href="'.$base.'twitter.com/' . $twitter_name . '">' . __( "Follow us on twitter", "js_composer" ) . '</a></p>';
$output .= "\n\t\t" . '</div> ' . $this->endBlockComment( '.wpb_wrapper' );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_twitter_widget' );

echo $output;