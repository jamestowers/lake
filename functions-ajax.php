<?php 
  
  add_action( 'wp_ajax_get_post_embeds', 'get_post_embeds' );
  add_action( 'wp_ajax_nopriv_get_post_embeds', 'get_post_embeds' );

  function get_post_embeds(){
    if(!empty($_POST['post_id'])){
      $embeds_group = get_post_meta( $_POST['post_id'], '_dropshop_embeds_group') ;
      $result = array(
        'embeds' => array()
      );
      foreach($embeds_group[0] as $embed){
        $code = $embed['_dropshop_embeds_embed_code'];
        if(!( isset( $code ) ))
          continue;
        if (filter_var($code, FILTER_VALIDATE_URL)) { 
          array_push($result['embeds'], wp_oembed_get( $code ) );
        }else{
          array_push($result['embeds'], $code);
        }
      }

      echo json_encode($result);
    };
    die();
  }