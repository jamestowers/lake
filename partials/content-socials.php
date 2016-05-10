<?php 
  $socials = get_option( 'dropshop_theme_social_links');
  $chosen_socials =  get_post_meta( $post->ID, '_dropshop_pre_content_socials', true);
  if($chosen_socials != ""){
    echo '<div class="social-icons">';
      echo '<ul>';
      foreach($chosen_socials as $key => $name){
        if($socials[$name]){
          echo '<li><a href="' . $socials[$name] . '" class="icon-' . $name . '"> ' . ucwords($name) . '</a></li>';
        }
      }
      echo '<ul>';
    echo '</div>';
  }
?>