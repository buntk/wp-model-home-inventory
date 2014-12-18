<?php
/**
 * The template for displaying Model Archive pages on Organic Theme's Seed Framework
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP Models
 * @since 0.0.1
 */
get_header();
function archive_model_loop() {

		global $post;

		$count = 0; // start counter at 0

		// Start the Loop.
		while ( have_posts() ) : the_post();

			$count++; // add 1 to counter on each loop
			$first = ($count == 1) ? 'first' : ''; // if counter is 1 add class of first
			
			//Thumbnail image with overlays
			$loop = sprintf( '<div class="model-widget-thumb"><a href="%s" class="model-image-link">%s</a>', get_permalink(), get_the_post_thumbnail( $post->ID, 'models' ) );
			//show status if not null
			if ( '' != wp_models_get_status() ) {
				$loop .= sprintf( '<span class="model-status %s">%s</span>', strtolower(str_replace(' ', '-', wp_models_get_status())), wp_models_get_status() );
			}
			$loop .= sprintf( '<div class="model-thumb-meta">' );
			//show custom text, if null then show property type
			if ( '' != get_post_meta( $post->ID, '_model_text', true ) ) {
				$loop .= sprintf( '<span class="model-text">%s</span>', get_post_meta( $post->ID, '_model_text', true ) );
			} elseif ( '' != wp_models_get_property_types() ) {
				$loop .= sprintf( '<span class="model-property-type">%s</span>', wp_models_get_property_types() );
			}
			//show model price if not null
			if ( '' != get_post_meta( $post->ID, '_model_price', true ) ) {
				$loop .= sprintf( '<span class="model-price">%s</span>', get_post_meta( $post->ID, '_model_price', true ) );
			}
			$loop .= sprintf( '</div><!-- .model-thumb-meta --></div><!-- .model-widget-thumb -->' );
			
			/*if ( '' != get_post_meta( $post->ID, '_model_open_house', true ) ) {
				$loop .= sprintf( '<span class="model-open-house">Open House: %s</span>', get_post_meta( $post->ID, '_model_open_house', true ) );
			}*/
			
			//Model Details for Styling
			$loop .= sprintf( '<div class="model-widget-details"><h3 class="model-title"><a href="%s">%s</a></h3>', get_permalink(), get_the_title() );
			/*$loop .= sprintf( '<p class="model-address"><span class="model-address">%s</span><br />', wp_models_get_address() );
			$loop .= sprintf( '<span class="model-city-state-zip">%s, %s %s</span></p>', wp_models_get_city(), wp_models_get_state(), get_post_meta( $post->ID, '_model_zip', true ) );*/
			if ( '' != get_post_meta( $post->ID, '_model_bedrooms', true ) || '' != get_post_meta( $post->ID, '_model_bathrooms', true ) || '' != get_post_meta( $post->ID, '_model_sqft', true )) {
				$loop .= sprintf( '<ul class="model-beds-baths-sqft"><li class="beds">%s<span> Beds</span></li> <li class="baths">%s<span> Baths</span></li> <li class="sqft">%s<span> Sq. Ft.</span></li></ul>', get_post_meta( $post->ID, '_model_bedrooms', true ), get_post_meta( $post->ID, '_model_bathrooms', true ), get_post_meta( $post->ID, '_model_sqft', true ) );
			}
			$loop .= sprintf('</div><!-- .model-widget-details -->');
			
			//More Button
			$loop .= sprintf( '<a href="%s" class="button btn-primary more-link">%s</a>', get_permalink(), __( 'View Model', 'wp_models' ) );

			/** wrap in div with column class, and output **/
			printf( '<article id="post-%s" class="model entry one-third %s"><div class="model-wrap">%s</div><!-- .model-wrap --></article><!-- article#post-## -->', get_the_id(), $first, apply_filters( 'wp_models_featured_models_widget_loop', $loop ) );

			if ( 3 == $count ) { // if counter is 3, reset to 0
				$count = 0;
			}
		
		endwhile;
		wp_models_paging_nav();
} ?>
   
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
 
			<?php archive_model_loop(); ?>
    
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