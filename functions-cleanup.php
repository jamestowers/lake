<?php
  
  add_action( 'after_setup_theme', 'dropshop_cleanup', 16 );

  function dropshop_cleanup() {

    add_filter('show_admin_bar', '__return_false');

    // LOSE HEAD JUNK
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    remove_action( 'wp_head', 'wp_generator' );

    // cleaning up random code around images
    add_filter( 'the_content', 'dropshop_filter_ptags_on_images' );
    add_filter( 'excerpt_more', 'dropshop_excerpt_more' );
    add_filter( 'gallery_style', 'dropshop_gallery_style' );



    add_filter( 'the_generator', 'dropshop_rss_version' );
    add_filter( 'style_loader_src', 'dropshop_remove_wp_ver_css_js', 9999 );


    add_filter( 'wp_head', 'dropshop_remove_wp_widget_recent_comments_style', 1 );
    add_action( 'wp_head', 'dropshop_remove_recent_comments_style', 1 );

    add_action( 'wp_enqueue_scripts', 'dropshop_scripts_and_styles', 999 );

    dropshop_theme_support();

    // adding sidebars to Wordpress (these are created in functions.php)
    add_action( 'widgets_init', 'dropshop_register_sidebars' );

  }


  // Prevent blank page title on homepage and add blog title on others
  add_filter( 'wp_title', 'dropshop_hack_wp_title_for_home' );
  function dropshop_hack_wp_title_for_home( $title ){
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
      return __( 'Home', 'dropshop' ) . ' | ' . get_bloginfo( 'description' );
    }else{
      return $title . ' | ' . get_bloginfo( 'name' );
    }
    return $title;
  }

  // remove WP version from RSS
  function dropshop_rss_version() { return ''; }

  // remove WP version from scripts
  function dropshop_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
      $src = remove_query_arg( 'ver', $src );
    return $src;
  }
  // remove injected CSS for recent comments widget
  function dropshop_remove_wp_widget_recent_comments_style() {
    if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
      remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
    }
  }
  // remove injected CSS from recent comments widget
  function dropshop_remove_recent_comments_style() {
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
      remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
    }
  }
  // remove injected CSS from gallery
  function dropshop_gallery_style($css) {
    return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
  }
  // remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
  function dropshop_filter_ptags_on_images($content){
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
  }
  // This removes the annoying […] to a Read More link
  function dropshop_excerpt_more($more) {
    global $post;
    // edit here if you like
    return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __( 'Read', 'dropshoptheme' ) . get_the_title($post->ID).'">'. __( 'Read more &raquo;', 'dropshoptheme' ) .'</a>';
  }


  add_action( 'admin_menu', 'my_remove_menu_pages' );
  function my_remove_menu_pages() {
    global $user_ID;

    if ( !current_user_can( 'install_themes' ) ) {
      remove_menu_page('edit.php'); // Posts
      remove_menu_page('plugins.php'); // Plugins
      remove_menu_page('themes.php'); // Appearance
      remove_menu_page('tools.php'); // Tools
      remove_menu_page('mf_dispatcher'); // Tools
      remove_menu_page('wpcf7'); // Tools
    }
    remove_menu_page('upload.php'); // Media
    remove_menu_page('link-manager.php'); // Links
    remove_menu_page('edit-comments.php'); // Comments
  }

  add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
  function remove_dashboard_widgets() {
    global $wp_meta_boxes;

    if ( !current_user_can( 'install_themes' ) ) {
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
      //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
      //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
      unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
      unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    }

  }




?>