<?php 

// ENABLE ADMIN CSS
function dropshop_load_admin_styles() {
  wp_register_style( 'dropshop_admin_css', get_template_directory_uri() . '/library/css/admin-style.css', false, '1.0.0' );
  wp_enqueue_style( 'dropshop_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'dropshop_load_admin_styles' );

// ENABLE ADMIN SCRIPTS
add_action( 'admin_enqueue_scripts', 'dropshop_enqueue_color_picker' );
function dropshop_enqueue_color_picker( ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'dropshop_admin_scripts', get_bloginfo('template_directory').'/library/js/admin/admin-scripts.js', array( 'wp-color-picker' ), false, true );
}

// ENABLE OPTIONS MENU
add_action('admin_menu', 'dropshop_add_appearance_menu');
function dropshop_add_appearance_menu(){
  add_theme_page( 
    'Theme Options', 
    'Theme Options', 
    'edit_posts', 
    'dropshop_theme_options', 
    create_function( null, 'dropshop_render_theme_options_page( "theme_colors" );' ) 
  );
}

function dropshop_colour_chooser_is_enabled(){
  $options = get_option('dropshop_theme_options');
  return isset( $options['enable_colour_chooser']) && $options['enable_colour_chooser'] == 1;
}


// RENDER OPTIONS PAGE
function dropshop_render_theme_options_page( $active_tab = '' ){
?>
  <!-- Create a header in the default WordPress 'wrap' container -->
  <div class="wrap">
  
    <h2><?php _e( 'Theme Options', 'dropshop' ); ?></h2>
    <?php settings_errors(); ?>

    <?php if( isset( $_GET[ 'tab' ] ) ) {
      $active_tab = $_GET[ 'tab' ];
    } else if( $active_tab == 'theme_colors' ) {
      $active_tab = 'theme_colors';
    } else if( $active_tab == 'theme_options' ) {
      $active_tab = 'theme_options';
    } else {
      $active_tab = 'contact_info';
    } // end if/else ?>

    <h2 class="nav-tab-wrapper">
      <a href="?page=dropshop_theme_options&tab=theme_colors" class="nav-tab <?php echo $active_tab == 'theme_colors' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Theme Colours', 'dropshop' ); ?></a>
      <a href="?page=dropshop_theme_options&tab=theme_options" class="nav-tab <?php echo $active_tab == 'theme_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Options', 'dropshop' ); ?></a>
    </h2>
    
    <form method="post" action="options.php">
      <?php

        if( $active_tab == 'theme_colors' ) {

          settings_fields( 'dropshop_theme_colors' );
          do_settings_sections( 'dropshop_theme_colors' );

        } else {
        
          settings_fields( 'dropshop_theme_options' );
          do_settings_sections( 'dropshop_theme_options' );
          
        } // end if/else
        
        submit_button();
      
      ?>
    </form>

  </div><!-- /.wrap -->
<?php
} // end dropshop_render_theme_options_page


// SET UP OPTIONS SETTINGS 
function dropshop_add_theme_options_settings(){

  // If the theme options don't exist, create them.
  if( false == get_option( 'dropshop_theme_colors' ) ) {  
    add_option( 'dropshop_theme_colors' );
  } // end if



  /* ------------------------------------------------------------------------ *
   * THEME COLOURS
   * ------------------------------------------------------------------------ */ 

  add_settings_section(
    'dropshop_theme_colors',  
    __( 'Site colours', 'dropshop' ),
    'dropshop_theme_colors_callback', 
    'dropshop_theme_colors'
  );

  // We want four colours so do a for loop
  for ($i = 1; $i <= 4; $i++) {
    add_settings_field( 
      'dropshop_theme_color_'.$i, 
      __( 'Colour ', 'dropshop' ).$i,
      'dropshop_theme_colors_fields_callback',
      'dropshop_theme_colors', 
      'dropshop_theme_colors',
      array(
        __( 'Choose a color to add to the page background colour selector', 'dropshop' ),
        $i // Pass id to callback function
      )
    );
  }

  register_setting(
    'dropshop_theme_colors',
    'dropshop_theme_colors'
  );


  /* ------------------------------------------------------------------------ *
   * THEME OPTIONS
   * ------------------------------------------------------------------------ */

  add_settings_section(
    'dropshop_theme_options',  
    __( 'Theme Options', 'dropshop' ),
    'dropshop_theme_options_callback', 
    'dropshop_theme_options'
  );

  add_settings_field(
    'Enable page background colour chooser',
    __('Enable page background colour chooser', 'dropshop'),
    'dropshop_theme_options_enable_page_colour_chooser_callback',
    'dropshop_theme_options', 
    'dropshop_theme_options',
    array(
      __( 'Allow choosing of page background colours', 'dropshop' )
    )
  );

  register_setting(
    'dropshop_theme_options',
    'dropshop_theme_options'
  );

}
add_action( 'admin_init', 'dropshop_add_theme_options_settings' );




/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 
function dropshop_theme_colors_callback(){
  echo '<p>' . __( 'Choose colours to add to the page background colour chooser', 'dropshop' ) . '</p>';
}

function dropshop_theme_options_callback(){
  echo '<p>' . __( 'Enable and disable various theme options', 'dropshop' ) . '</p>';
}




/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

function dropshop_theme_colors_fields_callback($args) {
  
  // First, we read the options collection
  $options = get_option('dropshop_theme_colors');
  $value = isset( $options['theme_color_'.$args[1]] ) ? $options['theme_color_'.$args[1]] : "";
  // Next, we update the name attribute to access this element's ID in the context of the display options array
  // We also access the email_address element of the options collection in the call to the checked() helper function
  $html = '<input class="wp-color-picker-field type="text" id="theme_color_' . $args[1] . '" name="dropshop_theme_colors[theme_color_' . $args[1] . ']" value="' . $value . '" />'; 
  
  // Here, we'll take the first argument of the array and add it to a label next to the text
  $html .= '<br><span class="description">'  . $args[0] . '</span>'; 
  
  echo $html;
  
} // end dropshop_contact_email_address_callback

function dropshop_theme_options_enable_page_colour_chooser_callback($args) {
  
  // First, we read the options collection
  $options = get_option('dropshop_theme_options');
  $checked = ( isset( $options['enable_colour_chooser']) && $options['enable_colour_chooser'] == 1 ) ? "checked" : "";
  $html = '<input class="" type="checkbox" id="" name="dropshop_theme_options[enable_colour_chooser]" value="1" ' . $checked . ' />'; 
  
  // Here, we'll take the first argument of the array and add it to a label next to the text
  $html .= '<br><span class="description">'  . $args[0] . '</span>'; 
  
  echo $html;
  
} // end dropshop_contact_email_address_callback






/* ------------------------------------------------------------------------ *
 * WRITE PANEL META BOXES
 * ------------------------------------------------------------------------ */ 

// Render meta box
function dropshop_background_colour_select_meta_box(){
  $current_value = "";
  if( isset( $_GET['post'] ) || isset( $_POST['post_ID'] ) ){
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    $current_value = get_post_meta( $post_id, "dropshop_page_background_color", true );
  }

  $colors = get_option('dropshop_theme_colors');
  echo '<ul>';
  foreach ($colors as $key => $color) {
    if( $color !== "" ){
      $selected = $current_value == $key ? "selected" : "";
      echo '<li><a href="#" class="' . $selected . '" id="' . $key . '" style="background: ' . $color . '" data-color="' . $key .'"></a></li>';
    }
  }
  echo '</ul>';
  echo '<input type="hidden" name="dropshop_page_background_color" value="' . $current_value . '" />';
  echo '<br style="clear: both" />';
}

// Add colour chooser box to admin write panel
if( dropshop_colour_chooser_is_enabled() ){
  add_action('add_meta_boxes', 'dropshop_add_color_chooser_meta_box');
  function dropshop_add_color_chooser_meta_box(){
    add_meta_box('dropshop-background-colour', 'Page background colour', 'dropshop_background_colour_select_meta_box', 'page', 'side', 'high');
  }
}


// Save meta data
add_action( 'save_post', 'dropshop_save_page_color_meta', 1, 2 );
function dropshop_save_page_color_meta( $post_id ) {
  global $post;
  $post = get_post( $post_id );
  if( $post->post_type == 'revision' )
    return;
  if( !current_user_can( 'edit_post', $post_id ))
    return;
  if( isset( $_POST['dropshop_page_background_color'] ) ){
    $curdata = $_POST['dropshop_page_background_color'];
    $olddata = get_post_meta( $post_id, "dropshop_page_background_color", true );
    if( $olddata == "" && $curdata != "" )
      add_post_meta( $post_id, "dropshop_page_background_color", $curdata );
    elseif( $curdata != $olddata )
      update_post_meta( $post_id, "dropshop_page_background_color", $curdata);
  }
}


// Add class to post or page post_class('')
add_filter( 'post_class', 'dropshop_add_color_class' );
function dropshop_add_color_class($classes){
  global $post;
  $color = get_post_meta( $post->ID, "dropshop_page_background_color", true );
  if( $color != "" ){
    $classes[] = 'bg-'.$color;
  }
  return $classes;
}


add_action( 'wp_head', 'dropshop_add_color_styles');
function dropshop_add_color_styles(){
  echo '<style type="text/css">';
  $colors = get_option('dropshop_theme_colors');
  if($colors != '')
    foreach($colors as $name => $hex){
      echo '.bg-' . $name . '{ background-color: ' . $hex . ';}' . "\r\n";
    }
  echo '</style>';
  //wp_reset_query();
}

?>