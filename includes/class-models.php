<?php
/**
 * This file contains the WP_Models class.
 */

/**
 * This class handles the creation of the "Models" post type, and creates a
 * UI to display the Model-specific data on the admin screens.
 *
 */
class WP_Models {

	var $settings_page = 'wp-models-settings';
	var $settings_field = 'wp_models_taxonomies';
	var $menu_page = 'register-taxonomies';

	var $options;

	/**
	 * Property details array.
	 */
	var $property_details;

	/**
	 * Construct Method.
	 */
	function __construct() {

		$this->options = get_option('plugin_wp_models_settings');

		$this->property_details = apply_filters( 'wp_models_property_details', array(
			'col1' => array(
			    __( 'Price:', 'wp_models' ) 					=> '_model_price',
			    __( 'Address:', 'wp_models' )					=> '_model_address',
			    __( 'City:', 'wp_models' )					=> '_model_city',
			    __( 'State:', 'wp_models' )					=> '_model_state',
			    __( 'ZIP:', 'wp_models' )						=> '_model_zip',
			    __( 'MLS #:', 'wp_models' ) 					=> '_model_mls',
				__( 'Open House Time & Date:', 'wp_models' ) 	=> '_model_open_house'
			),
			'col2' => array(
			    __( 'Year Built:', 'wp_models' ) 				=> '_model_year_built',
			    __( 'Floors:', 'wp_models' ) 					=> '_model_floors',
			    __( 'Square Feet:', 'wp_models' )				=> '_model_sqft',
				__( 'Lot Square Feet:', 'wp_models' )			=> '_model_lot_sqft',
			    __( 'Bedrooms:', 'wp_models' )				=> '_model_bedrooms',
			    __( 'Bathrooms:', 'wp_models' )				=> '_model_bathrooms',
			    __( 'Pool:', 'wp_models' )					=> '_model_pool'
			),
		) );

		add_action( 'init', array( $this, 'create_post_type' ) );

		add_filter( 'manage_edit-model_columns', array( $this, 'columns_filter' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );

		add_action( 'admin_menu', array( $this, 'register_meta_boxes' ), 5 );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_init', array( &$this, 'add_options' ) );
		add_action( 'admin_menu', array( &$this, 'settings_init' ), 15 );
	}

	/**
	 * Registers the option to load the stylesheet
	 */
	function register_settings() {
		register_setting( 'wp_models_options', 'plugin_wp_models_settings' );
	}

	/**
	 * Sets default slug in options
	 */
	function add_options() {

		$new_options = array(
			'wp_models_archive_posts_num' => 9,
			'wp_models_slug' => 'models'
		);

		if ( empty($this->options['wp_models_slug']) && empty($this->options['wp_models_archive_posts_num']) )  {
			add_option( 'plugin_wp_models_settings', $new_options );
		}
	}

	/**
	 * Adds settings page in admin menu
	 */
	function settings_init() {
		add_submenu_page( 'edit.php?post_type=model', __( 'Settings', 'wp_models' ), __( 'Settings', 'wp_models' ), 'manage_options', $this->settings_page, array( &$this, 'settings_page' ) );
	}

	/**
	 * Creates display of settings page along with form fields
	 */
	function settings_page() {
		include( dirname( __FILE__ ) . '/views/wp-models-settings.php' );
	}

	/**
	 * Creates our "Model" post type.
	 */
	function create_post_type() {

		$args = apply_filters( 'wp_models_post_type_args',
			array(
				'labels' => array(
					'name'					=> __( 'Models', 'wp_models' ),
					'singular_name'			=> __( 'Model', 'wp_models' ),
					'add_new'				=> __( 'Add New', 'wp_models' ),
					'add_new_item'			=> __( 'Add New Model', 'wp_models' ),
					'edit'					=> __( 'Edit', 'wp_models' ),
					'edit_item'				=> __( 'Edit Model', 'wp_models' ),
					'new_item'				=> __( 'New Model', 'wp_models' ),
					'view'					=> __( 'View Model', 'wp_models' ),
					'view_item'				=> __( 'View Model', 'wp_models' ),
					'search_items'			=> __( 'Search Models', 'wp_models' ),
					'not_found'				=> __( 'No models found', 'wp_models' ),
					'not_found_in_trash'	=> __( 'No models found in Trash', 'wp_models' )
				),
				'public'		=> true,
				'query_var'		=> true,
				'menu_position'	=> 5,
				'menu_icon'		=> 'dashicons-admin-home',
				'has_archive'	=> true,
				'supports'		=> array( 'title', 'editor', 'author', 'comments', 'excerpt', 'thumbnail', 'revisions', 'equity-layouts', 'equity-cpt-archives-settings', 'genesis-seo', 'genesis-layouts', 'genesis-simple-sidebars', 'genesis-cpt-archives-settings'),
				'rewrite'		=> array( 'slug' => $this->options['wp_models_slug'], 'feeds' => true, 'with_front' => false ),
			)
		);

		register_post_type( 'model', $args );

	}

	function register_meta_boxes() {

		add_meta_box( 'model_details_metabox', __( 'Property Details', 'wp_models' ), array( &$this, 'model_details_metabox' ), 'model', 'normal', 'high' );
		add_meta_box( 'model_features_metabox', __( 'Additional Details', 'wp_models' ), array( &$this, 'model_features_metabox' ), 'model', 'normal', 'high' );
		add_meta_box( 'bc_metabox', __( 'Bunt Creative', 'wp_models' ), array( &$this, 'bc_metabox' ), 'wp-models-options', 'side', 'core' );

	}

	function model_details_metabox() {
		include( dirname( __FILE__ ) . '/views/model-details-metabox.php' );
	}

	function model_features_metabox() {
		include( dirname( __FILE__ ) . '/views/model-features-metabox.php' );
	}

	function bc_metabox() {
		include( dirname( __FILE__ ) . '/views/bc-metabox.php' );
	}

	function metabox_save( $post_id, $post ) {

		/** Run only on models post type save */
		if ( 'model' != $post->post_type )
			return;

		if ( !isset( $_POST['wp_models_metabox_nonce'] ) || !wp_verify_nonce( $_POST['wp_models_metabox_nonce'], 'wp_models_metabox_save' ) )
	        return $post_id;

	    /** Don't try to save the data under autosave, ajax, or future post */
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
	    if ( defined( 'DOING_CRON' ) && DOING_CRON ) return;

	    /** Check permissions */
	    if ( ! current_user_can( 'edit_post', $post_id ) )
	        return;

	    $property_details = $_POST['wp_models'];

	    /** Store the property details custom fields */
	    foreach ( (array) $property_details as $key => $value ) {

	        /** Save/Update/Delete */
	        if ( $value ) {
	            update_post_meta($post->ID, $key, $value);
	        } else {
	            delete_post_meta($post->ID, $key);
	        }

	    }

	}

	/**
	 * Filter the columns in the "Models" screen, define our own.
	 */
	function columns_filter ( $columns ) {

		$columns = array(
			'cb'					=> '<input type="checkbox" />',
			'model_thumbnail'		=> __( 'Thumbnail', 'wp_models' ),
			'title'					=> __( 'Model Title', 'wp_models' ),
			'model_details'		=> __( 'Details', 'wp_models' ),
			'model_tags'			=> __( 'Tags', 'wp_models' )
		);

		return $columns;

	}

	/**
	 * Filter the data that shows up in the columns in the "Models" screen, define our own.
	 */
	function columns_data( $column ) {

		global $post, $wp_taxonomies;

		switch( $column ) {
			case "model_thumbnail":
				printf( '<p>%s</p>', the_post_thumbnail( 'thumbnail' ) );
				break;
			case "model_details":
				foreach ( (array) $this->property_details['col1'] as $label => $key ) {
					printf( '<b>%s</b> %s<br />', esc_html( $label ), esc_html( get_post_meta($post->ID, $key, true) ) );
				}
				foreach ( (array) $this->property_details['col2'] as $label => $key ) {
					printf( '<b>%s</b> %s<br />', esc_html( $label ), esc_html( get_post_meta($post->ID, $key, true) ) );
				}
				break;
			case "model_tags":
				echo '<b>Status</b>: ' . get_the_term_list( $post->ID, 'status', '', ', ', '' ) . '<br /><br />';
				echo '<b>Property Type:</b> ' . get_the_term_list( $post->ID, 'property-types', '', ', ', '' ) . '<br /><br />';
				echo '<b>Location:</b> ' . get_the_term_list( $post->ID, 'locations', '', ', ', '' ) . '<br /><br />';
				echo '<b>Features</b><br />' . get_the_term_list( $post->ID, 'features', '', ', ', '' );
				break;
		}

	}

}