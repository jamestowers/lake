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
    'container_class' => '',           // class of container (should you choose to use it)
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


function dropshop_custom_nav_attributes ( $atts, $item, $args ) {
    log_it($item);
    $atts['data-page-id'] = $item->object_id;
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'dropshop_custom_nav_attributes', 10, 3 );





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
  return '<svg id="logo-svg" xmlns="http://www.w3.org/2000/svg" width="895px" height="136.479px" viewBox="0 0 895 136.479" enable-background="new 0 0 895 136.479" xml:space="preserve">
<path id="E" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M885.276,112.852l-3.745,3.17
  c-0.769,0.641-1.472,1.088-2.113,1.344c-0.641,0.258-1.377,0.385-2.209,0.385h-21.674c-8.9,0-15.896-0.688-20.985-2.064
  c-5.091-1.377-9.718-3.697-13.879-6.964c-5.891-4.609-10.308-10.291-13.254-17.047c-2.633-6.04-4.081-12.943-4.359-20.698h47.913
  c1.134,0,2.013,0.294,2.637,0.884c0.498,0.468,0.725,1.158,0.725,1.84v0.253l-0.193,1.921h1.153l12.582-23.626h-62.803
  c0.661-2.398,1.463-4.725,2.441-6.963c3.01-6.883,7.46-12.822,13.35-17.816c3.715-3.074,8.261-5.347,13.64-6.819
  s12.133-2.209,20.264-2.209h13.414c0.963,0,1.762,0.256,2.402,0.769s0.96,1.185,0.96,2.017v0.384l-0.191,1.729h0.96L885.085,0
  h-30.319c-9.539,0-16.742,0.448-21.608,1.345c-4.866,0.896-9.413,2.401-13.639,4.514c-4.802,2.434-9.221,5.347-13.254,8.74
  c-4.033,3.393-7.619,7.235-10.758,11.525c-4.481,6.147-7.86,12.901-10.132,20.266c-2.272,7.363-3.41,15.239-3.41,23.627
  c0,10.821,1.969,20.633,5.907,29.436c3.938,8.805,9.748,16.344,17.434,22.62c6.273,5.186,12.979,8.885,20.121,11.092
  c7.138,2.209,17.175,3.314,30.108,3.314h18.504l12.871-23.627H885.276z"/>
<path id="K" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M759.372,75.778
  c-11.238-13.637-26.268-21.193-45.094-22.666L763.069,0h-32.271v1.345c0.832,0.447,1.439,0.88,1.825,1.296
  c0.384,0.417,0.575,0.912,0.575,1.489c0,0.384-0.094,0.752-0.287,1.104c-0.192,0.352-0.576,0.88-1.153,1.585l-38.981,44.346V7.011
  c0-0.64,0.11-1.233,0.335-1.777s0.593-1.04,1.104-1.488l2.305-2.401V0h-27.468v1.345l2.305,2.401
  c0.513,0.512,0.864,1.009,1.056,1.488c0.192,0.48,0.288,1.073,0.288,1.777V129.18c0,0.768-0.111,1.439-0.334,2.016
  c-0.226,0.576-0.561,1.09-1.01,1.539l-2.305,2.398v1.346h27.468v-1.346l-2.305-2.398c-0.512-0.449-0.88-0.963-1.104-1.539
  s-0.335-1.248-0.335-2.016v-58.99c1.246-0.246,2.505-0.433,3.781-0.557c1.312-0.129,2.643-0.192,3.986-0.192
  c16.84,0,30.317,5.059,40.434,15.175c10.118,10.117,15.176,23.53,15.176,40.243v4.322c0,0.768-0.112,1.439-0.337,2.016
  c-0.224,0.576-0.56,1.09-1.007,1.539l-2.402,2.398v1.346h23.819v-8.066C776.227,106.959,770.608,89.417,759.372,75.778"/>
<path id="A" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M545.954,75.298v61.18h23.817v-1.346l-2.398-2.398
  c-0.45-0.449-0.785-0.963-1.011-1.539c-0.223-0.576-0.335-54.361-0.335-55.129v-4.321c0-0.689,0.022-1.366,0.039-2.045h63.339
  v59.431c0,0.773-0.113,1.451-0.336,2.029c-0.225,0.58-0.593,1.096-1.104,1.547l-2.305,2.418v1.354h27.468v-1.354l-2.305-2.418
  c-0.449-0.451-0.784-0.967-1.009-1.547c-0.226-0.578-0.336-1.256-0.336-2.029V7.059c0-0.709,0.096-1.306,0.288-1.789
  c0.191-0.484,0.545-0.983,1.057-1.499l2.305-2.417V0h-23.616h-21.61c-18.823,1.472-33.854,9.028-45.092,22.666
  C551.573,36.305,545.954,53.849,545.954,75.298 M568.697,52.507c2.462-8.114,6.622-15.121,12.505-21.004
  c10.116-10.117,23.596-15.175,40.434-15.175c1.346,0,2.675,0.064,3.986,0.191c1.275,0.125,2.537,0.312,3.783,0.559v35.429H568.697z"
  />
<path id="L" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M448.188,129.18V7.012c0-0.704-0.096-1.297-0.287-1.777
  c-0.192-0.48-0.545-0.977-1.057-1.488l-2.306-2.401V0h27.469v1.345l-2.305,2.401c-0.512,0.448-0.882,0.944-1.104,1.488
  c-0.225,0.544-0.336,1.137-0.336,1.777V117.75h52.738c0.706,0,1.361-0.113,1.97-0.336c0.608-0.225,1.329-0.688,2.161-1.393
  l3.649-3.17l1.249,0.768v22.859h-85.491v-1.346l2.306-2.398c0.447-0.449,0.785-0.963,1.007-1.539
  C448.078,130.619,448.188,129.947,448.188,129.18"/>
<path id="B" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M372.83,89.48c-0.369-6.62-1.604-12.405-3.725-17.338
  c-0.046-0.108-0.101-0.209-0.148-0.316c-1.288-2.921-2.873-5.557-4.785-7.877c-3.526-4.277-8.102-7.474-13.685-9.637l-9.56-2.352
  l20.36-15.156l-0.02-0.011c0.234-0.108,0.799-0.997-0.157-7.5c-1.167-7.933-5.749-16.006-12.246-21.321
  C342.366,2.658,334.507,0,325.286,0H265.28v1.345l2.305,2.4c0.512,0.513,0.864,1.009,1.056,1.489
  c0.192,0.48,0.288,1.073,0.288,1.777V129.18c0,0.768-0.112,1.439-0.336,2.016c-0.226,0.578-0.562,1.09-1.008,1.539l-2.305,2.398
  v1.041h27.468h7.841h28.479c13.751,0,24.359-3.426,31.851-10.258c0.252-0.23,0.51-0.453,0.755-0.691
  c2.682-2.602,4.877-5.617,6.603-9.033c0.005-0.01,0.008-0.02,0.012-0.027c3.107-6.161,4.671-13.636,4.671-22.442
  C372.96,92.266,372.907,90.862,372.83,89.48 M356.86,101.97c-0.875,4.075-2.545,7.354-5.041,9.809
  c-3.842,3.777-9.605,5.666-17.288,5.666h-45.529V18.44h31.386c5.823,0,10.372,1.105,13.637,3.313
  c3.266,2.209,4.899,5.234,4.899,9.076c0,1.921-0.515,3.666-1.537,5.235c-1.024,1.569-2.88,3.441-5.57,5.619l-36.401,28.524
  c2.39-0.102,30.637-1.456,33.064-1.456c9.796,0,17.096,2.128,21.897,6.387c3.533,3.133,5.759,7.494,6.693,13.07
  c0.011,0.068,0.028,0.132,0.039,0.2c0.313,1.945,0.472,4.035,0.472,6.273C357.582,97.352,357.332,99.771,356.86,101.97"/>
<path id="O" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M253.951,46.27c-2.272-7.363-5.65-14.118-10.132-20.265
  c-3.138-4.29-6.724-8.131-10.757-11.525c-0.502-0.423-1.029-0.816-1.543-1.224c-3.615-2.866-7.507-5.386-11.711-7.517
  c-4.226-2.113-8.772-3.617-13.638-4.514c-3.424-0.631-8.008-1.039-13.746-1.226c-5.737,0.187-10.322,0.595-13.746,1.226
  c-4.866,0.896-9.412,2.4-13.638,4.514c-4.204,2.13-8.096,4.65-11.711,7.517c-0.514,0.408-1.041,0.8-1.543,1.224
  c-4.033,3.394-7.62,7.235-10.757,11.525c-4.482,6.147-7.86,12.902-10.133,20.265c-2.273,7.364-3.41,15.239-3.41,23.627
  c0,10.822,1.969,20.634,5.907,29.437c3.391,7.58,8.177,14.217,14.343,19.922c0.997,0.92,2.021,1.824,3.089,2.697
  c6.275,5.186,12.981,8.885,20.121,11.092c5.462,1.691,12.625,2.732,21.478,3.131c8.854-0.398,16.016-1.439,21.478-3.131
  c7.139-2.207,13.846-5.906,20.121-11.092c1.069-0.873,2.092-1.777,3.088-2.697c6.167-5.705,10.954-12.341,14.344-19.922
  c3.938-8.803,5.906-18.614,5.906-29.437C257.36,61.509,256.225,53.634,253.951,46.27 M180.07,115.566
  c-5.091-1.377-9.716-3.697-13.878-6.964c-5.891-4.608-10.309-10.292-13.254-17.048c-2.945-6.754-4.418-14.583-4.418-23.481
  c0-8.388,1.505-16.024,4.514-22.907c3.01-6.883,7.459-12.822,13.35-17.815c3.714-3.074,8.261-5.348,13.639-6.82
  c3.535-0.967,7.677-1.61,12.401-1.943c4.724,0.333,8.867,0.976,12.401,1.943c5.378,1.472,9.924,3.746,13.639,6.82
  c5.89,4.994,10.34,10.932,13.35,17.815c3.009,6.883,4.514,14.519,4.514,22.907c0,8.898-1.473,16.728-4.418,23.481
  c-2.946,6.756-7.364,12.439-13.254,17.048c-4.161,3.267-8.788,5.587-13.878,6.964c-3.309,0.895-7.435,1.492-12.353,1.807
  C187.506,117.059,183.379,116.461,180.07,115.566"/>
<path id="R" fill="#FFFFFF" stroke="#FFFFFF" stroke-miterlimit="10" d="M100.921,78.372c-8.089-11.014-18.549-18.324-31.367-21.955
  l26.345-19.612c0,0,1.099,0.423-0.069-7.511c-1.167-7.934-5.749-16.007-12.245-21.321C77.087,2.658,69.227,0,60.006,0H0v1.345
  l2.305,2.401C2.817,4.258,3.17,4.755,3.362,5.234C3.554,5.715,3.65,6.308,3.65,7.012V129.18c0,0.768-0.113,1.439-0.336,2.016
  c-0.227,0.576-0.563,1.09-1.009,1.539L0,135.133v1.346h27.469v-1.346l-2.305-2.398c-0.516-0.449-0.881-0.963-1.105-1.539
  c-0.227-0.576-0.336-1.248-0.336-2.016V18.44h31.386c5.824,0,10.373,1.105,13.638,3.313c3.265,2.209,4.897,5.234,4.897,9.077
  c0,1.921-0.515,3.666-1.537,5.234c-1.024,1.569-2.881,3.442-5.571,5.619l-36.4,28.525c2.497-0.321,4.834-0.561,7.011-0.721
  c2.174-0.159,4.226-0.239,6.147-0.239c15.111,0,27.612,5.25,37.505,15.75c9.893,10.501,14.839,23.787,14.839,39.859v4.322
  c0,0.768-0.113,1.439-0.336,2.016c-0.227,0.576-0.562,1.09-1.009,1.539l-2.593,2.398v1.346h23.818v-8.066
  C115.52,108.306,110.654,91.625,100.921,78.372"/>
</svg>';
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