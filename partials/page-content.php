<?php $page_classes = '';
$page_classes = get_post_meta( $post->ID, '_dropshop_page_class_custom_page_class', true);?>

<div class="inner pad group full-height two-column <?php echo $page_classes;?>">
  <h1 class="page-title"><?php the_title(); ?></h1>
  <div class="col6">
    <div class="row">
      <?php the_content(); ?>
    </div>
    <?php get_template_part('partials/postcontent', 'socials'); ?>
  </div>
  <div class="entry-content group col6">
    <?php echo get_post_meta( $post->ID, '_dropshop_pre_content_pre_content', true);?>
    <?php get_template_part('partials/postcontent', 'embeds'); ?>
  </div>
</div>