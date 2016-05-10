<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php dropshop_hero_image( $banner_text = "" );?>

    <section <?php post_class( ); ?> >

        <?php if( get_post_meta( $post->ID, '_dropshop_page_layout_choice', true) == '1col' ){
            get_template_part( '/partials/page-content', '1col' );
        } else{
            get_template_part('partials/page-content');
        }?>

    </section>

	<?php endwhile; ?>

  <?php else :
    // If no content, include the "No posts found" template: content-none.php
    get_template_part( 'content', 'none' );
    endif; ?>


<?php get_footer(); ?>
