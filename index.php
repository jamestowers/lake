<?php get_header(); ?>

	<div class="inner pad group full-height">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<?php get_template_part( 'partials/news-post' );?>

		<?php endwhile; ?>

				<?php if ( function_exists( 'dropshop_page_navi' ) ) { ?>
						<?php dropshop_page_navi(); ?>
				<?php } else { ?>
						<nav class="wp-prev-next">
								<ul class="group">
									<li class="prev-link"><?php next_posts_link( __( '&laquo; Older Entries', 'dropshoptheme' )) ?></li>
									<li class="next-link"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'dropshoptheme' )) ?></li>
								</ul>
						</nav>
				<?php } ?>

			<?php else :
				// If no content, include the "No posts found" template: content-none.php
        get_template_part( 'content', 'none' );
			endif; ?>

	<?php //get_sidebar();?>

	</div> <!-- close inner -->

<?php get_footer(); ?>
