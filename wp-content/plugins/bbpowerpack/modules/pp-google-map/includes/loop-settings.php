
<div class="fl-custom-query fl-loop-data-source" data-source="custom_query">
	<h3 class="fl-builder-settings-title">Content</h3>
	<div id="fl-builder-settings-section-filter" class="fl-builder-settings-section">

		<table class="fl-form-table fl-post-type-filter">
			<?php
				$post_types    = array();
				$taxonomy_type = array();

			foreach ( FLBuilderLoop::post_types() as $slug => $type ) {

				$taxonomies = FLBuilderLoop::taxonomies( $slug );

				$post_types[ $slug ] = $type->label;
				if ( ! empty( $taxonomies ) ) {

					foreach ( $taxonomies as $tax_slug => $tax ) {
						$taxonomy_type[ $slug ][ $tax_slug ] = $tax->label;
					}
				}
			}

			FLBuilder::render_settings_field(
				'post_slug',
				array(
					'type'    => 'select',
					'label'   => __( 'Post Type', 'bb-powerpack' ),
					'options' => $post_types,
					'default' => isset( $settings->post_type ) ? $settings->post_type : 'post',
				)
			);
			?>
		</table>

		<?php

		foreach ( $post_types as $slug => $label ) :
			$selected = isset( $settings->{'posts_' . $slug . '_type'} ) ? $settings->{'posts_' . $slug . '_type'} : 'post';
			?>
			<table class="fl-form-table fl-custom-query-filter fl-custom-query-<?php echo $slug; ?>-filter"<?php echo ( $slug === $selected ) ? ' style="display:table;"' : ''; ?>>
			<?php

			FLBuilder::render_settings_field(
				'posts_' . $slug . '_tax_type',
				array(
					'type'    => 'select',
					'label'   => __( 'Taxonomy', 'bb-powerpack' ),
					'options' => $taxonomy_type[ $slug ],
				)
			);

			foreach ( $taxonomy_type[ $slug ] as $tax_slug => $tax_label ) {

				FLBuilder::render_settings_field(
					'tax_' . $slug . '_' . $tax_slug,
					array(
						'type'     => 'suggest',
						'action'   => 'fl_as_terms',
						'data'     => $tax_slug,
						'label'    => $tax_label,
						/* translators: %s: tax label */
						'help'     => sprintf( __( 'Enter a list of %1$s.', 'bb-powerpack' ), $tax_label ),
						'matching' => true,
					),
					$settings
				);
			}

			?>
			</table>
		<?php endforeach; ?>
		<table class="fl-form-table fl-post-type-other-setting">
			<?php
			FLBuilder::render_settings_field(
				'post_count',
				array(
					'type'    => 'unit',
					'label'   => __( 'Total Number of Posts', 'bb-powerpack' ),
					'default' => '10',
					'slider'  => true,
					'help'    => __( 'Leave Blank or add -1 for all posts.', 'bb-powerpack' ),
				)
			);
			FLBuilder::render_settings_field(
				'post_map_name',
				array(
					'type'        => 'text',
					'label'       => __( 'Location Name', 'bb-powerpack' ),
					'help'        => __( 'Location Name to identify while editing', 'bb-powerpack' ),
					'connections' => array( 'string' ),
				)
			);
			FLBuilder::render_settings_field(
				'post_map_latitude',
				array(
					'type'        => 'text',
					'label'       => __( 'Latitude', 'bb-powerpack' ),
					'connections' => array( 'string' ),
				)
			);
			FLBuilder::render_settings_field(
				'post_map_longitude',
				array(
					'type'        => 'text',
					'label'       => __( 'Longitude', 'bb-powerpack' ),
					'connections' => array( 'string' ),
				)
			);
			FLBuilder::render_settings_field(
				'post_marker_point',
				array(
					'type'    => 'select',
					'label'   => __( 'Marker Point Icon', 'bb-powerpack' ),
					'default' => 'default',
					'options' => array(
						'default' => 'Default',
						'custom'  => 'Custom',
					),
					'toggle'  => array(
						'custom' => array(
							'fields' => array( 'post_marker_img' ),
						),
					),
				)
			);
			FLBuilder::render_settings_field(
				'post_marker_img',
				array(
					'type'        => 'photo',
					'label'       => __( 'Custom Marker', 'bb-powerpack' ),
					'show_remove' => true,
					'connections' => array( 'photo' ),
				)
			);
			FLBuilder::render_settings_field(
				'post_enable_info',
				array(
					'type'    => 'select',
					'label'   => __( 'Show Tooltip', 'bb-powerpack' ),
					'default' => 'yes',
					'options' => array(
						'yes' => __( 'Yes', 'bb-powerpack' ),
						'no'  => __( 'No', 'bb-powerpack' ),
					),
					'toggle'  => array(
						'yes' => array(
							'fields' => array( 'post_info_window_text' ),
						),
					),
				)
			);
			FLBuilder::render_settings_field(
				'post_info_window_text',
				array(
					'type'          => 'editor',
					'label'         => '',
					'default'       => __( 'IdeaBox Creations', 'bb-powerpack' ),
					'media_buttons' => false,
					'connections'   => array( 'string', 'html' ),
				)
			);
			?>
		</table>
	</div>
</div>
