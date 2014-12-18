<?php

// Home Summary
global $post;

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