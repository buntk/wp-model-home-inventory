<?php
/**
 * The Template for displaying all single model posts on Organic Theme's Seed Framework
 *
 * @package WP Models
 * @since 0.0.2
 * This version adds properticons
 */

add_action('wp_enqueue_scripts', 'enqueue_single_model_scripts');
function enqueue_single_model_scripts() {
	wp_enqueue_style( 'wp-models-single' );
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_script( 'fitvids', array('jquery'), true, true );
	wp_enqueue_script( 'wp-models-single', array('jquery, jquery-ui-tabs', 'jquery-validate'), true, true );
	wp_enqueue_style( 'properticons' );
}

function single_model_post_content() {

	global $post;

	?>

	<div class="entry-content wpmodels-single-model">

		<div class="model-image-wrap">
			<?php echo get_the_post_thumbnail( $post->ID, 'models-full', array('class' => 'single-model-image') );
			if ( '' != wp_models_get_status() ) {
				printf( '<span class="model-status %s">%s</span>', strtolower(str_replace(' ', '-', wp_models_get_status())), wp_models_get_status() );
			}
			/*if ( '' != get_post_meta( $post->ID, '_model_open_house', true ) ) {
				printf( '<span class="model-open-house">Open House: %s</span>', get_post_meta( $post->ID, '_model_open_house', true ) );
			}*/ ?>
		</div><!-- .model-image-wrap -->

		<?php
		$model_meta = sprintf( '<ul class="model-meta">');

		if ( '' != get_post_meta( $post->ID, '_model_price', true ) ) {
			$model_meta .= sprintf( '<li class="model-price">%s</li>', get_post_meta( $post->ID, '_model_price', true ) );
		}

		if ( '' != wp_models_get_property_types() ) {
			$model_meta .= sprintf( '<li class="model-property-type"><span class="label">Type: </span>%s</li>', get_the_term_list( get_the_ID(), 'property-types', '', ', ', '' ) );
		}

		if ( '' != wp_models_get_locations() ) {
			$model_meta .= sprintf( '<li class="model-location"><span class="label">Location: </span>%s</li>', get_the_term_list( get_the_ID(), 'locations', '', ', ', '' ) );
		}
		
		/*
		if ( '' != wp_models_get_community_name() ) {
			$model_meta .= sprintf( '<li class="model-community-name"><span class="label">Community: </span>%s</li>', get_the_term_list( get_the_ID(), 'community-names', '', ', ', '' ) );
		}*/

		$model_meta .= sprintf( '</ul>');

		echo $model_meta;
		?>
        <?php

		$model_meta2 = sprintf( '<ul class="model-meta model-meta2">');

		if ( '' != get_post_meta( $post->ID, '_model_bedrooms', true ) ) {
			$model_meta2 .= sprintf( '<li class="model-bedrooms"><span class="label">Beds: </span>%s</li>', get_post_meta( $post->ID, '_model_bedrooms', true ) );
		}

		if ( '' != get_post_meta( $post->ID, '_model_bathrooms', true ) ) {
			$model_meta2 .= sprintf( '<li class="model-bathrooms"><span class="label">Baths: </span>%s</li>', get_post_meta( $post->ID, '_model_bathrooms', true ) );
		}

		if ( '' != get_post_meta( $post->ID, '_model_sqft', true ) ) {
			$model_meta2 .= sprintf( '<li class="model-sqft"><span class="label">Sq Ft: </span>%s</li>', get_post_meta( $post->ID, '_model_sqft', true ) );
		}

		if ( '' != get_post_meta( $post->ID, '_model_lot_sqft', true ) ) {
			$model_meta2 .= sprintf( '<li class="model-lot-sqft"><span class="label">Series: </span>%s</li>', get_post_meta( $post->ID, '_model_lot_sqft', true ) );
		}

		$model_meta2 .= sprintf( '</ul>');

		echo $model_meta2;

		?>

		<div id="model-tabs" class="model-data">

			<ul>
				<li><a href="#model-description">Description</a></li>

				<!--<li><a href="#model-details">Details</a></li>-->


				<?php if (get_post_meta( $post->ID, '_model_gallery', true) != '') { ?>
					<li><a href="#model-gallery">Photos</a></li>
				<?php } ?>

				<?php if (get_post_meta( $post->ID, '_model_video', true) != '') { ?>
					<li><a href="#model-video">Video / Virtual Tour</a></li>
				<?php } ?>

				<?php /*if (get_post_meta( $post->ID, '_model_school_neighborhood', true) != '') { ?>
				<li><a href="#model-school-neighborhood">Schools &amp; Neighborhood</a></li>
				<?php } */ ?>
                
                <?php if (get_post_meta( $post->ID, '_model_quick_closing', true) != '') { ?>
				<li><a href="#model-details">Quick Closing</a></li>
				<?php } ?>
			</ul>

			<div id="model-description">
				<?php the_content( __( 'View more <span class="meta-nav">&rarr;</span>', 'wp_models' ) ); ?>
			</div><!-- #model-description -->

			<!--<div id="model-details">
				<?php
					/*$details_instance = new WP_Models();

					$pattern = '<tr class="wp_models%s"><td class="label">%s</td><td>%s</td></tr>';

					echo '<table class="model-details">';

					echo '<tbody class="left">';
					foreach ( (array) $details_instance->property_details['col1'] as $label => $key ) {
						$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
						if (! empty( $detail_value ) ) :
							printf( $pattern, $key, esc_html( $label ), $detail_value );
						endif;
					}
					echo '</tbody>';

					echo '<tbody class="right">';
					foreach ( (array) $details_instance->property_details['col2'] as $label => $key ) {
						$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
						if (! empty( $detail_value ) ) :
							printf( $pattern, $key, esc_html( $label ), $detail_value );
						endif;
					}
					echo '</tbody>';

					echo '</table>';*/
				//Remove this next bit because we won't show the data fields in the editor
				/*
				if ( get_post_meta( $post->ID, '_model_home_sum', true) != '' || get_post_meta( $post->ID, '_model_kitchen_sum', true) != '' || get_post_meta( $post->ID, '_model_living_room', true) != '' || get_post_meta( $post->ID, '_model_master_suite', true) != '' || get_post_meta( $post->ID, '_quick_closing', true) != '') { ?>
					<div class="additional-features">
						<h4>Additional Features</h4>
						<h6 class="label"><?php _e("Home Summary", 'wp_models'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_model_home_sum', true)); ?></p>
						<h6 class="label"><?php _e("Kitchen Summary", 'wp_models'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_model_kitchen_sum', true)); ?></p>
						<h6 class="label"><?php _e("Living Room", 'wp_models'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_model_living_room', true)); ?></p>
						<h6 class="label"><?php _e("Master Suite", 'wp_models'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_model_master_suite', true)); ?></p>
					</div><!-- .additional-features -->
				<?php
				} */ ?>				

			<!--     </div><!-- #model-details -->

			<?php if (get_post_meta( $post->ID, '_model_gallery', true) != '') { ?>
			<div id="model-gallery">
				<?php echo do_shortcode(get_post_meta( $post->ID, '_model_gallery', true)); ?>
			</div><!-- #model-gallery -->
			<?php } ?>

			<?php if (get_post_meta( $post->ID, '_model_video', true) != '') { ?>
			<div id="model-video">
				<div class="iframe-wrap">
				<?php echo get_post_meta( $post->ID, '_model_video', true); ?>
				</div>
			</div><!-- #model-video -->
			<?php } ?>

			<?php /*if (get_post_meta( $post->ID, '_model_school_neighborhood', true) != '') { ?>
			<div id="model-school-neighborhood">
				<p>
				<?php echo do_shortcode(get_post_meta( $post->ID, '_model_school_neighborhood', true)); ?>
				</p>
			</div><!-- #model-school-neighborhood -->
			<?php } */ ?>
            
            <?php if (get_post_meta( $post->ID, '_model_quick_closing', true) != '') { ?>
			<!--need new CSS for and ID #model-quick-closing for this content-->
            <div id="model-details">
				<?php 
					echo '<ul class="tagged-features">';
					echo get_the_term_list( get_the_ID(), 'features', '<li>', '</li><li>', '</li>' );
					echo '</ul><!-- .tagged-features -->';
					echo do_shortcode(get_post_meta( $post->ID, '_model_quick_closing', true)); 
					?>
			</div><!-- #model-quick-closing -->
			<?php } ?>

		</div><!-- #model-tabs.model-data -->

		<?php
			if (get_post_meta( $post->ID, '_model_map', true) != '') {
			echo '<div id="model-map"><h3>Location Map</h3>';
			echo do_shortcode(get_post_meta( $post->ID, '_model_map', true) );
			echo '</div><!-- .model-map -->';
			}
		?>

	</div><!-- .entry-content -->

<?php
}

get_header(); ?>
        
<!-- BEGIN .post class -->
<div <?php post_class(); ?> id="page-<?php the_ID(); ?>">

	<!-- BEGIN .row -->
	<div class="row">
	
		<!-- BEGIN .content -->
		<div class="content<?php if ( empty( $thumb ) ) { ?> no-thumb<?php } ?>">

			<!-- BEGIN .eleven columns -->
			<div class="eleven columns">
	
				<!-- BEGIN .postarea -->
				<div class="postarea clearfix">

<!--loop-->
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						<div class="entry-meta">
							<?php
								if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wp_models' ), __( '1 Comment', 'wp_models' ), __( '% Comments', 'wp_models' ) ); ?></span>
							<?php
								endif;

								edit_post_link( __( 'Edit', 'wp_models' ), '<span class="edit-link">', '</span>' );
							?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->

					
				<?php single_model_post_content(); ?>

				</article><!-- #post-ID -->

			<?php 
				// Previous/next post navigation.
				wp_models_post_nav();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				endwhile;
			?>
<!--end loop-->

				<!-- END .postarea -->
				</div>
			
			<!-- END .eleven columns -->
			</div>
			
			<!-- BEGIN .five columns -->
			<div class="five columns">
			
				<?php get_sidebar(); ?>
				
			<!-- END .five columns -->
			</div>
            
		<!-- END .content -->
		</div>
	
	<!-- END .row -->
	</div>
	
<!-- END .post class -->
</div>

<?php
get_footer();