<?php
$vc_manager = vc_manager();
$block = $block_data[0];
$settings = $block_data[1];
$link_setting = empty($settings[0]) ? '' : $settings[0];
?>
<?php if($block === 'title'): ?>
<h2 class="post-title">
    <?php echo empty($link_setting) || $link_setting!='no_link' ? $this->getLinked($post, $post->title, $link_setting, 'link_title') : $post->title ?>
</h2>
<?php elseif($block === 'image' && !empty($post->thumbnail)): ?>
<div class="post-thumb">
    <?php echo empty($link_setting) || $link_setting!='no_link' ? $this->getLinked($post, $post->thumbnail, $link_setting, 'link_image') : $post->thumbnail ?>
</div>
<?php elseif($block === 'text'): ?>
<div class="entry-content">
    <?php echo empty($link_setting) || $link_setting==='text' ?  $post->content : $post->excerpt; ?>
</div>
<?php elseif($block === 'link'): ?>
<a href="<?php echo $post->link ?>" class="btn btn-default vc_read_more"
   title="<?php echo $vc_manager->esc_attr( sprintf( $vc_manager->vcTranslate( 'Permalink to %s' ), $post->title_attribute ) ); ?>"<?php echo $this->link_target ?>><?php echo $vc_manager->vcTranslate( 'Read more') ?></a>
<?php endif; ?>