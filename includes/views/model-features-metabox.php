<?php

// Home Summary
global $post;

/* Removing most of these additional bits for now
*

echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Home Summary (allows shortcodes):<br /><textarea name="wp_models[_model_home_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_home_sum', true) ) );

echo '</div><br style="clear: both;" />';

// Kitchen Summary
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Kitchen Summary (allows shortcodes):<br /><textarea name="wp_models[_model_kitchen_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_kitchen_sum', true) ) );

echo '</div><br style="clear: both;" />';

// Living Room
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Living Room (allows shortcodes):<br /><textarea name="wp_models[_model_living_room]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_living_room', true) ) );

echo '</div><br style="clear: both;" />';

// Master Suite
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Master Suite (allows shortcodes):<br /><textarea name="wp_models[_model_master_suite]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_master_suite', true) ) );

echo '</div><br style="clear: both;" />';

// School and Neighborhood Info
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>School and Neighborhood Info (allows shortcodes):<br /><textarea name="wp_models[_model_school_neighborhood]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_school_neighborhood', true) ) );

echo '</div><br style="clear: both;" />';

*
*/

/* New editor field for quick closing model homes 
echo '<div style="width: 100%; float: left;">';
	
	// Hide MCE Toolbar on this editor because it's not needed
	//echo '<style>#wp-_model_gallery-wrap .mce-toolbar-grp {display: none;}</style>';
	
	_e('<p><label>Add an additional tab with room for images, text and other details for display in a tab beside Description<br />', 'wp_models');

	$wpmodels_quick_closing_content = get_post_meta( $post->ID, '_model_quick_closing', true);
	$wpmodels_quick_closing_editor_id = '_model_quick_closing';

	$wpmodels_quick_closing_editor_settings = array(
			'wpautop' 			=> false,
			'textarea_name' 	=> 'wp_models[_model_quick_closing]',
			'editor_class'		=> 'wpmodels_quick_closing',
			'textarea_rows'		=> 20,
			'tinymce'			=> true,
			'quicktags'			=> true,
			'drag_drop_upload'	=> false
		);

	wp_editor($wpmodels_quick_closing_content, $wpmodels_quick_closing_editor_id, $wpmodels_quick_closing_editor_settings);

echo '</div><br style="clear: both;" />';*/