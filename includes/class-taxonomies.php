<?php
/**
 * This file contains the WP_Models_Taxonomies class.
 */

/**
 * This class handles all the aspects of displaying, creating, and editing the
 * user-created taxonomies for the "Models" post-type.
 *
 */
class WP_Models_Taxonomies {

	var $settings_field = 'wp_models_taxonomies';
	var $menu_page = 'register-taxonomies';
	var $reorder_page = 'reorder-taxonomies';

	/**
	 * Construct Method.
	 */
	function __construct() {

		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_menu', array( &$this, 'settings_init' ), 15 );
		add_action( 'admin_init', array( &$this, 'actions' ) );
		add_action( 'admin_notices', array( &$this, 'notices' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'tax_reorder_enqueue' ) );

		add_action( 'init', array( &$this, 'register_taxonomies' ), 15 );
		add_action( 'init', array( $this, 'create_terms' ), 16 );

	}

	function register_settings() {

		register_setting( $this->settings_field, $this->settings_field );
		add_option( $this->settings_field, __return_empty_array(), '', 'yes' );

	}

	function settings_init() {

		add_submenu_page( 'edit.php?post_type=model', __( 'Register Taxonomies', 'wp_models' ), __( 'Register Taxonomies', 'wp_models' ), 'manage_options', $this->menu_page, array( &$this, 'admin' ) );
		add_submenu_page( 'edit.php?post_type=model', __( 'Reorder Taxonomies', 'wp_models' ), __( 'Reorder Taxonomies', 'wp_models' ), 'manage_options', $this->reorder_page, array( &$this, 'tax_reorder' ) );

	}

	function actions() {

		if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != $this->menu_page ) {
			return;
		}

		/** This section handles the data if a new taxonomy is created */
		if ( isset( $_REQUEST['action'] ) && 'create' == $_REQUEST['action'] ) {
			$this->create_taxonomy( $_POST['wp_models_taxonomy'] );
		}

		/** This section handles the data if a taxonomy is deleted */
		if ( isset( $_REQUEST['action'] ) && 'delete' == $_REQUEST['action'] ) {
			$this->delete_taxonomy( $_REQUEST['id'] );
		}

		/** This section handles the data if a taxonomy is being edited */
		if ( isset( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] ) {
			$this->edit_taxonomy( $_POST['wp_models_taxonomy'] );
		}

	}

	function tax_reorder_enqueue() {
		wp_enqueue_script('jquery-ui-sortable');
	}

	function admin() {

		echo '<div class="wrap">';

			if ( isset( $_REQUEST['view'] ) && 'edit' == $_REQUEST['view'] ) {
				require( dirname( __FILE__ ) . '/views/edit-tax.php' );
			}
			else {
				require( dirname( __FILE__ ) . '/views/create-tax.php' );
			}

		echo '</div>';

	}

	function create_taxonomy( $args = array() ) {

		/**** VERIFY THE NONCE ****/

		/** No empty fields */
		if ( ! isset( $args['id'] ) || empty( $args['id'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_models' ) );
		if ( ! isset( $args['name'] ) || empty( $args['name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_models' ) );
		if ( ! isset( $args['singular_name'] ) || empty( $args['singular_name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_models' ) );

		extract( $args );

		$labels = array(
			'name'					=> strip_tags( $name ),
			'singular_name' 		=> strip_tags( $singular_name ),
			'menu_name'				=> strip_tags( $name ),

			'search_items'			=> sprintf( __( 'Search %s', 'wp_models' ), strip_tags( $name ) ),
			'popular_items'			=> sprintf( __( 'Popular %s', 'wp_models' ), strip_tags( $name ) ),
			'all_items'				=> sprintf( __( 'All %s', 'wp_models' ), strip_tags( $name ) ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'wp_models' ), strip_tags( $singular_name ) ),
			'update_item'			=> sprintf( __( 'Update %s', 'wp_models' ), strip_tags( $singular_name ) ),
			'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_models' ), strip_tags( $singular_name ) ),
			'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_models' ), strip_tags( $singular_name ) ),
			'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_models' ), strip_tags( $name ) ),
			'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_models' ), strip_tags( $name ) )
		);

		$args = array(
			'labels'		=> $labels,
			'hierarchical'	=> true,
			'rewrite'		=> array( 'slug' => $id, 'with_front' => false ),
			'editable'		=> 1
		);

		$tax = array( $id => $args );

		$options = get_option( $this->settings_field );

		/** Update the options */
		update_option( $this->settings_field, wp_parse_args( $tax, $options ) );

		/** Flush rewrite rules */
		$this->register_taxonomies();
		flush_rewrite_rules();

	}

	function delete_taxonomy( $id = '' ) {

		/**** VERIFY THE NONCE ****/

		/** No empty ID */
		if ( ! isset( $id ) || empty( $id ) )
			wp_die( __( "Nice try, partner. But that taxonomy doesn't exist. Click back and try again.", 'wp_models' ) );

		$options = get_option( $this->settings_field );

		/** Look for the ID, delete if it exists */
		if ( array_key_exists( $id, (array) $options ) ) {
			unset( $options[$id] );
		} else {
			wp_die( __( "Nice try, partner. But that taxonomy doesn't exist. Click back and try again.", 'wp_models' ) );
		}

		/** Update the DB */
		update_option( $this->settings_field, $options );

	}

	function edit_taxonomy( $args = array() ) {

		/**** VERIFY THE NONCE ****/

		/** No empty fields */
		if ( ! isset( $args['id'] ) || empty( $args['id'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_models' ) );
		if ( ! isset( $args['name'] ) || empty( $args['name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_models' ) );
		if ( ! isset( $args['singular_name'] ) || empty( $args['singular_name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_models' ) );

		extract( $args );

		$labels = array(
			'name'					=> strip_tags( $name ),
			'singular_name' 		=> strip_tags( $singular_name ),
			'menu_name'				=> strip_tags( $name ),

			'search_items'			=> sprintf( __( 'Search %s', 'wp_models' ), strip_tags( $name ) ),
			'popular_items'			=> sprintf( __( 'Popular %s', 'wp_models' ), strip_tags( $name ) ),
			'all_items'				=> sprintf( __( 'All %s', 'wp_models' ), strip_tags( $name ) ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'wp_models' ), strip_tags( $singular_name ) ),
			'update_item'			=> sprintf( __( 'Update %s', 'wp_models' ), strip_tags( $singular_name ) ),
			'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_models' ), strip_tags( $singular_name ) ),
			'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_models' ), strip_tags( $singular_name ) ),
			'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_models' ), strip_tags( $name ) ),
			'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_models' ), strip_tags( $name ) )
		);

		$args = array(
			'labels'		=> $labels,
			'hierarchical'	=> true,
			'rewrite'		=> array( 'slug' => $id, 'with_front' => false ),
			'editable'		=> 1
		);

		$tax = array( $id => $args );

		$options = get_option( $this->settings_field );

		update_option( $this->settings_field, wp_parse_args( $tax, $options ) );

	}

	function notices() {

		if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != $this->menu_page ) {
			return;
		}

		$format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';

		if ( isset( $_REQUEST['created'] ) && 'true' == $_REQUEST['created'] ) {
			printf( $format, __('New taxonomy successfully created!', 'wp_models') );
			return;
		}

		if ( isset( $_REQUEST['edited'] ) && 'true' == $_REQUEST['edited'] ) {
			printf( $format, __('Taxonomy successfully edited!', 'wp_models') );
			return;
		}

		if ( isset( $_REQUEST['deleted'] ) && 'true' == $_REQUEST['deleted'] ) {
			printf( $format, __('Taxonomy successfully deleted.', 'wp_models') );
			return;
		}

		return;

	}

	/**
	 * Register the status taxonomy, manually.
	 */
	function model_status_taxonomy() {

		$name = __( 'Status', 'wp_models' );
		$singular_name = __( 'Status', 'wp_models' );

		return array(
			'status' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_models' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_models' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_models' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_models' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_models' ), strip_tags( $name ) )
				),
				'hierarchical' => true,
				'rewrite'  => array( __( 'status', 'wp_models' ), 'with_front' => false ),
				'editable' => 0
			)
		);

	}

	/**
	 * Register the property-types taxonomy, manually.
	 */
	function property_type_taxonomy() {

		$name = __( 'Property Types', 'wp_models' );
		$singular_name = __( 'Property Type', 'wp_models' );

		return array(
			'property-types' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_models' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_models' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_models' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_models' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_models' ), strip_tags( $name ) )
				),
				'hierarchical' => true,
				'rewrite'  => array( __( 'property-types', 'wp_models' ), 'with_front' => false ),
				'editable' => 0
			)
		);

	}

	/**
	 * Register the location taxonomy, manually.
	 */
	function model_location_taxonomy() {

		$name = __( 'Locations', 'wp_models' );
		$singular_name = __( 'Location', 'wp_models' );

		return array(
			'locations' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_models' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_models' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_models' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_models' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_models' ), strip_tags( $name ) )
				),
				'hierarchical' => true,
				'rewrite' => array( __( 'locations', 'wp_models' ), 'with_front' => false ),
				'editable' => 0
			)
		);

	}

	/**
	 * Register the property features taxonomy, manually.
	 */
	function property_features_taxonomy() {

		$name = __( 'Features', 'wp_models' );
		$singular_name = __( 'Feature', 'wp_models' );

		return array(
			'features' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_models' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_models' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_models' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_models' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_models' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_models' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_models' ), strip_tags( $name ) )
				),
				'hierarchical' => 0,
				'rewrite' => array( __( 'features', 'wp_models' ),  'with_front' => false ),
				'editable' => 0
			)
		);

	}

	/**
	 * Create the taxonomies.
	 */
	function register_taxonomies() {

		foreach( (array) $this->get_taxonomies() as $id => $data ) {
			register_taxonomy( $id, array( 'model' ), $data );
		}

	}

	/**
	 * Get the taxonomies.
	 */
	function get_taxonomies() {

		return array_merge( $this->model_status_taxonomy(), $this->model_location_taxonomy(), $this->property_type_taxonomy(), $this->property_features_taxonomy(), (array) get_option( $this->settings_field ) );

	}

	/**
	 * Create the terms
	 */
	function create_terms() {

		/** Default terms for status */
		$status_terms = apply_filters( 'wp_models_default_status_terms', array('New' => 'new', 'Featured' => 'featured','Sold' => 'sold', 'Reduced' => 'reduced') );
		foreach ($status_terms as $term => $slug) {
			if (term_exists($term, 'status')) {
				continue;
			}
			wp_insert_term($term,'status',array('slug' => $slug));
		}

		/** Default terms for property-type */
		$property_type_terms = apply_filters( 'wp_models_default_property_type_terms', array('Residential' => 'residential', 'Townhome' => 'townhome', 'Commercial' => 'commercial' ) );
		foreach ($property_type_terms as $term => $slug) {
			if (term_exists($term, 'property-types')) {
				continue;
			}
			wp_insert_term($term,'property-types', array('slug' => $slug));
		}

	}

	/**
	 * Reorder taxonomies
	 */
	function tax_reorder() {
		$wp_models_taxes = get_option('wp_models_taxonomies');

		if($_POST) {
			$new_order = $_POST['wp_models_taxonomy'];
			$wp_models_taxes_reordered = array();
			foreach( $new_order as $tax ) {
				if($wp_models_taxes[$tax])
					$wp_models_taxes_reordered[$tax] = $wp_models_taxes[$tax];	
			}
			$wp_models_taxes = $wp_models_taxes_reordered;
			update_option('wp_models_taxonomies', $wp_models_taxes_reordered);
			
		}?>
	<h2><?php _e( 'Reorder Taxonomies', 'wp_models' ); ?></h2>
	<div id="col-container">
		<div class="updated"><?php _e('Note: This will only allow you to reorder user-created taxonomies. Default taxonomies cannot be reordered (Status, Locations, Property Types, Features).', 'wp_models' ); ?> </div>
		<style>
		#sortable { list-style-type: none; margin: 10px 0 ; padding: 0; }
		#sortable li .item { 
			-moz-border-radius: 6px 6px 6px 6px;
			border: 1px solid #E6E6E6;
			font-weight: bold;
			height: auto;
			line-height: 35px;
			overflow: hidden;
			padding-left: 10px;
			position: relative;
			text-shadow: 0 1px 0 white;
			width: auto;
			word-wrap: break-word;
			cursor: move;
			background: none repeat-x scroll left top #DFDFDF;
			-moz-box-shadow: 2px 2px 3px #888;
			-webkit-box-shadow: 2px 2px 3px #888;
			box-shadow: 2px 2px 3px #888;
		}
		#sortable li span { position: absolute; margin-left: -1.3em; }
		.ui-state-highlight { background: #E6E6E6; border: 1px #666 dashed; }
		.wpmodels-submit { padding: 5px 10px; }
		.wpmodels-submit:hover { background: #eaf2fa; font-weight: bold;}
		</style>
		<script>
		jQuery(function($) {
			$( "#sortable" ).sortable({ placeholder: 'ui-state-highlight', forcePlaceholderSize: true});
			$( "#sortable" ).disableSelection();
		});
		</script>
		<div id="col-left">
		<div class="col-wrap">
	    <p><?php _e('Drag and Drop to reorder', 'wp_models'); ?></p>
		<form method="post">
		<ul id="sortable">
	    	<?php foreach($wp_models_taxes as $wp_models_tax_key => $wp_models_tax_value) { ?>
	        	<li class="ui-state-default">
	            	<div class="item">
						<?php echo $wp_models_tax_value['labels']['name']; ?><input type="hidden" id="wp_models_taxonomy[]" name="wp_models_taxonomy[]" value="<?php echo $wp_models_tax_key; ?>" />
	                </div>
	            </li>
	        <?php } ?>
		</ul>
	    <input class="wpmodels-submit" type="submit" value="Save" />
		</form>
		</div>
		</div><!-- /col-left -->

	</div><!-- /col-container -->
	<?php
	}

}