<?php 
/**
 * Adds shortcode to display models
 */

add_shortcode( 'models', 'wp_models_shortcode' );

function wp_models_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
        'id'       => '',
        'taxonomy' => '',
        'term'     => '',
        'limit'    => '',
        'columns'  => ''
    ), $atts ) );

    /**
     * if limit is empty set to all
     */
    if(!$limit) {
        $limit = -1;
    }

    /**
     * if columns is empty set to 0
     */
    if(!$columns) {
        $columns = 0;
    }

    /*
     * query args based on parameters
     */
    $query_args = array(
        'post_type'       => 'model',
        'posts_per_page'  => $limit
    );

    if($id) {
        $query_args = array(
            'post_type'       => 'model',
            'post__in'        => explode(',', $id)
        );
    }

    if($term && $taxonomy) {
        $query_args = array(
            'post_type'       => 'model',
            'posts_per_page'  => $limit,
            'tax_query'       => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'     => $term
                )
            )
        );
    }

    /*
     * start loop
     */
    global $post;

    $models_array = get_posts( $query_args );

    $count = 0;

    $output = '<div class="wp-models-shortcode">';

    foreach ( $models_array as $post ) : setup_postdata( $post );

        $count = ( $count == $columns ) ? 1 : $count + 1;

        $first_class = ( 1 == $count ) ? 'first' : '';

        $output .= '<div class="model-wrap ' . get_column_class($columns) . ' ' . $first_class . '"><div class="model-widget-thumb"><a href="' . get_permalink() . '" class="model-image-link">' . get_the_post_thumbnail( $post->ID, 'models' ) . '</a>';

        if ( '' != wp_models_get_status() ) {
            $output .= '<span class="model-status ' . strtolower(str_replace(' ', '-', wp_models_get_status())) . '">' . wp_models_get_status() . '</span>';
        }

        $output .= '<div class="model-thumb-meta">';

        if ( '' != get_post_meta( $post->ID, '_model_text', true ) ) {
            $output .= '<span class="model-text">' . get_post_meta( $post->ID, '_model_text', true ) . '</span>';
        } elseif ( '' != wp_models_get_property_types() ) {
            $output .= '<span class="model-property-type">' . wp_models_get_property_types() . '</span>';
        }

        if ( '' != get_post_meta( $post->ID, '_model_price', true ) ) {
            $output .= '<span class="model-price">' . get_post_meta( $post->ID, '_model_price', true ) . '</span>';
        }

        $output .= '</div><!-- .model-thumb-meta --></div><!-- .model-widget-thumb -->';

        /*if ( '' != get_post_meta( $post->ID, '_model_open_house', true ) ) {
            $output .= '<span class="model-open-house">Open House: ' . get_post_meta( $post->ID, '_model_open_house', true ) . '</span>';
        }*/

        $output .= '<div class="model-widget-details"><h3 class="model-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
        /*$output .= '<p class="model-address"><span class="model-address">' . wp_models_get_address() . '</span><br />';
        $output .= '<span class="model-city-state-zip">' . wp_models_get_city() . ', ' . wp_models_get_state() . ' ' . get_post_meta( $post->ID, '_model_zip', true ) . '</span></p>';*/

        if ( '' != get_post_meta( $post->ID, '_model_bedrooms', true ) || '' != get_post_meta( $post->ID, '_model_bathrooms', true ) || '' != get_post_meta( $post->ID, '_model_sqft', true )) {
            $output .= '<ul class="model-beds-baths-sqft"><li class="beds">' . get_post_meta( $post->ID, '_model_bedrooms', true ) . '<span> Beds</span></li> <li class="baths">' . get_post_meta( $post->ID, '_model_bathrooms', true ) . '<span> Baths</span></li> <li class="sqft">' . get_post_meta( $post->ID, '_model_sqft', true ) . '<span> Sq ft</span></li></ul>';
        }

        $output .= '</div><!-- .model-widget-details --></div><!-- .model-wrap -->';

    endforeach;

    $output .= '</div><!-- .wp-models-shortcode -->';

    wp_reset_postdata();

    return $output;
    
}
