<?php get_header(); ?>

	<div class="inner">

		<div id="main" class="col8 pad group" role="main">

			<?php if (is_category()) { ?>
				<h1 class="page-title">
					<span><?php _e( 'Posts Categorized:', 'dropshoptheme' ); ?></span> <?php single_cat_title(); ?>
				</h1>

			<?php } elseif (is_tag()) { ?>
				<h1 class="page-title">
					<span><?php _e( 'Posts Tagged:', 'dropshoptheme' ); ?></span> <?php single_tag_title(); ?>
				</h1>

			<?php } elseif (is_author()) {
				global $post;
				$author_id = $post->post_author;
			?>
				<h1 class="page-title">

					<span><?php _e( 'Posts By:', 'dropshoptheme' ); ?></span> <?php the_author_meta('display_name', $author_id); ?>

				</h1>
			<?php } elseif (is_day()) { ?>
				<h1 class="page-title">
					<span><?php _e( 'Daily Archives:', 'dropshoptheme' ); ?></span> <?php the_time('l, F j, Y'); ?>
				</h1>

			<?php } elseif (is_month()) { ?>
					<h1 class="page-title">
						<span><?php _e( 'Monthly Archives:', 'dropshoptheme' ); ?></span> <?php the_time('F Y'); ?>
					</h1>

			<?php } elseif (is_year()) { ?>
					<h1 class="page-title">
						<span><?php _e( 'Yearly Archives:', 'dropshoptheme' ); ?></span> <?php the_time('Y'); ?>
					</h1>
			<?php } ?>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">

				<header class="article-header">

					<h3 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					<p class="byline vcard"><?php
						printf(__( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> <span class="amp">&</span> filed under %3$s.', 'dropshoptheme' ), get_the_time('Y-m-j'), get_the_time(__( 'F jS, Y', 'dropshoptheme' )), get_the_category_list(', '));
					?></p>

				</header>

				<section class="entry-content group">

					<?php the_post_thumbnail( 'dropshop-thumb-300' ); ?>

					<?php the_excerpt(); ?>

				</section>

				<footer class="article-footer">

				</footer>

			</article>

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

		</div>

		<?php get_sidebar(); ?>

	</div> <!-- close inner -->


<?php get_footer(); ?>
