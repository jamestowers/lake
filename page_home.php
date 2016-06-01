<?php /* Template Name: Homepage */ get_header(); ?>
  <?php dropshop_social_icons();?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <section>
      <div id="top-banner">
        <?php $banner_text = '<img src="' . get_bloginfo('template_directory') . '/library/images/logo.svg" />'; dropshop_hero_image( logo()  );?>
        <nav role="navigation" id="nav-header">
          <?php dropshop_nav_header(); ?>
        </nav>


        <?php get_template_part('partials/scroll-arrow');?>

      </div>
      
    </section>
	<?php endwhile; else :
    get_template_part( 'content', 'none' );
  endif; wp_reset_postdata(); ?>

  <?php
  // Get pages query
  
  /*$pages = array( 10, 12, 14, 16, 8, 56, 18 ); // Local
  $newsPageId = 8; // Local*/
  
  $pages = array( 20, 23, 24, 56, 19, 25, 26 ); // live
  $newsPageId = 19; // Live

  $sections = new WP_Query( array( 
    'post_type' => 'page', 
    'post__in' => $pages,
    'orderby' => 'menu_order title',
    'order'   => 'ASC'
  ) );
  if ( $sections->have_posts() ) :     
      while ($sections->have_posts()) : $sections->the_post();?>
        <section <?php post_class('page', $post->ID);?> data-page-id="<?php echo $post->ID;?>">
      
          <?php
           dropshop_hero_image( $banner_text = "" );
          switch ($post->ID) {

            case $newsPageId: //NEWS
              echo '<div class="inner pad group full-height">';
                echo '<h1 class="page-title">News</h1>';
                $news_posts = new WP_Query( array( 
                  'post_type' => 'post'
                ) );
                if ( $news_posts->have_posts() ) :     
                    while ($news_posts->have_posts()) : $news_posts->the_post();
                      get_template_part( 'partials/news-post' );
                    endwhile;
                else:
                  echo '<h2>No news yet...</h2>';
                endif;
              echo '</div>';
              break;

            default: // ALL OTHER PAGES
              if( get_post_meta( $post->ID, '_dropshop_page_layout_choice', true) == '1col' ){
                  get_template_part( '/partials/page-content', '1col' );
              } else{
                  get_template_part('partials/page-content');
              }
            }


        echo '</section>';
      endwhile;
  endif;
  ?>


<?php get_footer(); ?>
