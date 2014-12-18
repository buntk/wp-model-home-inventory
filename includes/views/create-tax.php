<?php screen_icon( 'themes' ); ?>
<h2><?php _e( 'Model Taxonomies', 'wp_models' ); ?></h2>

<div id="col-container">

	<div id="col-right">
	<div class="col-wrap">

		<h3><?php _e( 'Current Model Taxonomies', 'wp_models' ); ?></h3>
		<table class="widefat tag fixed" cellspacing="0">
			<thead>
			<tr>
			<th scope="col" class="manage-column column-slug"><?php _e( 'ID', 'wp_models' ); ?></th>
			<th scope="col" class="manage-column column-singular-name"><?php _e( 'Singular Name', 'wp_models' ); ?></th>
			<th scope="col" class="manage-column column-plural-name"><?php _e( 'Plural Name', 'wp_models' ); ?></th>
			</tr>
			</thead>

			<tfoot>
			<tr>
			<th scope="col" class="manage-column column-slug"><?php _e( 'ID', 'wp_models' ); ?></th>
			<th scope="col" class="manage-column column-singular-name"><?php _e( 'Singular Name', 'wp_models'); ?></th>
			<th scope="col" class="manage-column column-plural-name"><?php _e( 'Plural Name', 'wp_models'); ?></th>
			</tr>
			</tfoot>

			<tbody id="the-list" class="list:tag">

				<?php
				$alt = true;

				$model_taxonomies = array_merge( $this->property_features_taxonomy(), $this->model_status_taxonomy(), $this->property_type_taxonomy(), $this->model_location_taxonomy(), get_option( $this->settings_field ) );

				foreach ( (array) $model_taxonomies as $id => $data ) :
				?>

				<tr <?php if ( $alt ) { echo 'class="alternate"'; $alt = false; } else { $alt = true; } ?>>
					<td class="slug column-slug">

					<?php if ( isset( $data['editable'] ) && 0 === $data['editable'] ) : ?>
						<?php echo '<strong>' . esc_html( $id ) . '</strong><br /><br />'; ?>
					<?php else : ?>
						<?php printf( '<a class="row-title" href="%s" title="Edit %s">%s</a>', admin_url( 'admin.php?page=' . $this->menu_page . '&amp;action=edit&amp;id=' . esc_html( $id ) ), esc_html( $id ), esc_html( $id ) ); ?>

						<br />

						<div class="row-actions">
							<span class="edit"><a href="<?php echo admin_url( 'admin.php?page=' . $this->menu_page . '&amp;view=edit&amp;id=' . esc_html( $id ) ); ?>"><?php _e( 'Edit', 'wp_models' ); ?></a> | </span>
							<span class="delete"><a class="delete-tag" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=' . $this->menu_page . '&amp;action=delete&amp;id=' . esc_html( $id ) ), 'wp_models-action_delete-taxonomy' ); ?>"><?php _e( 'Delete', 'wp_models' ); ?></a></span>
						</div>
					<?php endif; ?>

					</td>
					<td class="singular-name column-singular-name"><?php echo esc_html( $data['labels']['singular_name'] )?></td>
					<td class="plural-name column-plural-name"><?php echo esc_html( $data['labels']['name'] )?></td>
				</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	</div>
	</div><!-- /col-right -->

	<div id="col-left">
	<div class="col-wrap">

		<div class="form-wrap">
			<h3><?php _e( 'Add New Model Taxonomy', 'wp_models' ); ?></h3>

			<form method="post" action="<?php echo admin_url( 'admin.php?page=register-taxonomies&amp;action=create' ); ?>">
			<?php wp_nonce_field( 'wp_models-action_create-taxonomy' ); ?>

			<div class="form-field">
				<label for="taxonomy-id"><?php _e( 'ID', 'wp_models' ); ?></label>
				<input name="wp_models_taxonomy[id]" id="taxonomy-id" type="text" value="" size="40" />
				<p><?php _e( 'The unique ID is used to register the taxonomy.<br />(no spaces, underscores, or special characters)', 'wp_models' ); ?></p>
			</div>

			<div class="form-field form-required">
				<label for="taxonomy-name"><?php _e( 'Plural Name', 'wp_models' ); ?></label>
				<input name="wp_models_taxonomy[name]" id="taxonomy-name" type="text" value="" size="40" />
				<p><?php _e( 'Example: "Property Types" or "Locations"', 'wp_models' ); ?></p>
			</div>

			<div class="form-field form-required">
				<label for="taxonomy-singular-name"><?php _e( 'Singular Name', 'wp_models' ); ?></label>
				<input name="wp_models_taxonomy[singular_name]" id="taxonomy-singular-name" type="text" value="" size="40" />
				<p><?php _e( 'Example: "Property Type" or "Location"', 'wp_models' ); ?></p>
			</div>

			<?php submit_button( __( 'Add New Taxonomy', 'wp_models' ), 'secondary' ); ?>
			</form>
		</div>

	</div>
	</div><!-- /col-left -->

</div><!-- /col-container -->