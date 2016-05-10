<?php
/********************************

FUNCTIONS

********************************/
function dropshop_social_icons(){
	$socials = get_option( 'dropshop_theme_social_links');
	if(!empty( $socials) ){
		echo '<div class="social-icons">
		        <ul>';
		        foreach ($socials as $option => $url) {
		        	if($url){
			        	echo '<li><a href="' . $url . '" class="icon-' . $option . '" target="_blank"></a></li>';
			        }
		        };
		echo '</ul>
		</div>';
	}
}

function dropshop_share_buttons($url = ''){
	echo '<div class="social-icons pull-left">
					Share this: 
	        <ul>
	         <li><a href="' . $url . '" class="icon-facebook share-fb no-ajax"></a></li>
	         <li><a href="' . $url . '" class="icon-twitter share-twitter no-ajax"></a></li>
	         <li><a href="' . $url . '" class="icon-email share-email no-ajax"></a></li>
	        </ul>
		</ul>
	</div>';
}






/********************************

MENU OPTIONS

********************************/


/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level 
 * 'Contact info' menu.
 */
function dropshop_contact_info_menu() {

	add_theme_page(
		'Contact info', 					// The title to be displayed in the browser window for this page.
		'Contact info',					// The text to be displayed for this menu item
		'administrator',					// Which type of users can see this menu item
		'dropshop_contact_info',			// The unique ID - that is, the slug - for this menu item
		'dropshop_theme_display'				// The name of the function to call when rendering this menu's page

	);
	
	add_menu_page(
		'Contact info',					// The value used to populate the browser's title bar when the menu page is active
		'Contact info',					// The text of the menu in the administrator's sidebar
		'administrator',					// What roles are able to access the menu
		'dropshop_theme_menu',				// The ID used to bind submenu items to this menu 
		'dropshop_theme_display',				// The callback function used to render this menu
		'dashicons-phone',
		30
	);
	
	add_submenu_page(
		'dropshop_theme_menu',
		__( 'Social Links', 'dropshop' ),
		__( 'Social Links', 'dropshop' ),
		'administrator',
		'dropshop_theme_social_links',
		create_function( null, 'dropshop_theme_display( "social_links" );' )
	);

	add_submenu_page(
		'dropshop_theme_menu',
		__( 'Social Options', 'dropshop' ),
		__( 'Social Options', 'dropshop' ),
		'administrator',
		'dropshop_theme_social_options',
		create_function( null, 'dropshop_theme_display( "social_options" );' )
	);
	

} // end dropshop_contact_info_menu
add_action( 'admin_menu', 'dropshop_contact_info_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function dropshop_theme_display( $active_tab = '' ) {
?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'Contact Info', 'dropshop' ); ?></h2>
		<?php settings_errors(); ?>
		
		<?php if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'social_links' ) {
			$active_tab = 'social_links';
		} else if( $active_tab == 'social_options' ) {
			$active_tab = 'social_options';
		} else {
			$active_tab = 'contact_info';
		} // end if/else ?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=dropshop_contact_info&tab=contact_info" class="nav-tab <?php echo $active_tab == 'contact_info' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Contact info', 'dropshop' ); ?></a>
			<a href="?page=dropshop_contact_info&tab=social_links" class="nav-tab <?php echo $active_tab == 'social_links' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Links', 'dropshop' ); ?></a>
			<a href="?page=dropshop_contact_info&tab=social_options" class="nav-tab <?php echo $active_tab == 'social_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Social Options', 'dropshop' ); ?></a>
		</h2>
		
		<form method="post" action="options.php">
			<?php
			
				if( $active_tab == 'contact_info' ) {
				
					settings_fields( 'dropshop_theme_contact_info' );
					do_settings_sections( 'dropshop_theme_contact_info' );
					
				} elseif( $active_tab == 'social_links' ) {
				
					settings_fields( 'dropshop_theme_social_links' );
					do_settings_sections( 'dropshop_theme_social_links' );
					
				} else {
				
					settings_fields( 'dropshop_theme_social_options' );
					do_settings_sections( 'dropshop_theme_social_options' );
					
				} // end if/else
				
				submit_button();
			
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end dropshop_theme_display







/********************************

CONTACT INFO

********************************/
/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function dropshop_initialize_contact_info() {

	// If the theme options don't exist, create them.
	if( false == get_option( 'dropshop_theme_contact_info' ) ) {	
		add_option( 'dropshop_theme_contact_info' );
	} // end if

	// First, we register a section. This is necessary since all future options must belong to a 
	add_settings_section(
		'contact_info_section',			// ID used to identify this section and with which to register options
		__( 'Contact info', 'dropshop' ),		// Title to be displayed on the administration page
		'dropshop_general_options_callback',	// Callback used to render the description of the section
		'dropshop_theme_contact_info'		// Page on which to add this section of options
	);
	
	// Next, we'll introduce the fields for toggling the visibility of content elements.
	add_settings_field(	
		'Email address',						// ID used to identify the field throughout the theme
		__( 'Email address', 'dropshop' ),							// The label to the left of the option interface element
		'dropshop_email_address_callback',	// The name of the function responsible for rendering the option interface
		'dropshop_theme_contact_info',	// The page on which this option will be displayed
		'contact_info_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			__( 'Enter your contact email address.', 'dropshop' ),
		)
	);
	
	add_settings_field(	
		'Phone Number',						
		__( 'Phone number', 'dropshop' ),				
		'dropshop_phone_number_callback',	
		'dropshop_theme_contact_info',					
		'contact_info_section',			
		array(								
			__( 'Enter you contact phone number.', 'dropshop' ),
		)
	);
	
	add_settings_field(	
		'Address line 1',						
		__( 'Address line 1', 'dropshop' ),				
		'dropshop_address_line_1_callback',	
		'dropshop_theme_contact_info',		
		'contact_info_section',			
		array(								
			__( 'Street number and street name', 'dropshop' ),
		)
	);

	add_settings_field(	
		'Address line 2',						
		__( 'Address line 2', 'dropshop' ),				
		'dropshop_address_line_2_callback',	
		'dropshop_theme_contact_info',		
		'contact_info_section',			
		array(								
			__( 'Optional', 'dropshop' ),
		)
	);

	add_settings_field(	
		'City',						
		__( 'City', 'dropshop' ),				
		'dropshop_city_callback',	
		'dropshop_theme_contact_info',		
		'contact_info_section',			
		array(								
			__( 'Enter city/Town', 'dropshop' ),
		)
	);

	add_settings_field(	
		'Postcode',						
		__( 'Postcode', 'dropshop' ),				
		'dropshop_postcode_callback',	
		'dropshop_theme_contact_info',		
		'contact_info_section',			
		array(								
			__( '', 'dropshop' ),
		)
	);

	add_settings_field(	
		'Maps Latitude',						
		__( 'Maps Latitude', 'dropshop' ),				
		'dropshop_latitude_callback',	
		'dropshop_theme_contact_info',		
		'contact_info_section',			
		array(								
			__( 'Enter your address latitude to show a marker on a map', 'dropshop' ),
		)
	);

	add_settings_field(	
		'Maps Longitude',						
		__( 'Maps Longitude', 'dropshop' ),				
		'dropshop_longitude_callback',	
		'dropshop_theme_contact_info',		
		'contact_info_section',			
		array(								
			__( 'Enter your address longitude to show a marker on a map', 'dropshop' ),
		)
	);
	
	// Finally, we register the fields with WordPress
	register_setting(
		'dropshop_theme_contact_info',
		'dropshop_theme_contact_info'
	);
	
} // end dropshop_initialize_contact_info
add_action( 'admin_init', 'dropshop_initialize_contact_info' );












/********************************

SOCIAL INFO

********************************/

/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function dropshop_theme_intialize_social_links() {

	if( false == get_option( 'dropshop_theme_social_links' ) ) {	
		add_option( 'dropshop_theme_social_links' );
	} // end if
	
	add_settings_section(
		'social_settings_section',			// ID used to identify this section and with which to register options
		__( 'Social Links', 'dropshop' ),		// Title to be displayed on the administration page
		'dropshop_social_links_callback',	// Callback used to render the description of the section
		'dropshop_theme_social_links'		// Page on which to add this section of options
	);
	
	add_settings_field(	
		'twitter',						
		'Twitter',							
		'dropshop_twitter_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'facebook',						
		'Facebook',							
		'dropshop_facebook_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'youtube',						
		'YouTube',							
		'dropshop_youtube_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'soundcloud',						
		'Soundcloud',							
		'dropshop_soundcloud_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'spotify',						
		'Spotify',							
		'dropshop_spotify_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'	
	);
	
	add_settings_field(	
		'linkedin',						
		'Linked In',							
		'dropshop_linkedin_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'pinterest',						
		'Pinterest',							
		'dropshop_pinterest_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'snapchat',						
		'snapchat',							
		'dropshop_snapchat_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);
	
	add_settings_field(	
		'instagram',						
		'Instagram',							
		'dropshop_instagram_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'googleplus',						
		'Google+',							
		'dropshop_googleplus_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'vine',						
		'Vine',							
		'dropshop_vine_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);

	add_settings_field(	
		'twitch',						
		'Twitch',							
		'dropshop_twitch_callback',	
		'dropshop_theme_social_links',	
		'social_settings_section'			
	);
	
	register_setting(
		'dropshop_theme_social_links',
		'dropshop_theme_social_links',
		'dropshop_theme_sanitize_social_links'
	);
	
} // end dropshop_theme_intialize_social_links
add_action( 'admin_init', 'dropshop_theme_intialize_social_links' );











/********************************

SOCIAL INTEGRATION OPTIONS

********************************/

/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function dropshop_theme_intialize_social_options() {

	if( false == get_option( 'dropshop_theme_social_options' ) ) {	
		add_option( 'dropshop_theme_social_options' );
	} // end if
	
	add_settings_section(
		'social_settings_section',			// ID used to identify this section and with which to register options
		__( 'Social Integration Options', 'dropshop' ),		// Title to be displayed on the administration page
		'dropshop_social_options_callback',	// Callback used to render the description of the section
		'dropshop_theme_social_options'		// Page on which to add this section of options
	);
	
	add_settings_field(	
		'share_text',						
		'Share text',							
		'dropshop_share_text_callback',	
		'dropshop_theme_social_options',	
		'social_settings_section'			
	);

	add_settings_field(	
		'facebook_app_id',						
		'Facebook App ID',							
		'dropshop_facebook_app_id_callback',	
		'dropshop_theme_social_options',	
		'social_settings_section'			
	);

	
	register_setting(
		'dropshop_theme_social_options',
		'dropshop_theme_social_options'
	);
	
} // end dropshop_theme_intialize_social_options
add_action( 'admin_init', 'dropshop_theme_intialize_social_options' );











/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the General Options page. 
 *
 * It's called from the 'dropshop_initialize_contact_info' function by being passed as a parameter
 * in the add_settings_section function.
 */
function dropshop_general_options_callback() {
	echo '<p>' . __( 'Enter you company/personal contact information for display on the site', 'dropshop' ) . '</p>';
} // end dropshop_general_options_callback

/**
 * This function provides a simple description for the Social Links page. 
 *
 * It's called from the 'dropshop_theme_intialize_social_links' function by being passed as a parameter
 * in the add_settings_section function.
 */
function dropshop_social_links_callback() {
	echo '<p>' . __( 'Provide the URL to the social networks you\'d like to display.', 'dropshop' ) . '</p>';
} // end dropshop_general_options_callback


/**
 * This function provides a simple description for the Social Options page. 
 *
 * It's called from the 'dropshop_theme_intialize_social_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function dropshop_social_options_callback() {
	echo '<p>' . __( 'Set up social apps for sharing', 'dropshop' ) . '</p>';
} // end dropshop_general_options_callback















/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function dropshop_email_address_callback($args) {
	
	// First, we read the options collection
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['email_address'] ) ? $options['email_address'] : "";
	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the email_address element of the options collection in the call to the checked() helper function
	$html = '<input class="regular-text" type="text" id="email_address" name="dropshop_theme_contact_info[email_address]" value="' . $value . '" />'; 
	
	// Here, we'll take the first argument of the array and add it to a label next to the text
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_contact_email_address_callback

function dropshop_phone_number_callback($args) {

	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['phone_number'] ) ? $options['phone_number'] : "";
	$html = '<input class="regular-text" type="text" id="phone_number" name="dropshop_theme_contact_info[phone_number]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_contact_phone_number_callback

function dropshop_address_line_1_callback($args) {
	
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['address_line_1'] ) ? $options['address_line_1'] : "";
	$html = '<input class="regular-text" type="text" id="address_line_1" name="dropshop_theme_contact_info[address_line_1]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_address_line_1_callback

function dropshop_address_line_2_callback($args) {
	
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['address_line_2'] ) ? $options['address_line_2'] : "";
	$html = '<input class="regular-text" type="text" id="address_line_2" name="dropshop_theme_contact_info[address_line_2]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_address_line_2_callback

function dropshop_city_callback($args) {
	
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['city'] ) ? $options['city'] : "";
	$html = '<input class="regular-text" type="text" id="city" name="dropshop_theme_contact_info[city]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_city_callback

function dropshop_postcode_callback($args) {
	
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['postcode'] ) ? $options['postcode'] : "";
	$html = '<input class="regular-text" type="text" id="postcode" name="dropshop_theme_contact_info[postcode]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_postcode_callback

function dropshop_latitude_callback($args) {
	
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['latitude'] ) ? $options['latitude'] : "";
	$html = '<input class="regular-text" type="text" id="latitude" name="dropshop_theme_contact_info[latitude]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_latitude_callback


function dropshop_longitude_callback($args) {
	
	$options = get_option('dropshop_theme_contact_info');
	$value = isset( $options['longitude'] ) ? $options['longitude'] : "";
	$html = '<input class="regular-text" type="text" id="longitude" name="dropshop_theme_contact_info[longitude]" value="' . $value . '" />'; 
	$html .= '<br><span class="description">'  . $args[0] . '</span>'; 
	
	echo $html;
	
} // end dropshop_longitude_callback












function dropshop_twitter_callback() {
	
	// First, we read the social options collection
	$options = get_option( 'dropshop_theme_social_links' );
	
	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['twitter'] ) ) {
		$url = esc_url( $options['twitter'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="twitter" name="dropshop_theme_social_links[twitter]" value="' . $url . '" />';
	
} // end dropshop_twitter_callback

function dropshop_facebook_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['facebook'] ) ) {
		$url = esc_url( $options['facebook'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="facebook" name="dropshop_theme_social_links[facebook]" value="' . $url . '" />';
	
} // end dropshop_facebook_callback

function dropshop_instagram_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['instagram'] ) ) {
		$url = esc_url( $options['instagram'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="instagram" name="dropshop_theme_social_links[instagram]" value="' . $url . '" />';
	
} // end dropshop_instagram_callback

function dropshop_youtube_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['youtube'] ) ) {
		$url = esc_url( $options['youtube'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="youtube" name="dropshop_theme_social_links[youtube]" value="' . $url . '" />';
	
} // end dropshop_youtube_callback

function dropshop_soundcloud_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['soundcloud'] ) ) {
		$url = esc_url( $options['soundcloud'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="soundcloud" name="dropshop_theme_social_links[soundcloud]" value="' . $url . '" />';
	
} // end dropshop_soundcloud_callback

function dropshop_spotify_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['spotify'] ) ) {
		$url = esc_url( $options['spotify'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="spotify" name="dropshop_theme_social_links[spotify]" value="' . $url . '" />';
	
} // end dropshop_spotify_callback

function dropshop_pinterest_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['pinterest'] ) ) {
		$url = esc_url( $options['pinterest'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="pinterest" name="dropshop_theme_social_links[pinterest]" value="' . $url . '" />';
	
} // end dropshop_pinterest_callback

function dropshop_snapchat_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['snapchat'] ) ) {
		$url = esc_url( $options['snapchat'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="snapchat" name="dropshop_theme_social_links[snapchat]" value="' . $url . '" />';
	
} // end dropshop_snapchat_callback

function dropshop_linkedin_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['linkedin'] ) ) {
		$url = esc_url( $options['linkedin'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="linkedin" name="dropshop_theme_social_links[linkedin]" value="' . $url . '" />';
	
} // end dropshop_linkedin_callback

function dropshop_googleplus_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['googleplus'] ) ) {
		$url = esc_url( $options['googleplus'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="googleplus" name="dropshop_theme_social_links[googleplus]" value="' . $url . '" />';
	
} // end dropshop_googleplus_callback

function dropshop_vine_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['vine'] ) ) {
		$url = esc_url( $options['vine'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="vine" name="dropshop_theme_social_links[vine]" value="' . $url . '" />';
	
} // end dropshop_vine_callback

function dropshop_twitch_callback() {
	
	$options = get_option( 'dropshop_theme_social_links' );
	
	$url = '';
	if( isset( $options['twitch'] ) ) {
		$url = esc_url( $options['twitch'] );
	} // end if
	
	// Render the output
	echo '<input class="regular-text" type="text" id="twitch" name="dropshop_theme_social_links[twitch]" value="' . $url . '" />';
	
} // end dropshop_twitch_callback












function dropshop_share_text_callback() {
	
	$options = get_option( 'dropshop_theme_social_options' );
	
	$value = isset( $options['share_text'] ) ? $options['share_text'] : "";
	// Render the output
	$html =  '<textarea class="large-text" rows="4" id="share_text" name="dropshop_theme_social_options[share_text]">' . $value . '</textarea>';
	$html .= '<br><span class="description">Enter some text to pre-populate the sharing dialog on Twitter (Facebook does not allow pre-populating)</span>'; 
	echo $html;
} // end dropshop_share_text_callback

function dropshop_facebook_app_id_callback() {
	
	$options = get_option( 'dropshop_theme_social_options' );
	
	$value = isset( $options['facebook_app_id'] ) ? $options['facebook_app_id'] : "";
	// Render the output
	echo '<input class="regular-text" type="text" id="facebook_app_id" name="dropshop_theme_social_options[facebook_app_id]" value="' . $value . '" />';
	
} // end dropshop_facebook_app_id_callback






/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
 
 /**
 * Sanitization callback for the social links. Since each of the social options are text inputs,
 * this function loops through the incoming option and strips all tags and slashes from the value
 * before serializing it.
 *	
 * @params	$input	The unsanitized collection of options.
 *
 * @returns			The collection of sanitized values.
 */
function dropshop_theme_sanitize_social_links( $input ) {
	
	// Define the array for the updated options
	$output = array();

	// Loop through each of the options sanitizing the data
	foreach( $input as $key => $val ) {
	
		if( isset ( $input[$key] ) ) {
			$output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
		} // end if	
	
	} // end foreach
	
	// Return the new collection
	return apply_filters( 'dropshop_theme_sanitize_social_links', $output, $input );

} // end dropshop_theme_sanitize_social_links



?>