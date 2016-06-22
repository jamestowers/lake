<footer class="group article-footer small">
  <?php dropshop_share_buttons(get_the_permalink());?>
  <?php the_tags( '<p class="tags pull-right"><span class="tags-title">' . __( 'Tags:', 'dropshoptheme' ) . '</span> ', ', ', '</p>' ); ?>
</footer>

<?php if(is_single()){?>
  <div class="group comments">
    <div class="fb-comments" data-href="<?php the_permalink();?>" data-numposts="5"></div>
  </div>
<?php }?>