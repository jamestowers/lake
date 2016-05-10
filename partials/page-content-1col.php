<?php $page_classes = '';
$page_classes = get_post_meta( $post->ID, '_dropshop_page_class_custom_page_class', true);?>

<div class="inner pad group full-height single-column <?php echo $page_classes;?>">
    <h1 class="page-title"><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <?php get_template_part('partials/postcontent', 'embeds'); ?>
    <?php get_template_part('partials/postcontent', 'socials'); ?>
</div>