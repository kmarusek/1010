<?php
$defaults = array(
	'data_source' => 'manual',
	'pods_source_type' => 'pods_relation'
);
$settings = (object) array_merge( $defaults, (array) $settings );
?>
<div id="fl-builder-settings-section-content_source" class="fl-builder-settings-section">
	<div class="fl-builder-settings-section-header">
		<button class="fl-builder-settings-title">
			<svg class="fl-symbol">
				<use xlink:href="#fl-down-caret"></use>
			</svg>
			<?php _e( 'Content', 'bb-powerpack' ); ?>
		</button>
	</div>

	<div class="fl-builder-settings-section-content">

		<table class="fl-form-table">
		<?php
		$fields = array(
			'data_source' => array(
				'type'    => 'select',
				'label'   => __( 'Source', 'bb-powerpack' ),
				'default' => 'manual',
				'options' => array(
					'manual' => __( 'Manual', 'bb-powerpack' ),
					'post'   => __( 'Post', 'bb-powerpack' ),
				),
				'toggle'  => array(
					'manual' => array(
						'sections' => array( 'items' ),
					),
					'post'   => array(
						'sections' => array( 'post' ),
						'fields' => array( 'posts_per_page' )
					),
				),
			),
		);

		if ( class_exists( 'acf' ) ) {
			$fields['data_source']['options']['acf']          = __( 'ACF Repeater Field', 'bb-powerpack' );
			$fields['data_source']['toggle']['acf']['fields'] = array( 'acf_repeater_name', 'acf_repeater_label', 'acf_repeater_content' );

			$fields['acf_repeater_name']     = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Field Name', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_repeater_label'] = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Sub Field Name (Title)', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_repeater_content']   = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Sub Field Name (Content)', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);

			if ( class_exists( 'FLThemeBuilderLoader' ) ) {
				$fields['data_source']['options']['acf_relationship'] = __( 'ACF Relationship Field', 'bb-powerpack' );
				$fields['data_source']['toggle']['acf_relationship']['fields'] = array( 'acf_relational_type', 'acf_relational_key', 'acf_order', 'acf_order_by' );

				$fields['acf_relational_type'] = array(
					'type'		=> 'select',
					'label'		=> __( 'Type', 'bb-powerpack' ),
					'default'       => 'relationship',
					'options'       => array(
						'relationship'  => __( 'Relationship', 'bb-powerpack' ),
						'user'          => __( 'User', 'bb-powerpack' ),
					),
				);

				$fields['acf_relational_key'] = array(
					'type'          => 'text',
					'label'         => __( 'Key', 'bb-powerpack' ),
				);

				// Order
				$fields['acf_order'] = array(
					'type'    => 'select',
					'label'   => __( 'Order', 'bb-powerpack' ),
					'options' => array(
						'DESC' => __( 'Descending', 'bb-powerpack' ),
						'ASC'  => __( 'Ascending', 'bb-powerpack' ),
					),
				);

				// Order by
				$fields['acf_order_by'] = array(
					'type'    => 'select',
					'label'   => __( 'Order By', 'bb-powerpack' ),
					'default' => 'post__in',
					'options' => array(
						'author'         => __( 'Author', 'bb-powerpack' ),
						'comment_count'  => __( 'Comment Count', 'bb-powerpack' ),
						'date'           => __( 'Date', 'bb-powerpack' ),
						'modified'       => __( 'Date Last Modified', 'bb-powerpack' ),
						'ID'             => __( 'ID', 'bb-powerpack' ),
						'menu_order'     => __( 'Menu Order', 'bb-powerpack' ),
						'meta_value'     => __( 'Meta Value (Alphabetical)', 'bb-powerpack' ),
						'meta_value_num' => __( 'Meta Value (Numeric)', 'bb-powerpack' ),
						'rand'           => __( 'Random', 'bb-powerpack' ),
						'title'          => __( 'Title', 'bb-powerpack' ),
						'name'          => __( 'Slug', 'bb-powerpack' ),
						'post__in'       => __( 'Selection Order', 'bb-powerpack' ),
					),
					'toggle'  => array(
						'meta_value'     => array(
							'fields' => array( 'acf_order_by_meta_key' ),
						),
						'meta_value_num' => array(
							'fields' => array( 'acf_order_by_meta_key' ),
						),
					),
				);

				// Meta Key
				$fields['acf_order_by_meta_key'] = array(
					'type'  => 'text',
					'label' => __( 'Meta Key', 'bb-powerpack' ),
				);
			}
		}
		if ( function_exists( 'acf_add_options_page' ) ) {
			$fields['data_source']['options']['acf_options_page']          = __( 'ACF Option Page', 'bb-powerpack' );
			$fields['data_source']['toggle']['acf_options_page']['fields'] = array( 'acf_options_page_repeater_name', 'acf_options_page_repeater_label', 'acf_options_page_repeater_content' );
			$fields['data_source']['help']                                 = __( 'To make use of the \'ACF Option Page\' feature, you will need ACF PRO (ACF v5), or the options page add-on (ACF v4)', 'bb-powerpack' );

			$fields['acf_options_page_repeater_name']     = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Field Name', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_options_page_repeater_label'] = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Sub Field Name (Title)', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
			$fields['acf_options_page_repeater_content']   = array(
				'type'        => 'text',
				'label'       => __( 'ACF Repeater Sub Field Name (Content)', 'bb-powerpack' ),
				'connections' => array( 'string' ),
			);
		}

		foreach ( $fields as $field_name => $field ) {
			FLBuilder::render_settings_field( $field_name, $field, $settings );
		}
		?>
		</table>
	
	</div>
</div>

<?php do_action( 'pp_module_after_ui_setting_fields', $settings ); ?>

<style>
	.fl-builder-settings:not([data-current-source="acf_relationship"]) #fl-field-acf_relational_type,
	.fl-builder-settings:not([data-current-source="acf_relationship"]) #fl-field-acf_relational_key,
	.fl-builder-settings:not([data-current-source="acf_relationship"]) #fl-field-acf_order,
	.fl-builder-settings:not([data-current-source="acf_relationship"]) #fl-field-acf_order_by,
	.fl-builder-settings option[value="fwp/example"] {
		display: none;
	}
	.fl-builder-settings[data-current-source="pods_relationship"] #fl-builder-settings-section-post {
		display: block !important;
	}
	.fl-builder-settings[data-current-source="pods_relationship"] #fl-field-post_type,
	.fl-builder-settings[data-current-source="pods_relationship"] .fl-custom-query-filter {
		display: none !important;
	}
</style>

<script>
	;( function( $ ) {
		var onSourceChange = function() {
			var $form = $('.fl-builder-settings');

			$form.find( 'select[name="data_source"]' ).on( 'change', function() {
				$form.attr( 'data-current-source', $(this).val() );
			} );
		};

		var initSettings = function() {
			var $form = $('.fl-builder-settings');

			$form.find( 'select[name="data_source"]' ).on( 'change', onSourceChange );

			$form.attr( 'data-current-source', $form.find( 'select[name="data_source"]' ).val() );
		};

		initSettings();
	})( jQuery );
</script>