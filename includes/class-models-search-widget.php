<?php
/**
 * This widget creates a search form which uses models' taxonomy for search fields.
 *
 * @package WP Models
 * @since 0.0.1
 */
class WP_Models_Search_Widget extends WP_Widget {

	function WP_Models_Search_Widget() {
		$widget_ops = array( 'classname' => 'models-search wp-models-search', 'description' => __( 'Display models search dropdown', 'wp_models' ) );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'models-search' );
		$this->WP_Widget( 'models-search', __( 'WP Model Home Inventory - Search', 'wp_models' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'			=> '',
			'button_text'	=> __( 'Search Models', 'wp_models' )
		) );

		global $_wp_models_taxonomies;

		$models_taxonomies = $_wp_models_taxonomies->get_taxonomies();

		extract( $args );

		echo $before_widget;

		if ( $instance['title'] ) echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

		echo '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" ><input type="hidden" value="" name="s" /><input type="hidden" value="model" name="post_type" />';

		foreach ( $models_taxonomies as $tax => $data ) {
			if ( ! isset( $instance[$tax] ) || ! $instance[$tax] )
				continue;

			$terms = get_terms( $tax, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 100, 'hierarchical' => false ) );
			if ( empty( $terms ) )
				continue;

			$current = ! empty( $wp_query->query_vars[$tax] ) ? $wp_query->query_vars[$tax] : '';
			echo "<select name='$tax' id='$tax' class='wp-models-taxonomy'>\n\t";
			echo '<option value="" ' . selected( $current == '', true, false ) . ">{$data['labels']['name']}</option>\n";
			foreach ( (array) $terms as $term )
				echo "\t<option value='{$term->slug}' " . selected( $current, $term->slug, false ) . ">{$term->name}</option>\n";

			echo '</select>';
		}

		echo '<div class="btn-search"><button type="submit" class="searchsubmit"><i class="fa fa-search"></i><span class="button-text">'. esc_attr( $instance['button_text'] ) .'</span></button></div>';
		echo '<div class="clear"></div>
		</form>';

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'			=> '',
			'button_text'	=> __( 'Search Models', 'wp_models' )
		) );

		global $_wp_models_taxonomies;

		$models_taxonomies = $_wp_models_taxonomies->get_taxonomies();
		$new_widget = empty( $instance );

		printf( '<p><label for="%s">%s</label><input type="text" id="%s" name="%s" value="%s" style="%s" /></p>', $this->get_field_id( 'title' ), __( 'Title:', 'wp_models' ), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), esc_attr( $instance['title'] ), 'width: 95%;' );
		?>
		<h5><?php _e( 'Include these taxonomies in the search widget', 'wp_models' ); ?></h5>
		<?php
		foreach ( (array) $models_taxonomies as $tax => $data ) {
			$terms = get_terms( $tax );
			if ( empty( $terms ) )
				continue;
			
			$checked = isset( $instance[ $tax ] ) && $instance[ $tax ];

			printf( '<p><label><input id="%s" type="checkbox" name="%s" value="1" %s />%s</label></p>', $this->get_field_id( 'tax' ), $this->get_field_name( $tax ), checked( 1, $checked, 0 ), esc_html( $data['labels']['name'] ) );

		}

		printf( '<p><label for="%s">%s</label><input type="text" id="%s" name="%s" value="%s" style="%s" /></p>', $this->get_field_id( 'button_text' ), __( 'Button Text:', 'wp_models' ), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), esc_attr( $instance['button_text'] ), 'width: 95%;' );
	}
}