<?php

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'hero-image-xlarge-desktop', 2200, 1200, true );
add_image_size( 'hero-image-large-desktop', 1440, 900, true );
add_image_size( 'hero-image-desktop', 1100, 700, true );
add_image_size( 'hero-image-tablet-portrait', 750, 1000, true );
add_image_size( 'hero-image-mobile-portrait', 350, 500, true );


/*
The function below adds the ability to use the dropdown menu to select 
the new images sizes you have just created from within the media manager 
when you add media to your content blocks. If you add more image sizes, 
duplicate one of the lines in the array and name it according to your 
new image size.
*/
add_filter( 'image_size_names_choose', 'dropshop_custom_image_sizes' );

function dropshop_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
      'hero-image-xlarge-desktop' => __('Very large desktop'),
      'hero-image-large-desktop' => __('Large desktop'),
      'hero-image-desktop' => __('Desktop'),
      'hero-image-tablet-portrait' => __('Tablet portrait'),
      'hero-image-mobile-portrait' => __('Mobile portrait')
    ) );
}









/*********************
HERO IMAGE
*********************/
function dropshop_get_img_alt( $image ) {
    $img_alt = trim( strip_tags( get_post_meta( $image, '_wp_attachment_image_alt', true ) ) );
    return $img_alt;
}

function dropshop_get_picture_srcs( $image, $mappings ) {
    $arr = array();
    foreach ( $mappings as $size => $type ) {
        $image_src = wp_get_attachment_image_src( $image, $type );
        $arr[] = '<source srcset="'. $image_src[0] . '" media="(min-width: '. $size .'px)">';
    }
    return implode( array_reverse ( $arr ) );
}

function dropshop_responsive_shortcode( $atts ) {
    extract( shortcode_atts( array(
        'imageid'    => 1,
        // You can add more sizes for your shortcodes here
        'size1' => 0,
        'size2' => 400,
        'size3' => 800,
        'size4' => 1200,
        'size5' => 1600,
    ), $atts ) );

    $mappings = array(
        $size1 => 'hero-image-mobile-portrait',
        $size2 => 'hero-image-tablet-portrait',
        $size3 => 'hero-image-desktop',
        $size4 => 'hero-image-large-desktop',
        $size5 => 'hero-image-xlarge-desktop',
    );

   return
        '<picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->'
            . dropshop_get_picture_srcs( $imageid, $mappings ) .
            '<!--[if IE 9]></video><![endif]-->
            <img srcset="' . wp_get_attachment_image_src( $imageid[0] ) . '" alt="' . dropshop_get_img_alt( $imageid ) . '">
            <noscript>' . wp_get_attachment_image( $imageid, $mappings[0] ) . ' </noscript>
        </picture>';
}

add_shortcode( 'responsive', 'dropshop_responsive_shortcode' );
/* in post use:
  $image_id = get_post_thumbnail_id( $post->ID );
  echo do_shortcode('[responsive imageid="'.$image_id.'"]');
*/

function dropshop_using_custom_featured_image_metabox_plugin() {
  return method_exists('Custom_Featured_Image_Metabox', 'get_instance');
}

function dropshop_hero_image( $headline_text = "" ){
  global $post;
  // If we are using the custom-featured-image-metabox plugin we can check to see if the hero image has been enabled on this post/page
  if( dropshop_using_custom_featured_image_metabox_plugin() && get_post_meta($post->ID, 'enable_cover_image', true) !== '1'){
    return;
  }
  $vid_url = get_post_meta( $post->ID, '_dropshop_hero_video_url', true);
  if ( has_post_thumbnail() || $vid_url != '') { ?>
    
    <div class="hero-container">

    <?php 
      if($vid_url != ""){
        echo '<div class="video-wrapper">';
        switch( get_post_meta( $post->ID, '_dropshop_hero_video_type', true) ) {
          case 'vimeo':
            echo '<iframe id="vimeo-player" data-video-id="' . dropshop_get_vimeoid_from_url($vid_url) . '" src="" width="1000" height="562" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            break;

          case 'youtube':
            echo '<div id="youtube-player" data-video-id="' . dropshop_get_youtubeid_from_url($vid_url) . '" src="" width="1000" height="562" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></div>';
            break;

          default: 
            echo '<video poster="" loop muted>';
              // MP4 must be first for iPad!
              echo '<source src="' . $vid_url . '" type="video/mp4">';
              //echo '<source src="' . $vid_url . '.webm" type="video/webm">';
            echo '</video>';
            break;
        }
        echo '</div>';

        echo '<ul class="video-controls">';
          echo '<li><a href="" class="play-pause icon-pause" title="Unmute"></a></li>';
          echo '<li><a href="" class="mute icon-volume" title="Unmute"></a></li>';
        echo '</ul>';
      }else{
        if ( has_post_thumbnail() ) {
          $image_id = get_post_thumbnail_id( $post->ID );
          echo do_shortcode('[responsive imageid="'.$image_id.'"]');
        }
      }
    ?>

    <?php if( $headline_text ){
      echo '<div class="hero-banner">' . $headline_text . '</div>';
    }?>

    </div>

  <?php }
}

function dropshop_get_youtubeid_from_url($url){
  preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

  return isset($matches[1]) ? $matches[1] : false;
}

function dropshop_get_vimeoid_from_url( $url ) {
  $regex = '~
    # Match Vimeo link and embed code
    (?:<iframe [^>]*src=")?         # If iframe match up to first quote of src
    (?:                             # Group vimeo url
        https?:\/\/             # Either http or https
        (?:[\w]+\.)*            # Optional subdomains
        vimeo\.com              # Match vimeo.com
        (?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
        \/                      # Slash before Id
        ([0-9]+)                # $1: VIDEO_ID is numeric
        [^\s]*                  # Not a space
    )                               # End group
    "?                              # Match end quote if part of src
    (?:[^>]*></iframe>)?            # Match the end of the iframe
    (?:<p>.*</p>)?                  # Match any title information stuff
    ~ix';
  
  preg_match( $regex, $url, $matches );
  
  return $matches[1];
}
?>