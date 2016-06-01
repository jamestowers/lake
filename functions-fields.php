<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'dropshop_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function dropshop_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
/*function dropshop_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}*/

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array             $field_args Array of field parameters
 * @param  CMB2_Field object $field      Field object
 */
/*function dropshop_before_row_if_2( $field_args, $field ) {
	if ( 2 == $field->object_id ) {
		echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
	} else {
		echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
	}
}*/

add_action( 'cmb2_admin_init', 'dropshop_page_layout_metabox' );
function dropshop_page_layout_metabox() {
	$prefix = '_dropshop_page_layout_';

	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Page Layout', 'cmb2' ),
		'object_types'  => array( 'page' ),
		'priority'   => 'high',
		//'show_names' => false, // Show field names on the left
	) );

	$cmb_demo->add_field( array(
		'name'             => __( 'Layout style', 'cmb2' ),
		'desc'             => __( 'Defaults to two columns', 'cmb2' ),
		'id'               => $prefix . 'choice',
		'type'             => 'radio_inline',
		'options'          => array(
			'1col'     => __( 'Single column', 'cmb2' ),
			'2col'   => __( 'Two column', 'cmb2' ),
		),
	) );
}


add_action( 'cmb2_admin_init', 'dropshop_page_class_metabox' );
function dropshop_page_class_metabox() {
	$prefix = '_dropshop_page_class_';

	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Custom page class', 'cmb2' ),
		'object_types'  => array( 'page' ),
		'priority'   => 'low',
		//'show_names' => false, // Show field names on the left
	) );

	$cmb_demo->add_field( array(
		'name'       => __( 'Class', 'cmb2' ),
		'desc'       => __( 'Add custom CSS classes to the this page', 'cmb2' ),
		'id'         => $prefix . 'custom_page_class',
		'type'       => 'text'
	) );
}

add_action( 'cmb2_admin_init', 'dropshop_pre_content_metabox' );
function dropshop_pre_content_metabox() {
	$prefix = '_dropshop_pre_content_';

	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Pre content', 'cmb2' ),
		'object_types'  => array( 'page' ),
		'priority'   => 'high',
		//'show_names' => false, // Show field names on the left
	) );

	$cmb_demo->add_field( array(
		'name'    => __( 'Text', 'cmb2' ),
		'desc'    => __( 'Optional extra content to show on page, on the left in a two column layout', 'cmb2' ),
		'id'      => $prefix . 'pre_content',
		'type'    => 'wysiwyg',
		'options' => array( 'textarea_rows' => 10, ),
	) );

	$socials = get_option( 'dropshop_theme_social_links');
	$options = array();
	foreach($socials as $option => $url){
		$options[$option] = ucwords($option);
	};
	$cmb_demo->add_field( array(
		'name'    => __( 'Show social links', 'cmb2' ),
		'desc'    => __( 'Show these links to social media', 'cmb2' ),
		'id'      => $prefix . 'socials',
		'type'    => 'multicheck',
		'options' => $options,
		'inline'  => true, // Toggles display to inline
	) );

}


add_action( 'cmb2_admin_init', 'dropshop_hero_metabox' );
function dropshop_hero_metabox() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_dropshop_hero_';

	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Hero video options', 'cmb2' ),
		'object_types'  => array( 'page', 'post', ), // Post type
		// 'show_on_cb' => 'dropshop_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$cmb_demo->add_field( array(
		'name'       => __( 'URL', 'cmb2' ),
		'desc'       => __( 'Insert a video url', 'cmb2' ),
		'id'         => $prefix . 'video_url',
		'type'       => 'text_url',
		//'show_on_cb' => 'dropshop_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb_demo->add_field( array(
		'name'             => __( 'Video type', 'cmb2' ),
		'desc'             => __( 'Select the type of video', 'cmb2' ),
		'id'               => $prefix . 'video_type',
		'type'             => 'radio_inline',
		//'show_option_none' => 'No Selection',
		'options'          => array(
			'url'     => __( 'URL', 'cmb2' ),
			'vimeo'   => __( 'Vimeo', 'cmb2' ),
			'youtube' => __( 'YouTube', 'cmb2' ),
		),
	) );

}

add_action( 'cmb2_admin_init', 'dropshop_embeds_metabox' );
function dropshop_embeds_metabox() {

	$prefix = '_dropshop_embeds_';
		/**
	 * Repeatable Field Groups
	 */
	$cmb_group = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Embedable widgets', 'cmb2' ),
		'object_types'  => array( 'page', 'post', ),
	) );

	$group_field_id = $cmb_group->add_field( array(
		'id'          => $prefix . 'group',
		'type'        => 'group',
		'description' => __( 'Add multiple embedable widgets', 'cmb2' ),
		'options'     => array(
			'group_title'   => __( 'Embed {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Embed', 'cmb2' ),
			'remove_button' => __( 'Remove Embed', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name' => __( 'Embed code', 'cmb2' ),
		'desc' => __( 'Paste the embed code, YouTube url or Vimeo url in here', 'cmb2' ),
		'id'   => $prefix . 'embed_code',
		'type' => 'textarea_code',
	) );
}

