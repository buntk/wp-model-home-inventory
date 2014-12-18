<?php
/**
 * Allows listing post type to use custom templates for single listings
 * Adapted from Single Post Template plugin by Nathan Rice (http://www.nathanrice.net/)
 * http://wordpress.org/plugins/single-post-template/
 *
 * Author: Nathan Rice
 * Author URI: http://www.nathanrice.net/
 * License: GNU General Public License v2.0
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 *
 * @package WP Models
 * @since 0.0.1
 */

class Single_Model_Template {

	function __construct() {
/* For the project I am working to customize this plugin I don't want to create a custom single-model-custom.php file, so I don't want the metabox displaying on the post edit page.
		//add_action( 'admin_menu', array( $this, 'wpmodels_add_metabox' ) );
*/
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

	}

	function get_model_templates() {

		$templates = wp_get_theme()->get_files( 'php', 1 );
		$model_templates = array();

		$base = array( trailingslashit( get_template_directory() ), trailingslashit( get_stylesheet_directory() ) );

		foreach ( (array) $templates as $file => $full_path ) {

			if ( ! preg_match( '|Single Model Template:(.*)$|mi', file_get_contents( $full_path ), $header ) )
				continue;

			$model_templates[ $file ] = _cleanup_header_comment( $header[1] );

		}

		return $model_templates;

	}

	function model_templates_dropdown() {

		global $post;

		$model_templates = $this->get_model_templates();

		/** Loop through templates, make them options */
		foreach ( (array) $model_templates as $template_file => $template_name ) {
			$selected = ( $template_file == get_post_meta( $post->ID, '_wp_post_template', true ) ) ? ' selected="selected"' : '';
			$opt = '<option value="' . esc_attr( $template_file ) . '"' . $selected . '>' . esc_html( $template_name ) . '</option>';
			echo $opt;
		}

	}

	function wpmodels_add_metabox( $post ) {
		add_meta_box( 'wpmodels_model_templates', __( 'Single Model Template', 'wpmodels' ), array( $this, 'model_template_metabox' ), 'model', 'side', 'high' );
	}

	function model_template_metabox( $post ) {

		?>
		<input type="hidden" name="wpmodels_single_noncename" id="wpmodels_single_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<label class="hidden" for="model_template"><?php  _e( 'Model Template', 'wp_models' ); ?></label><br />
		<select name="_wp_post_template" id="model_template" class="dropdown">
			<option value=""><?php _e( 'Default', 'wp_models' ); ?></option>
			<?php $this->model_templates_dropdown(); ?>
		</select><br /><br />
		<p><?php _e( 'You can use custom templates for single models that might have additional features or custom layouts by adding them to your theme directory. If so, you will see them above.', 'wp_models' ); ?></p>
		<?php

	}

	function metabox_save( $post_id, $post ) {

		/*
		 * Verify this came from our screen and with proper authorization,
		 * because save_post can be triggered at other times
		 */
		if ( !isset( $_POST['wpmodels_single_noncename'] ) || ! wp_verify_nonce( $_POST['wpmodels_single_noncename'], plugin_basename( __FILE__ ) ) )
			return $post->ID;

		/** Is the user allowed to edit the post or page? */
		if ( 'model' == $_POST['post_type'] )
			if ( ! current_user_can( 'edit_page', $post->ID ) )
				return $post->ID;
		else
			if ( ! current_user_can( 'edit_post', $post->ID ) )
				return $post->ID;

		/** OK, we're authenticated: we need to find and save the data */

		/** Put the data into an array to make it easier to loop though and save */
		$mydata['_wp_post_template'] = $_POST['_wp_post_template'];

		/** Add values of $mydata as custom fields */
		foreach ( $mydata as $key => $value ) {
			/** Don't store custom data twice */
			if( 'revision' == $post->post_type )
				return;

			/** If $value is an array, make it a CSV (unlikely) */
			$value = implode( ',', (array) $value );

			/** Update the data if it exists, or add it if it doesn't */
			if( get_post_meta( $post->ID, $key, false ) )
				update_post_meta( $post->ID, $key, $value );
			else
				add_post_meta( $post->ID, $key, $value );

			/** Delete if blank */
			if( ! $value )
				delete_post_meta( $post->ID, $key );
		}

	}

}