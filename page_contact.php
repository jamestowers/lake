<?php /* Template Name: Contact page */

get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <section <?php post_class( ); ?> >
    
            <?php dropshop_hero_image();?>

            <div class="inner pad group">

        		<h1 class="page-title"><?php the_title(); ?></h1>

        		<div class="entry-content group col7">
        			<?php the_content(); ?>
        		</div>

                <div class="col5">

                    <?php $contact_info = get_option( 'dropshop_theme_contact_info' );?>

                    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                    
                    <div style="overflow:hidden;height:500px;width:100%;">
                        <div id="gmap_canvas" style="height:500px;width:100%;"></div>
                        <style>
                            #gmap_canvas img{
                                max-width:none!important;
                                background:none!important
                            }
                        </style>
                    </div>

                    <script type="text/javascript"> 
                        function init_map(){
                            var myOptions = {
                                zoom:14,
                                center:new google.maps.LatLng(<?php echo $contact_info['latitude'];?>,<?php echo $contact_info['longitude'];?>),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            };
                            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                            marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(<?php echo $contact_info['latitude'];?>,<?php echo $contact_info['longitude'];?>)
                            });
                            infowindow = new google.maps.InfoWindow({
                                content:"<b><?php echo get_bloginfo('name');?></b><br/><?php echo $contact_info['address_line_1'];?> <br /><?php echo $contact_info['address_line_2'];?> <br /><?php echo $contact_info['address_line_2'];?> <br /><?php echo $contact_info['city'];?> <br /> <?php echo $contact_info['postcode'];?>" 
                            });
                            google.maps.event.addListener(marker, "click", function(){
                                infowindow.open(map,marker);
                            });
                            infowindow.open(map,marker);
                        }
                        google.maps.event.addDomListener(window, 'load', init_map);
                    </script>  

                </div>
                
            </div>

    	</section>


	<?php endwhile; ?>

  <?php else : ?>

    <?php not_found_message();?>

  <?php endif; ?>


<?php get_footer(); ?>
