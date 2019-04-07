<?php
class WPBakeryShortCode_VC_Posts_slider extends WPBakeryShortCode {
    protected function getPostThumbnail( $post_id, $grid_thumb_size = 'full' ) {
            $nthumbs = JsComposer::getSmartBlogPostsThumbSizes();
            
            if(in_array($grid_thumb_size, array_values($nthumbs)))            
                return "{$post_id}-{$grid_thumb_size}.jpg";
            else
                return "{$post_id}.jpg";

	}
}