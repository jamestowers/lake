
					</div> <!-- close content -->

				<div id="curtain" class="">
					<div></div>
				</div>
				
				<footer class="footer group">
						
					<div id="footer-asides" class="group">

					  <?php dynamic_sidebar( 'footer-sidebar' ); ?>

					</div>

					<nav role="navigation" id="nav-footer">
						<?php dropshop_nav_footer(); ?>
					</nav>

					<p class="small source-org">&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>.</p>

				</footer>
			</div> <!-- close body_class() div -->
		</div> <!-- close wrapper -->

		<?php wp_footer(); ?>


		<!-- FACEBOOK -->
		<?php $social_options = get_option("dropshop_theme_social_options");?>
		<?php if( !empty($social_options) && $social_options['facebook_app_id'] !== "" ){ ?>

			<script>
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId      : '<?php echo $social_options["facebook_app_id"];?>',
			      xfbml      : true,
			      version    : 'v2.5'
			    });
			  };

			  (function(d, s, id){
			     var js, fjs = d.getElementsByTagName(s)[0];
			     if (d.getElementById(id)) {return;}
			     js = d.createElement(s); js.id = id;
			     js.src = "//connect.facebook.net/en_US/sdk.js";
			     fjs.parentNode.insertBefore(js, fjs);
			   }(document, 'script', 'facebook-jssdk'));
			</script>
		<?php }?>

		
	</body>

</html>
