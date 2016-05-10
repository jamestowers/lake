<?php

require ('functions-cleanup.php');
require ('functions-theme-options.php');
require ('functions-contact-options.php');
require ('functions-hero.php');
require ('functions-ajax.php');
require ('functions-fields.php'); // UsesCMB2 "plugin" - see https://wordpress.org/plugins/cmb2/installation/
// Also - wiki here: https://github.com/WebDevStudios/CMB2/wiki


set_post_thumbnail_size( '400', '400', true ); 

// Remove max_srcset_image_width.
function remove_max_srcset_image_width( $max_width ) {
   return false;
}
add_filter( 'max_srcset_image_width', 'remove_max_srcset_image_width' );




/*********************
CUSTOM TAXONOMY
*********************/
/*
UNCOMMENT TO ADD NEW TAXONOMY CALLED CLIENTS

function add_clients_taxonomy() {
  // create a new taxonomy
  register_taxonomy(
    'clients',
    array('post', 'case_study'),
    array(
      'label' => __( 'Clients' ),
      'rewrite' => array( 'slug' => 'client' ),
      'hierarchical' => true
    )
  );
}
add_action( 'init', 'add_clients_taxonomy' );
*/







/*********************
SCRIPTS & ENQUEUEING
*********************/

// Load jQuery
if ( !function_exists( 'dropshop_load_scripts' ) ) {
  function dropshop_load_scripts() {
    if ( !is_admin() ) {
      wp_deregister_script( 'jquery' );
      wp_register_script( 'modernizr', get_stylesheet_directory_uri() . '/library/js/vendor/modernizr.custom.min.js', array(), '2.5.3', false );
      
      wp_register_script( 'jquery', "//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js", '', '' , true);
      wp_register_script( 'picturefill', get_bloginfo('template_directory') . "/library/js/vendor/picturefill.min.js", 'jquery', '', true);
      wp_register_script( 'fastclick', get_bloginfo('template_directory') . "/library/js/vendor/fastclick.js", 'jquery', '', true);
      /*wp_register_script( 'smoothstate', get_bloginfo('template_directory') . "/library/js/vendor/jquery.smoothState.js", array('jquery', 'picturefill'), '', true);
      wp_register_script( 'dropshop', get_bloginfo('template_directory') . "/library/js/dropshop.js", array('jquery', 'picturefill', 'smoothstate'), '', true);
      wp_register_script( 'transitions', get_bloginfo('template_directory') . "/library/js/transitions.js", array('dropshop'), '', true);*/
      wp_register_script( 'dropshop', get_bloginfo('template_directory') . "/library/js/all.js", array('jquery'), '', true);
      wp_register_script( 'scripts', get_bloginfo('template_directory') . "/library/js/scripts.js", array('dropshop'), '', true);

      wp_enqueue_script( 'modernizr' );
      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'fastclick' );
      /*wp_enqueue_script( 'smoothstate' );
      wp_enqueue_script( 'picturefill' );*/
      wp_enqueue_script( 'dropshop' );
      //wp_enqueue_script( 'transitions' );
      wp_enqueue_script( 'scripts');

      wp_localize_script( 'dropshop', 'ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
      ));
    }
  }
  add_action( 'wp_enqueue_scripts', 'dropshop_load_scripts' );
}

function dropshop_scripts_and_styles() {
  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
  if (!is_admin()) {
    wp_register_style( 'dropshop-stylesheet', get_stylesheet_directory_uri() . '/library/css/style.css', array(), '', 'all' );
    wp_register_style( 'dropshop-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );
    
    wp_enqueue_style( 'dropshop-stylesheet' );
    wp_enqueue_style( 'dropshop-ie-only' );

    $wp_styles->add_data( 'dropshop-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet
  }
}




// Add specific CSS class by filter
add_filter( 'body_class', 'extra_class_names' );
function extra_class_names( $classes ) {
  global $post;

  $has_video = get_post_meta( $post->ID, '_dropshop_hero_video_url', true) !== "";
  if(!has_post_thumbnail($post->ID) && !$has_video ){ 
    array_push($classes, ' no-post-thumbnail');
  }

  if(!is_front_page() && $has_video){
    array_push($classes, ' has-video');
  }

  return $classes;
}







/*********************
MENUS & NAVIGATION
*********************/

function dropshop_theme_support() {
  add_theme_support( 'post-thumbnails' );
  add_theme_support('automatic-feed-links');
  add_theme_support( 'menus' );
  
  register_nav_menus(
    array(
      'nav-header' => __( 'Header Nav', 'dropshoptheme' ),   // main nav in header
      'nav-footer' => __( 'Footer Nav', 'dropshoptheme' ) // secondary nav in footer
    )
  );
}

// the main menu
function dropshop_nav_header() {
  // display the wp3 menu if available
  wp_nav_menu(array(
    'container' => false,                           // remove nav container
    'container_class' => 'menu group',           // class of container (should you choose to use it)
    'menu' => __( 'Header Nav', 'dropshoptheme' ),  // nav name
    'menu_class' => 'nav group',         // adding custom nav class
    'theme_location' => 'nav-header',                 // where it's located in the theme
    'before' => '',                                 // before the menu
    'after' => '',                                  // after the menu
    'link_before' => '',                            // before each link
    'link_after' => '',                             // after each link
    'depth' => 3,                                   // limit the depth of the nav
    'fallback_cb' => 'dropshop_main_nav_fallback'      // fallback function
  ));
} /* end dropshop main nav */

// the footer menu (should you choose to use one)
function dropshop_nav_footer() {
  // display the wp3 menu if available
  wp_nav_menu(array(
    'container' => 'false',                              // remove nav container
    'container_class' => 'footer-nav group',   // class of container (should you choose to use it)
    'menu' => __( 'Footer Nav', 'dropshoptheme' ),   // nav name
    'menu_class' => 'nav group',      // adding custom nav class
    'theme_location' => 'nav-footer',             // where it's located in the theme
    'before' => '',                                 // before the menu
    'after' => '',                                  // after the menu
    'link_before' => '',                            // before each link
    'link_after' => '',                             // after each link
    'depth' => 1,                                   // limit the depth of the nav
    'fallback_cb' => 'dropshop_footer_links_fallback'  // fallback function
  ));
} /* end dropshop footer link */

// this is the fallback for header menu
function dropshop_main_nav_fallback() {
  wp_page_menu( array(
    'show_home' => true,
    'menu_class' => 'nav top-nav group',      // adding custom nav class
    'include'     => '',
    'exclude'     => '',
    'echo'        => true,
    'link_before' => '',                            // before each link
    'link_after' => ''                             // after each link
  ) );
}

// this is the fallback for footer menu
function dropshop_footer_links_fallback() {
  /* you can put a default here if you like */
}











/*********************
SIDEBARS / WIDGETS
*********************/

// Sidebars & Widgetizes Areas
function dropshop_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar',
		'name' => __( 'Sidebar', 'dropshoptheme' ),
		'description' => __( 'The first (primary) sidebar.', 'dropshoptheme' ),
		'before_widget' => '<aside id="%1$s" class="widget box %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));

	
	register_sidebar(array(
		'id' => 'footer-sidebar',
		'name' => __( 'Footer Widgets', 'dropshoptheme' ),
		'description' => __( 'Room for 3 widgets just above the footer on every page', 'dropshoptheme' ),
		'before_widget' => '<aside id="%1$s" class="widget box %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
	));
} // don't remove this bracket!











/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using dropshop_related_posts(); )
function dropshop_related_posts() {
  echo '<ul id="dropshop-related-posts">';
  global $post;
  $tags = wp_get_post_tags( $post->ID );
  if($tags) {
    foreach( $tags as $tag ) { 
      $tag_arr .= $tag->slug . ',';
    }
    $args = array(
      'tag' => $tag_arr,
      'numberposts' => 5, /* you can change this to show more */
      'post__not_in' => array($post->ID)
    );
    $related_posts = get_posts( $args );
    if($related_posts) {
      foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
        <li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
      <?php endforeach; }
    else { ?>
      <?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'dropshoptheme' ) . '</li>'; ?>
    <?php }
  }
  wp_reset_query();
  echo '</ul>';
} /* end dropshop related posts function */











/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function dropshop_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  
  echo '<nav class="pagination">';
  
    echo paginate_links( array(
      'base'      => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
      'format'    => '',
      'current'     => max( 1, get_query_var('paged') ),
      'total'     => $wp_query->max_num_pages,
      'prev_text'   => '&larr;',
      'next_text'   => '&rarr;',
      'type'      => 'list',
      'end_size'    => 3,
      'mid_size'    => 3
    ) );
  
  echo '</nav>';
} /* end page navi */









function not_found_message(){ ?>

  <article id="post-not-found" class="hentry group">
    <h1><?php _e( 'Oops, Post Not Found!', 'dropshoptheme' ); ?></h1>
      <section class="entry-content">
        <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'dropshoptheme' ); ?></p>
    </section>
    <footer class="article-footer">
        <p><?php _e( 'This is the error message in the index.php template.', 'dropshoptheme' ); ?></p>
    </footer>
  </article>

<?php }






function logo(){
  return '<svg id="logo-svg" xmlns="http://www.w3.org/2000/svg" width="895" height="221" viewBox="0 0 895 221"><style>.a{fill:#FFF;stroke:#FFF;}</style><path id="R" d="M0.7 1.4l0 2.5h3.1c8.5 0 15.4 6.8 15.5 15.3v134.7c-0.3 8.9-7.5 15.2-15.5 15.2H0.7l0 2.4h53l0-2.4h-3c-9 0-15.5-7.3-15.6-15.3V9.1h30.2c19.9 0.1 29.4 16.4 30.1 37 0.2 7.3-0.7 11.9-1.5 14.6 -6.1 22.3-27.4 24.4-37.9 25.3 -6.1 0.5-11.7 0.1-12.9 0.1v2.2c4.2 0.6 8.6 5.1 14.6 11.7 15.9 17.8 53.6 59.3 65.3 72.6 26 29.6 53.9 50.5 93.2 40.1v-2.4c-30.8 3.9-55.3-21.1-80.3-47.6 -27.4-28.9-42.7-47.1-63.5-69.3 23.5 0.1 41.2-22 41.2-48 0-26.5-18.7-44-47.3-44L0.7 1.4z" class="a"/><path d="M220.1 44c-40.5 0-68.6 26-68.6 65s28.1 65 68.6 65c40.5 0 68.6-26 68.6-65S260.6 44 220.1 44zM220.1 168.6c-32.8 0-54.8-23.9-54.8-59.6 0-35.8 21.9-59.6 54.8-59.6s54.8 23.9 54.8 59.6C274.9 144.7 252.9 168.6 220.1 168.6z" class="a"/><path d="M302.9 46.5l0 1.8h2.2c6.2 0 11.3 4.9 11.5 11.1v99.2c-0.2 6.2-5.2 11.1-11.4 11.1h-2.3l0 1.8h43.5c26.6 0 41.6-13.4 41.6-33.5 0-17.7-11.6-31.6-30.6-34.4 10.1-2.9 19.7-12 19.8-27.1 -0.1-17.9-13.8-29.8-38.8-29.8L302.9 46.5zM328.2 164.9V52.2h9.4c16.9 0 26 9.3 26.1 25.4 -0.1 23-18.6 26.2-29.5 26.1l0 1.7c17.3 0 40.3 4.4 40.3 31.2 0 18.1-10.5 28.4-28.8 28.3H328.2z" class="a"/><path d="M681 198.4c-40.1 15.3-63.8 12.1-162.9-22.3 -11.5-3.9-11.7-10-11.7-16.7V19.1c0.2-8.5 7.1-15.3 15.5-15.3h3.1l0-2.4H471.9l0 2.4h3.1c8.5 0 15.4 6.8 15.5 15.3v134.6c-0.2 8.7-7.2 15.3-15.5 15.3h-3.1l0 2.4c13.9 0.2 28.1 5.3 42.8 11.9 48.6 21.6 102.3 61.9 167.5 17L681 198.4z" class="a"/><path d="M618.8 44h-1.6c0 3.9-6.6 18.4-6.6 18.4l-40.5 96.1c-3.6 7.9-10.2 11.2-15.6 11.2h-1.6v1.8h35.9v-1.8h-1.6c-4.6 0-9.3-4-6.8-11.1l12-30.5h48.1l11.4 30.4c2.6 7.1-2.2 11.2-6.8 11.2h-1.6v1.8h40.2v-1.8h-1.5c-5.5 0-12.1-3.3-15.7-11.2L618.8 44zM594.5 122.7l22.4-57.3 21.5 57.3H594.5z" class="a"/><path d="M717.8 59.3c0.2-6.2 5.2-11.1 11.4-11.1h2.3l0-1.8H692.4l0 1.8h2.3c6.2 0 11.3 5 11.4 11.2v99c-0.1 6.2-5.2 11.2-11.4 11.2h-2.3l0 1.8h39l0-1.8h-2.3c-6.2 0-11.3-5-11.4-11.2V59.3zM735.2 105l42-48.5c4.6-5.2 11.6-8.3 19.2-8.4h0.8v-1.6h-37.7v1.6h1.7c4.9 0 7.3 4.5 4.3 8.4l-42.1 52.8 42.9 52c8.2 9.6 21.9 10.1 26.9 10.1h7.5v-1.8c-7.3-0.2-12-2.4-16.9-7.3L735.2 105z" class="a"/><path d="M892.2 142.6c-5.5 13.6-12.8 23.4-31.7 23.4h-25.6v-53.5h30.3c4.7 0.2 8.4 4 8.4 8.8v2.6l1.7 0V95.7l-1.7 0v2.6c0 4.8-3.9 8.7-8.6 8.8h-30.2v-55.2h36.4c5.6 0.3 10 5 10 10.6v3.2l1.8 0V51.4v-3 -5.2c-3.3 1.4-13.6 3.2-21.3 3.2h-52.1l0 1.8h2.3c5.9 0 11.4 4.5 11.4 10.2v100c-0.1 6.2-5.2 11.2-11.4 11.2h-2.3l0 1.8h74l10.4-28.8L892.2 142.6z" class="a"/></svg>';
}




/*********************
DEBUGGING
*********************/

if(!function_exists('log_it')){
	function log_it( $message ) {
		if( WP_DEBUG === true ){
			if( is_array( $message ) || is_object( $message ) ){
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}

?>