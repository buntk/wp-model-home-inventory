<?php
wp_nonce_field( 'wp_models_metabox_save', 'wp_models_metabox_nonce' );

global $post;

$pattern = '<p><label>%s<br /><input type="text" name="wp_models[%s]" value="%s" /></label></p>';

echo '<div style="width: 45%; float: left">';

	foreach ( (array) $this->property_details['col1'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div>';

echo '<div style="width: 45%; float: left;">';

	foreach ( (array) $this->property_details['col2'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div><br style="clear: both;" /><br /><br />';

echo '<div style="width: 90%; float: left;">';

	_e('<p><label>Custom Listing Text (custom text to display as overlay on featured model widget)<br />', 'wp_models');
	printf( __( '<input type="text" name="wp_models[_model_text]" value="%s" /></label></p>', 'wp_models' ), htmlentities( get_post_meta( $post->ID, '_model_text', true) ) );

echo '</div><br style="clear: both;" /><br /><br />';

echo '<div style="width: 100%; float: left;">';
	
	// Hide MCE Toolbar on this editor because it's not needed
	echo '<style>#wp-_model_gallery-wrap .mce-toolbar-grp {display: none;}</style>';
	
	_e('<p><label>Photo Gallery (use Add Media button to insert Gallery):<br />', 'wp_models');

	$wpmodels_gallery_content = get_post_meta( $post->ID, '_model_gallery', true);
	$wpmodels_gallery_editor_id = '_model_gallery';

	$wpmodels_gallery_editor_settings = array(
			'wpautop' 			=> false,
			'textarea_name' 	=> 'wp_models[_model_gallery]',
			'editor_class'		=> 'wpmodels_gallery',
			'textarea_rows'		=> 20,
			'tinymce'			=> true,
			'quicktags'			=> false,
			'drag_drop_upload'	=> false
		);

	wp_editor($wpmodels_gallery_content, $wpmodels_gallery_editor_id, $wpmodels_gallery_editor_settings);

echo '</div><br style="clear: both;" /><br /><br />';

echo '<div style="width: 90%; float: left;">';
	
	_e('<p><label>Enter Video or Virtual Tour Embed Code:<br />', 'wp_models');
	printf( __( '<textarea name="wp_models[_model_video]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_video', true) ) );

echo '</div><br style="clear: both;" />';

echo '<div style="width: 90%; float: left;">';
	
	_e('<p><label>Enter Map Embed Code or shortcode from Map plugin (such as <a href="https://wordpress.org/plugins/simple-google-maps-short-code/">Simple Google Maps Short Code</a> or <a href="https://wordpress.org/plugins/mappress-google-maps-for-wordpress/">MapPress</a>):<br /><em>Recommend size: 660x300 (If possible, use 100% width, or your themes content width)</em><br />', 'wp_models');
	printf( __( '<textarea name="wp_models[_model_map]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_map', true) ) );

echo '</div>';

echo '<div style="width: 90%; float: left;">';
	
	_e('<p><label>If you use a Contact Form plugin, you may enter the Contact Form shortcode here. Otherwise, the single model template will use a default contact form:<br />', 'wp_models');
	printf( __( '<textarea name="wp_models[_model_contact_form]" rows="1" cols="18" style="%s">%s</textarea></label></p>', 'wp_models' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_model_contact_form', true) ) );

echo '</div><br style="clear: both;" />';