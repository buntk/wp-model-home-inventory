<?php
/*
	Plugin Name: WP Model Home Inventory
	Plugin URI: http://buntcreative.com/wordpress/wp-model-home-inventory
	Description: Iteration of the WP Listing plugin (http://wordpress.org/plugins/wp-listings/). Designed to work with Organic Themes' Seed Framework.
	Author: Bunt Creative
	Author URI: http://buntcreative.com
	Version: 0.0.1
	License: GNU General Public License v2.0 (or later)
	License URI: http://www.opensource.org/licenses/gpl-license.php
*/

register_activation_hook( __FILE__, 'wp_models_activation' );
/**
 * This function runs on plugin activation. It flushes the rewrite rules to prevent 404's
 *
 * @since 0.0.1
 */
function wp_models_activation() {

		/** Flush rewrite rules */
		if ( ! post_type_exists( 'model' ) ) {
			wp_models_init();
			global $_wp_models, $_wp_models_taxonomies, $_wp_models_templates;
			$_wp_models->create_post_type();
			$_wp_models_taxonomies->register_taxonomies();
		}
		flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'wp_models_deactivation' );
/**
 * This function runs on plugin deactivation. It flushes the rewrite rules to get rid of remnants
 *
 * @since 0.0.1
 */
function wp_models_deactivation() {

		flush_rewrite_rules();
}

add_action( 'after_setup_theme', 'wp_models_init' );
/**
 * Initialize WP Listings.
 *
 * Include the libraries, define global variables, instantiate the classes.
 *
 * @since 0.0.1
 */
function wp_models_init() {

	global $_wp_models, $_wp_models_taxonomies, $_wp_models_templates;

	define( 'WP_MODELS_URL', plugin_dir_url( __FILE__ ) );
	define( 'WP_MODELS_VERSION', '0.0.1' );

	/** Load textdomain for translation */
	load_plugin_textdomain( 'wp_models', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	/** Includes */
	require_once( dirname( __FILE__ ) . '/includes/helpers.php' );
	require_once( dirname( __FILE__ ) . '/includes/functions.php' );
	require_once( dirname( __FILE__ ) . '/includes/shortcodes.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-models.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-taxonomies.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-model-template.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-models-search-widget.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-featured-models-widget.php' );

	/** Add theme support for post thumbnails if it does not exist */
	if(!current_theme_supports('post-thumbnails')) {
		add_theme_support( 'post-thumbnails' );
	}

	/** Registers and enqueues scripts for single models */
	add_action('wp_enqueue_scripts', 'add_wp_models_scripts');
	function add_wp_models_scripts() {
		wp_register_script( 'wp-models-single', WP_MODELS_URL . 'includes/js/single-model.js' ); // enqueued only on single models
		wp_register_script( 'fitvids', '//cdnjs.cloudflare.com/ajax/libs/fitvids/1.1.0/jquery.fitvids.js', array('jquery'), true, true ); // enqueued only on single models
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-tabs', array('jquery') );
    }

	/** Enqueues wp-models.css style file if it exists and is not deregistered in settings */
	add_action('wp_enqueue_scripts', 'add_wp_models_main_styles');
	function add_wp_models_main_styles() {

		$options = get_option('plugin_wp_models_settings');

		/** Register single styles but don't enqueue them **/
		wp_register_style('wp-models-single', WP_MODELS_URL . '/includes/css/wp-models-single.css');

		/** Register Font Awesome icons but don't enqueue them */
		wp_register_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
		
		/** Register Properticons but don't enqueue them */
		wp_register_style('properticons', '//s3.amazonaws.com/properticons/css/properticons.css');
		
		if ( !isset($options['wp_models_stylesheet_load']) ) {
			$options['wp_models_stylesheet_load'] = 0;
		}

		if ('1' == $options['wp_models_stylesheet_load'] ) {
			return;
		}

        if ( file_exists(dirname( __FILE__ ) . '/includes/css/wp-models.css') ) {
        	wp_register_style('wp_models', WP_MODELS_URL . 'includes/css/wp-models.css');
            wp_enqueue_style('wp_models');
        }
    }

    /** Enqueues wp-models-widgets.css style file if it exists and is not deregistered in settings */
	add_action('wp_enqueue_scripts', 'add_wp_models_widgets_styles');
	function add_wp_models_widgets_styles() {

		$options = get_option('plugin_wp_models_settings');

		if ( !isset($options['wp_models_widgets_stylesheet_load']) ) {
			$options['wp_models_widgets_stylesheet_load'] = 0;
		}

		if ('1' == $options['wp_models_widgets_stylesheet_load'] ) {
			return;
		}

        if ( file_exists(dirname( __FILE__ ) . '/includes/css/wp-models-widgets.css') ) {
        	wp_register_style('wp_models_widgets', WP_MODELS_URL . 'includes/css/wp-models-widgets.css');
            wp_enqueue_style('wp_models_widgets');
        }
    }

	/** Instantiate */
	$_wp_models = new WP_Models;
	$_wp_models_taxonomies = new WP_Models_Taxonomies;
	$_wp_models_templates = new Single_Model_Template;

	add_action( 'widgets_init', 'wp_models_register_widgets' );
}

/**
 * Register Widgets that will be used in the WP Models plugin
 *
 * @since 0.1.0
 */
function wp_models_register_widgets() {

	$widgets = array( 'WP_Models_Featured_Models_Widget', 'WP_Models_Search_Widget' );

	foreach ( (array) $widgets as $widget ) {
		register_widget( $widget );
	}

}