<div id="icon-options-general" class="icon32"></div>
<div class="wrap">
	<h2>WP Model Home Inventory Settings</h2>
	<hr>
	<div id="poststuff" class="metabox-holder has-right-sidebar">
		<div id="side-info-column" class="inner-sidebar">
		<?php do_meta_boxes('wp-models-options', 'side', null); ?>
		</div>

        <div id="post-body">
            <div id="post-body-content" class="has-sidebar-content">

            	<?php $options = get_option('plugin_wp_models_settings');

            	if ( !isset($options['wp_models_stylesheet_load']) ) {
					$options['wp_models_stylesheet_load'] = 0;
				}
				if ( !isset($options['wp_models_widgets_stylesheet_load']) ) {
					$options['wp_models_widgets_stylesheet_load'] = 0;
				}
				if ( !isset($options['wp_models_default_state']) ) {
					$options['wp_models_default_state'] = '';
				}
				if ( !isset($options['wp_models_archive_posts_num']) ) {
					$options['wp_models_archive_posts_num'] = 9;
				}
				if ( !isset($options['wp_models_slug']) ) {
					$options['wp_models_slug'] = 'models';
				}

            	?>

            	<h2>Include CSS?</h2>
				<p>Here you can deregister the WP Model Home Inventory CSS files and use your theme's CSS file for ease of customization</p>
				<?php
				if ($options['wp_models_stylesheet_load'] == 1)
					echo '<p style="color:red; font-weight: bold;">The plugin\'s main stylesheet (wp-models.css) has been deregistered<p>';
				if ($options['wp_models_widgets_stylesheet_load'] == 1)
					echo '<p style="color:red; font-weight: bold;">The plugin\'s widget stylesheet (wp-models-widgets.css) has been deregistered<p>';
				?>
				<form action="options.php" method="post" id="wp-models-stylesheet-options-form">
					<?php settings_fields('wp_models_options'); ?>
					<?php echo '<h4><input name="plugin_wp_models_settings[wp_models_stylesheet_load]" id="wp_models_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_models_stylesheet_load'], false ) . ' /> Deregister WP Model Home Inventory\'s main CSS (wp-models.css)?</h4>'; ?>

					<?php echo '<h4><input name="plugin_wp_models_settings[wp_models_widgets_stylesheet_load]" id="wp_models_widgets_stylesheet_load" type="checkbox" value="1" class="code" ' . checked(1, $options['wp_models_widgets_stylesheet_load'], false ) . ' /> Deregister WP Model Home Inventory\'s widgets CSS (wp-models-widgets.css)?</h4><hr>'; ?>

					<?php
					_e("<h2>Default Province or State</h2><p>You can enter a default province or state that will automatically be output on template pages and widgets set to display this value. When you create a model and leave the prov/state field empty, the default entered below will be shown. You can override the default on each model by entering a value into the prov/state field.</p>", 'wp_models' );
				    echo '<h4>Default Province/State: <input name="plugin_wp_models_settings[wp_models_default_state]" id="wp_models_default_state" type="text" value="' . $options['wp_models_default_state'] . '" size="1" /></h4><hr>';
					?>

					<?php
					_e("<h2>Default Number of Posts</h2><p>The default number of posts displayed on a model archive page is 9. Here you can set a custom number. Enter <span style='color: #f00;font-weight: 700;'>-1</span> to display all model posts.<br /><em>If you have more than 20-30 posts, it's not recommended to show all or your page will load slow.</em></p>", 'wp_models' );
				    echo '<h4>Number of posts on model archive page: <input name="plugin_wp_models_settings[wp_models_archive_posts_num]" id="wp_models_archive_posts_num" type="text" value="' . $options['wp_models_archive_posts_num'] . '" size="1" /></h4>';
					?>
					<br />

					<?php echo '<h4>Listings post type slug (leave as default or change as needed): <input type="text" name="plugin_wp_models_settings[wp_models_slug]" value="' . $options['wp_models_slug'] . '" /></h4>'; ?>
					<p>Don't forget to <a href="../wp-admin/options-permalink.php">reset your permalinks</a> if you change the slug.</p>
					<input name="submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Settings'); ?>" />
				</form>
            </div>
        </div>
    </div>
</div>