<?php 
    $embeds_group = get_post_meta( $post->ID, '_dropshop_embeds_group', true) ;
    if($embeds_group !== ''){
      echo '<div class="embeds group" data-post-id="' . $post->ID . '" data-status="pending">';
        get_template_part( '/partials/loading' );
      echo '</div>';
    }
  ?>