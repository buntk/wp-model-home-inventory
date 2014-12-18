<?php
/**
 * Holds additional functions for use in the WP Models plugin
 *
 */

add_image_size( 'models-full', 960, 960, false );
add_image_size( 'models', 300, 300, false );

add_filter( 'template_include', 'wp_models_template_include' );
function wp_models_template_include( $template ) {

	$post_type = 'model';

    if ( wp_models_is_taxonomy_of($post_type) ) {
        if ( file_exists(get_stylesheet_directory() . '/archive-' . $post_type . '.php' ) ) {
            return get_stylesheet_directory() . '/archive-' . $post_type . '.php';
        } else {
            return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
        }
    }

	if ( is_post_type_archive( $post_type ) ) {
		if ( file_exists(get_stylesheet_directory() . '/archive-' . $post_type . '.php') ) {
			$template = get_stylesheet_directory() . '/archive-' . $post_type . '.php';
			return $template;
		} else {
			return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
		}
	}

	if ( is_single() && $post_type == get_post_type() ) {

		global $post;

		$custom_template = get_post_meta( $post->ID, '_wp_post_template', true );

		/** Prevent directory traversal */
		$custom_template = str_replace( '..', '', $custom_template );

		if( ! $custom_template )
			if( file_exists(get_stylesheet_directory() . '/single-' . $post_type . '.php') )
				return $template;
			else
				return dirname( __FILE__ ) . '/views/single-' . $post_type . '.php';
		else
			if( file_exists( get_stylesheet_directory() . "/{$custom_template}" ) )
				$template = get_stylesheet_directory() . "/{$custom_template}";
			elseif( file_exists( get_template_directory() . "/{$custom_template}" ) )
				$template = get_template_directory() . "/{$custom_template}";

	}

	return $template;
}

/**
 * Controls output of default state for the state custom field if there is one set
 */
function wp_models_get_state() {

	$options = get_option('plugin_wp_models_settings');

	global $post;

	$state = get_post_meta($post->ID, '_model_state', true);

	if (isset($options['wp_models_default_state'])) {
		$default_state = $options['wp_models_default_state'];
	}

	if ( empty($default_state) ) {
		$default_state = 'ST';
	}

	if ( empty($state) ) {
		return $default_state;
	}

	return $state;
}

/**
 * Controls output of city name
 */
function wp_models_get_city() {

	global $post;

	$city = get_post_meta($post->ID, '_model_city', true);

	if ( '' == $city ) {
		$city = 'Cityname';
	}

	return $city;
}

/**
 * Controls output of address
 */
function wp_models_get_address($post_id = null) {

	global $post;

	$address = get_post_meta($post->ID, '_model_address', true);

	if ( '' == $address ) {
		$address = 'Address Unavailable';
	}

	return $address;
}

/**
 * Displays the status (active, pending, sold, for rent) of a model
 */
function wp_models_get_status($post_id = null) {

	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$model_status = wp_get_object_terms($post_id, 'status');

	if ( empty($model_status) || is_wp_error($model_status) ) {
		return;
	}

	foreach($model_status as $term) {
		if ( $term->name != 'Featured' ) {
			return $term->name;
		}
	}
}

/**
 * Displays the property type (residential, condo, commercial, etc) of a model
 */
function wp_models_get_property_types($post_id = null) {

	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$model_property_types = wp_get_object_terms($post_id, 'property-types');

	if ( empty($model_property_types) || is_wp_error($model_property_types) ) {
		return;
	}

	foreach($model_property_types as $type) {
		return $type->name;
	}
}

/**
 * Displays the location term of a model
 */
function wp_models_get_locations($post_id = null) {

	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$model_locations = wp_get_object_terms($post_id, 'locations');

	if ( empty($model_locations) || is_wp_error($model_locations) ) {
		return;
	}

	foreach($model_locations as $location) {
		return $location->name;
	}
}

function wp_models_post_number( $query ) {

	if ( !$query->is_main_query() || is_admin() || !is_post_type_archive('model') ) {
		return;
	}

	$options = get_option('plugin_wp_models_settings');

	$archive_posts_num = $options['wp_models_archive_posts_num'];

	if ( empty($archive_posts_num) ) {
		$archive_posts_num = '9';
	}

	$query->query_vars['posts_per_page'] = $archive_posts_num;

}
add_action( 'pre_get_posts', 'wp_models_post_number' );


/**
 * Better Jetpack Related Posts Support for Models
 */
function wp_models_jetpack_relatedposts( $headline ) {
  if ( is_singular( 'model' ) ) {
    $headline = sprintf(
            '<h3 class="jp-relatedposts-headline"><em>%s</em></h3>',
            esc_html( 'Similar Models' )
            );
    return $headline;
  }
}
add_filter( 'jetpack_relatedposts_filter_headline', 'wp_models_jetpack_relatedposts' );
