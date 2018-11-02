<?php
/**
 *  UABB Table Module file
 *
 *  @package UABB Table Module
 */

/**
 * Function that initializes UABB Table Module
 *
 * @class UABBTable
 */
class UABBTable extends FLBuilderModule {

	/**
	 * Constructor function that constructs default values for the Table module
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Table', 'uabb' ),
				'description'     => __( 'A simple Table.', 'uabb' ),
				'category'        => BB_Ultimate_Addon_Helper::module_cat( BB_Ultimate_Addon_Helper::$content_modules ),
				'group'           => UABB_CAT,
				'dir'             => BB_ULTIMATE_ADDON_DIR . 'modules/uabb-table/',
				'url'             => BB_ULTIMATE_ADDON_URL . 'modules/uabb-table/',
				'partial_refresh' => true,
				'icon'            => 'uabb-table.svg',
			)
		);

		add_filter( 'wp_handle_upload_prefilter', array( $this, 'uabb_csv_file_handle' ), 10, 1 );
	}
	/**
	 * Function to check CSV file extension in uabb-file type
	 *
	 * @since 1.12.0
	 * @method uabb_csv_file_handle
	 * @param string $file fetches the file.
	 */
	public function uabb_csv_file_handle( $file ) {

		if ( ! function_exists( 'get_current_screen()' ) ) {
			return $file;
		} else {
			if ( 'async-upload' === get_current_screen()->base ) {
				$type = isset( $_POST['uabb_upload_type'] ) ? $_POST['uabb_upload_type'] : false;

				$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

				$regex['uabb-file'] = '#(csv)#i';

				if ( 'uabb-file' === $type ) {
					if ( ! preg_match( $regex[ $type ], $ext ) ) {
						$file['error'] = sprintf( __( 'The uploaded file is not a valid CSV extension.', 'uabb' ), $type );
					}
				}
				return $file;
			}
		}
	}

	/**
	 * Function to get the icon for the Table
	 *
	 * @since 1.12.0
	 * @method get_icons
	 * @param string $icon gets the icon for the module.
	 */
	public function get_icon( $icon = '' ) {

		// check if $icon is referencing an included icon.
		if ( '' != $icon && file_exists( BB_ULTIMATE_ADDON_DIR . 'modules/uabb-table/icon/' . $icon ) ) {
			$path = BB_ULTIMATE_ADDON_DIR . 'modules/uabb-table/icon/' . $icon;
		}

		if ( file_exists( $path ) ) {
			$remove_icon = apply_filters( 'uabb_remove_svg_icon', false, 10, 1 );
			if ( true === $remove_icon ) {
				return;
			} else {
				return file_get_contents( $path );
			}
		} else {
			return '';
		}
	}

	/**
	 * Parse table CSV file.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.12.0
	 * @access public
	 */
	public function parse_csv_file() {

		$csv_file_src = wp_get_attachment_url( $this->settings->file_src );

		if ( isset( $csv_file_src ) && '.csv' !== substr( $csv_file_src, -4 ) ) {

			$html = __( 'Please provide a valid CSV file.', 'uabb' );
			return $html;
		}

		$row        = array();
		$rows_count = array();
		$upload_dir = wp_upload_dir();
		$file_url   = str_replace( $upload_dir['baseurl'], '', $csv_file_src );

		$file = $upload_dir['basedir'] . $file_url;

		// Attempt to change permissions if not readable.
		if ( ! is_readable( $file ) ) {
			chmod( $file, 0744 );
		}

		// Check if file is writable, then open it in 'read only' mode.
		if ( is_readable( $file ) ) {

			$_file = fopen( $file, 'r' );

			if ( ! $_file ) {

				$html = __( "File could not be opened. Check the file's permissions to make sure it's readable by your server.", 'uabb' );

				return $html;
			}

			// To sum this part up, all it really does is go row by row.
			// Column by column, saving all the data.
			$file_data = array();

			// Get first row in CSV, which is of course the headers.
			$header = fgetcsv( $_file );

			while ( $row = fgetcsv( $_file ) ) {

				foreach ( $header as $i => $key ) {
					$file_data[ $key ] = $row[ $i ];
				}

				$data[] = $file_data;
			}

			fclose( $_file );

		} else {

			$html = __( "File could not be opened. Check the file's permissions to make sure it's readable by your server.", 'uabb' );

			return $html;
		}

		if ( is_array( $data ) ) {

			foreach ( $data as $key => $value ) {
				$rows[ $key ]       = $value;
				$rows_count[ $key ] = count( $value );
			}
		}

		$header_val = array_keys( $rows[0] );

		ob_start(); ?>

		<?php
		$counter           = 1;
		$cell_counter      = 0;
		$cell_inline_count = 0;
		$total_rows        = 0;
		?>

		<?php foreach ( $rows as $row_key => $row ) { ?>
			<tr>
				<?php $total_rows = $total_rows + 1; ?>
			</tr>
		<?php } ?>

		<div class="table-data">
			<?php if ( 'yes' === $this->settings->show_entries ) : ?>
				<div class="entries-wrapper">
					<label class="lbl-entries"><?php echo $this->settings->show_entries_label; ?> </label>
					<select class="select-filter">	
						<option class="filter-entry"><?php echo $this->settings->show_entries_all_label; ?></option>
						<?php for ( $cnt = 1; $cnt < $total_rows; $cnt++ ) { ?>
							<option class="filter-entry"> <?php echo $cnt; ?> </option>
						<?php } ?>									
					</select>
				</div>
			<?php endif; ?>	

			<?php if ( 'yes' === $this->settings->show_search ) : ?>
				<div class="search-wrapper">
					<input class="search-input" type="text" placeholder="<?php echo $this->settings->search_label; ?>" name="toSearch" id="searchHere"/>
				</div>
			<?php endif ?>
		</div>
		<div class="uabb-table-module-content uabb-table">
			<div class="uabb-table-element-box">
				<div class="uabb-table-wrapper">
					<div class="uabb-table"> 
						<table class="uabb-table-inner-wrap">
							<thead class="uabb-table-header">
								<?php
								if ( $header_val ) {
									?>
									<tr class="table-header-tr">
									<?php
									foreach ( $header_val as $hkey => $head ) {
										?>
										<th class="table-header-th table-heading-<?php echo $hkey; ?>"> 
											<label class="th-style"> <?php echo $head; ?> </label>
											<?php if ( 'yes' === $this->settings->show_sort ) { ?>
													<i class="uabb-sort-icon fa fa-sort"> </i>
												<?php } ?>
										</th> 
										<?php
									}
								}
								?>
							</thead>
							<tbody class="uabb-table-features">
								<?php
									$counter           = 1;
									$cell_counter      = 0;
									$cell_inline_count = 0;
								?>
								<?php foreach ( $rows as $row_key => $row ) { ?>
									<tr class="tbody-row">
										<?php foreach ( $row as $bkey => $col ) { ?>
											<td class="table-body-td">
												<span class="content-text"> <?php echo $col; ?> </span>
											</td>
											<?php
												$cell_counter++;
												$counter++;
												$cell_inline_count++;
											?>
										<?php } ?>
									</tr>
								<?php } ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
		$html   = ob_get_clean();
		$return = $html;
		return $return;
	}

	/**
	 * Render table CSV file output.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.12.0
	 * @access public
	 */
	public function render() {
		$output = $this->parse_csv_file();
		echo $output;
	}

	/**
	 * Get help descriptions.
	 *
	 * @since 1.14.0
	 * @access public
	 * @param string $field get the field name.
	 */
	public static function get_description( $field ) {

		$branding_name       = BB_Ultimate_Addon_Helper::get_builder_uabb_branding( 'uabb-plugin-name' );
		$branding_short_name = BB_Ultimate_Addon_Helper::get_builder_uabb_branding( 'uabb-plugin-short-name' );

		if ( empty( $branding_name ) && empty( $branding_short_name ) ) {

			if ( 'head_row_span' === $field ) {

				$heading_rowspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of rows this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple rows to see the result. Click <a href="https://www.ultimatebeaver.com/docs/how-to-merge-columns-and-rows-in-table/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=table-module" target="_blank" rel="noopener"> <strong> here</strong> </a> to read article for more. </div>', 'uabb' )
				);

				return $heading_rowspan_description;
			}

			if ( 'head_col_span' === $field ) {

				$heading_colspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of columns this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple column cells to see the result. Click <a href="https://www.ultimatebeaver.com/docs/how-to-merge-columns-and-rows-in-table/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=table-module" target="_blank" rel="noopener"> <strong> here</strong> </a> to read article for more. </div>', 'uabb' )
				);

				return $heading_colspan_description;
			}

			if ( 'custom_header_col_width' === $field ) {

				$custom_column_width = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> This width will be adopted by all the columns below this header cell. </div> <div class="uabb-table-description"> <b>Note: </b>Get more information about <a href="https://www.ultimatebeaver.com/docs/how-to-add-table-header/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=table-module" target="_blank" rel="noopener"> <strong> How custom width option works. </strong> </a> </div>', 'uabb' )
				);

				return $custom_column_width;
			}

			if ( 'body_row_span' === $field ) {

				$body_rowspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of rows this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple rows to see the result.  Click <a href="https://www.ultimatebeaver.com/docs/how-to-merge-columns-and-rows-in-table/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=table-module" target="_blank" rel="noopener"> <strong> here</strong> </a> to read article for more. </div>', 'uabb' )
				);

				return $body_rowspan_description;
			}

			if ( 'body_col_span' === $field ) {

				$body_colspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of columns this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple column cells to see the result. Click <a href="https://www.ultimatebeaver.com/docs/how-to-merge-columns-and-rows-in-table/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=table-module" target="_blank" rel="noopener"> <strong> here</strong> </a> to read article for more. </div>', 'uabb' )
				);

				return $body_colspan_description;
			}

			if ( 'file_src' === $field ) {

				$csv_file_issue = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> <b>Note: </b> Facing issue with CSV importer? Please read this article for <a href="https://www.ultimatebeaver.com/docs/facing-issues-with-csv-import/?utm_source=uabb-pro-backend&utm_medium=module-editor-screen&utm_campaign=table-module" target="_blank" rel="noopener"> <strong> troubleshooting steps.</strong> </a> </div>', 'uabb' )
				);

				return $csv_file_issue;
			}
		} else {

			if ( 'head_row_span' === $field ) {

				$heading_rowspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of rows this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple rows to see the result. </div>', 'uabb' )
				);

				return $heading_rowspan_description;
			}

			if ( 'head_col_span' === $field ) {

				$heading_colspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of columns this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple column cells to see the result. </div>', 'uabb' )
				);

				return $heading_colspan_description;
			}

			if ( 'custom_header_col_width' === $field ) {

				$custom_column_width = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> This width will be adopted by all the columns below this header cell. </div>', 'uabb' )
				);

				return $custom_column_width;
			}

			if ( 'body_row_span' === $field ) {

				$body_rowspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of rows this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple rows to see the result. </div>', 'uabb' )
				);

				return $body_rowspan_description;
			}

			if ( 'body_col_span' === $field ) {

				$body_colspan_description = sprintf( /* translators: %s: search term */
					__( '<div class="uabb-table-description"> Specify the number of columns this cell should merge. </div> <div class="uabb-table-description"> <b>Note: </b> Add multiple column cells to see the result. </div>', 'uabb' )
				);

				return $body_colspan_description;
			}

			if ( 'file_src' === $field ) {

				$csv_file_issue = '';

				return $csv_file_issue;
			}
		}

		if ( 'show_sort' === $field ) {

			$sorting_description = sprintf( /* translators: %s: search term */
				__( '<div class="uabb-table-description"> <b>Note: </b>Sorting feature will not work with Rowspan or Colspan structure. It will misalign table layout.</div>', 'uabb' )
			);

			return $sorting_description;
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'UABBTable', array(
		'headrow'        => array(
			'title'    => __( 'Table Header', 'uabb' ),
			'sections' => array(
				'new_feature' => array(
					'title'  => __( 'Table Source', 'uabb' ),
					'fields' => array(
						'table_type' => array(
							'type'    => 'select',
							'label'   => __( 'Select Table Source', 'uabb' ),
							'default' => 'manual',
							'options' => array(
								'manual' => __( 'Manual', 'uabb' ),
								'file'   => __( 'CSV File', 'uabb' ),
							),
							'toggle'  => array(
								'manual' => array(
									'sections' => array( 'general' ),
									'tabs'     => array( 'bodyrows' ),
								),
								'file'   => array(
									'fields' => array( 'file_src' ),
								),
							),
						),
						'file_src'   => array(
							'type'        => 'uabb-file',
							'label'       => __( 'Upload CSV File', 'uabb' ),
							'show_remove' => true,
							'description' => UABBTable::get_description( 'file_src' ),
						),
					),
				),
				'general'     => array(
					'title'  => __( 'Heading Row', 'uabb' ),
					'fields' => array(
						'thead_row' => array(
							'type'         => 'form',
							'label'        => __( 'Item', 'uabb' ),
							'form'         => 'thead_row_form',
							'preview_text' => 'heading',
							'multiple'     => true,
							'default'      => array(
								array(
									'head_action' => 'row',
									'heading'     => 'Name',
								),
								array(
									'head_action' => 'cell',
									'heading'     => 'Designation',
								),
								array(
									'head_action' => 'cell',
									'heading'     => 'Office',
								),
							),
						),
					),
				),
			),
		),
		'bodyrows'       => array(
			'title'    => __( 'Table Content', 'uabb' ),
			'sections' => array(
				'general' => array(
					'title'  => __( 'Body Content', 'uabb' ),
					'fields' => array(
						'tbody_row' => array(
							'type'         => 'form',
							'label'        => __( 'Item', 'uabb' ),
							'form'         => 'tbody_row_form',
							'preview_text' => 'features',
							'multiple'     => true,
							'default'      => array(
								array(
									'action'   => 'row',
									'features' => 'Mr. John Doe',
								),
								array(
									'action'   => 'cell',
									'features' => 'Software Developer',
								),
								array(
									'action'   => 'cell',
									'features' => 'Tokyo',
								),
								array(
									'action'   => 'row',
									'features' => 'Ms. Cara Wagner',
								),
								array(
									'action'   => 'cell',
									'features' => 'Integration Specialist',
								),
								array(
									'action'   => 'cell',
									'features' => 'London',
								),
								array(
									'action'   => 'row',
									'features' => 'Mr. Bruno Stevens',
								),
								array(
									'action'   => 'cell',
									'features' => 'WordPress Developer',
								),
								array(
									'action'   => 'cell',
									'features' => 'New York',
								),
							),
						),
					),
				),
			),
		),
		'extra_features' => array(
			'title'    => __( 'Features', 'uabb' ),
			'sections' => array(
				'enable_sort'   => array(
					'title'  => __( 'Sorting', 'uabb' ),
					'fields' => array(
						'show_sort' => array(
							'type'        => 'select',
							'label'       => __( 'Sortable Table', 'uabb' ),
							'default'     => 'no',
							'help'        => __( 'Sort table entries on the click of table headings.', 'uabb' ),
							'options'     => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'description' => UABBTable::get_description( 'show_sort' ),
						),
					),
				),
				'enable_search' => array(
					'title'  => __( 'Search Field', 'uabb' ),
					'fields' => array(
						'show_search'  => array(
							'type'    => 'select',
							'label'   => __( 'Searchable Table', 'uabb' ),
							'default' => 'no',
							'help'    => __( 'Search table entries easily.', 'uabb' ),
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no'  => array(
									'sections' => array( '' ),
								),
								'yes' => array(
									'fields'   => array( 'search_label' ),
									'sections' => array( 'filter_typography' ),
								),
							),
						),
						'search_label' => array(
							'type'        => 'text',
							'label'       => __( 'Search Placeholder Label', 'uabb' ),
							'placeholder' => __( 'Search...', 'uabb' ),
							'connections' => array( 'string', 'html' ),
						),
					),
				),
				'enable_filter' => array(
					'title'  => __( 'Entries Dropdown', 'uabb' ),
					'fields' => array(
						'show_entries'           => array(
							'type'    => 'select',
							'label'   => __( 'Show Entries Dropdown', 'uabb' ),
							'default' => 'no',
							'help'    => __( 'Controls the number of entries in a table.', 'uabb' ),
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no'  => array(
									'sections' => array( '' ),
								),
								'yes' => array(
									'fields'   => array( 'show_entries_label', 'show_entries_all_label' ),
									'sections' => array( 'filter_typography' ),
								),
							),
						),
						'show_entries_label'     => array(
							'type'        => 'text',
							'label'       => __( 'Show Entries Label', 'uabb' ),
							'placeholder' => __( 'Show Entries', 'uabb' ),
							'connections' => array( 'string', 'html' ),
						),
						'show_entries_all_label' => array(
							'type'        	=> 'text',
							'label'       	=> __( 'All Label', 'uabb' ),
							'default' 		=> __( 'All', 'uabb' ),
							'placeholder' 	=> __( 'All', 'uabb' ),
							'connections' 	=> array( 'string', 'html' ),
						),
					),
				),
			),
		),
		'style'          => array(
			'title'    => __( 'Style', 'uabb' ),
			'sections' => array(
				'spacing'           => array(
					'title'  => __( 'Header Settings', 'uabb' ),
					'fields' => array(
						'row_heading_color'            => array(
							'type'       => 'color',
							'label'      => __( 'Heading Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'color',
								'selector' => '.uabb-table-header .table-header-th, .uabb-table-header .table-header-th .thead-th-context, .table-header-th .th-style',
							),
						),
						'row_heading_background_color' => array(
							'type'       => 'color',
							'label'      => __( 'Heading Background Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'background',
								'selector' => '.uabb-table-header .table-header-th',
							),
						),
						'headings_align'               => array(
							'type'    => 'select',
							'label'   => __( 'Headings Alignment', 'uabb' ),
							'default' => 'center',
							'options' => array(
								'left'   => __( 'Left', 'uabb' ),
								'center' => __( 'Center', 'uabb' ),
								'right'  => __( 'Right', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-table-header .table-header-th',
								'property' => 'text-align',
							),
						),
						'header_cell_padding'          => array(
							'type'        => 'unit',
							'label'       => __( 'Header Cells Padding', 'uabb' ),
							'default'     => '15',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-inner-wrap .uabb-table-header .table-header-th',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'table_data_border_size'       => array(
							'type'        => 'unit',
							'label'       => __( 'Header Cells Border Size', 'uabb' ),
							'placeholder' => '1',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-inner-wrap .uabb-table-header .table-header-th',
								'property' => 'border-width',
								'unit'     => 'px',
							),
						),
						'table_data_border_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Header Cells Border Color', 'uabb' ),
							'default'    => '000000',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-table-inner-wrap .uabb-table-header .table-header-th',
								'property' => 'border-color',
							),
						),
					),
				),
				'body_styles'       => array(
					'title'  => __( 'Body Settings', 'uabb' ),
					'fields' => array(
						'strip_effect'                 => array(
							'type'    => 'select',
							'label'   => __( 'Enable Striped Effect', 'uabb' ),
							'default' => 'no',
							'help'    => __( 'Enable striped effect to highlight odd and even rows with different background colors.', 'uabb' ),
							'options' => array(
								'yes' => __( 'Yes', 'uabb' ),
								'no'  => __( 'No', 'uabb' ),
							),
							'toggle'  => array(
								'no'  => array(
									'fields' => array( 'table_foreground_outside' ),
								),
								'yes' => array(
									'fields' => array( 'odd_properties_bg', 'even_properties_bg' ),
								),
							),
						),
						'table_foreground_outside'     => array(
							'type'       => 'color',
							'label'      => __( 'Rows Background Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'background',
								'selector' => '.uabb-table-features .tbody-row',
							),
						),
						'odd_properties_bg'            => array(
							'type'       => 'color',
							'label'      => __( 'Odd Rows Background Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'background',
								'selector' => '.uabb-table-features .tbody-row:nth-child(odd)',
							),
						),
						'even_properties_bg'           => array(
							'type'       => 'color',
							'label'      => __( 'Even Rows Background Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'background',
								'selector' => '.uabb-table-features .tbody-row:nth-child(even)',
							),
						),
						'features_color'               => array(
							'type'       => 'color',
							'label'      => __( 'Rows Text Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'color',
								'selector' => '.uabb-table-features .table-body-td, .uabb-table-features .table-body-td .td-style',
							),
						),
						'body_rows_text_hover'         => array(
							'type'       => 'color',
							'label'      => __( 'Row Text Hover Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'body_rows_bg_hover'           => array(
							'type'       => 'color',
							'label'      => __( 'Row Background Hover Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'body_cell_text_hover'         => array(
							'type'       => 'color',
							'label'      => __( 'Cell Text Hover Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'body_cell_bg_hover'           => array(
							'type'       => 'color',
							'label'      => __( 'Cell Background Hover Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'features_align'               => array(
							'type'    => 'select',
							'label'   => __( 'Content Alignment', 'uabb' ),
							'default' => 'center',
							'options' => array(
								'left'   => __( 'Left', 'uabb' ),
								'center' => __( 'Center', 'uabb' ),
								'right'  => __( 'Right', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-table-features .table-body-td',
								'property' => 'text-align',
							),
						),
						'body_cell_padding'            => array(
							'type'        => 'unit',
							'label'       => __( 'Body Cells Padding', 'uabb' ),
							'default'     => '15',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-inner-wrap .uabb-table-features .table-body-td',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'table_body_data_border_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Body Cells Border Size', 'uabb' ),
							'placeholder' => '1',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-inner-wrap .uabb-table-features .table-body-td',
								'property' => 'border-width',
								'unit'     => 'px',
							),
						),
						'table_body_data_border_color' => array(
							'type'       => 'color',
							'label'      => __( 'Body Cells Border Color', 'uabb' ),
							'default'    => '000000',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.uabb-table-inner-wrap .uabb-table-features .table-body-td',
								'property' => 'border-color',
							),
						),
					),
				),
				'head_image_styles' => array(
					'title'  => __( 'Header Image Settings', 'uabb' ),
					'fields' => array(
						'head_icons_global_color' => array(
							'type'       => 'color',
							'label'      => __( 'Icons Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'color',
								'selector' => '.uabb-table-header .table-header-th .before-icon, .uabb-table-header .table-header-th .after-icon',
							),
						),
						'head_icons_gloabl_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Icons Size', 'uabb' ),
							'default'     => '15',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-header .table-header-th .before-icon, .uabb-table-header .table-header-th .after-icon',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'head_image_gloabl_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Image Size', 'uabb' ),
							'default'     => '50',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-header .table-header-th .thead-img',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
					),
				),
				'body_image_styles' => array(
					'title'  => __( 'Body Image Settings', 'uabb' ),
					'fields' => array(
						'body_icons_global_color' => array(
							'type'       => 'color',
							'label'      => __( 'Icons Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'color',
								'selector' => '.uabb-table-features .table-body-td .before-icon, .uabb-table-features .table-body-td .after-icon',
							),
						),
						'body_icons_gloabl_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Icons Size', 'uabb' ),
							'default'     => '15',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-features .table-body-td .before-icon, .uabb-table-features .table-body-td .after-icon',
								'property' => 'font-size',
								'unit'     => 'px',
							),
						),
						'body_image_gloabl_size'  => array(
							'type'        => 'unit',
							'label'       => __( 'Image Size', 'uabb' ),
							'default'     => '50',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-features .table-body-td .body-content-img',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
					),
				),
				'features_styles'   => array(
					'title'  => __( 'Entries Dropdown & Search Field', 'uabb' ),
					'fields' => array(
						'entries_label_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Label Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'color',
								'selector' => '.entries-wrapper .lbl-entries',
							),
						),
						'entries_input_color'      => array(
							'type'       => 'color',
							'label'      => __( 'Input Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'color',
								'selector' => '.select-filter, .search-wrapper .search-input::placeholder',
							),
						),
						'entries_background_color' => array(
							'type'       => 'color',
							'label'      => __( 'Input Background Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'property' => 'background',
								'selector' => '.select-filter, .search-input',
							),
						),
						'entries_border_size'      => array(
							'type'        => 'unit',
							'label'       => __( 'Border Size', 'uabb' ),
							'placeholder' => '1',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.select-filter, .search-input',
								'property' => 'border-width',
								'unit'     => 'px',
							),
						),
						'entries_border_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Border Color', 'uabb' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.select-filter, .search-input',
								'property' => 'border-color',
							),
						),
						'entries_input_padding'    => array(
							'type'        => 'unit',
							'label'       => __( 'Input Padding', 'uabb' ),
							'default'     => '10',
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.select-filter, .search-input',
								'property' => 'padding',
								'unit'     => 'px',
							),
						),
						'entries_input_size'       => array(
							'type'        => 'unit',
							'label'       => __( 'Input Size', 'uabb' ),
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.select-filter, .search-input',
								'property' => 'width',
								'unit'     => 'px',
							),
						),
						'entries_bottom_space'     => array(
							'type'        => 'unit',
							'label'       => __( 'Bottom Space', 'uabb' ),
							'size'        => '8',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.table-data',
								'property' => 'margin-bottom',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
		'all_typography' => array(
			'title'    => __( 'Typography', 'uabb' ),
			'sections' => array(
				'heading_typography' => array(
					'title'  => __( 'Headings', 'uabb' ),
					'fields' => array(
						'heading_typography_font_family' => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-table-wrapper .table-header-th',
							),
						),
						'heading_typography_font_size_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-wrapper .table-header-th',
								'property' => 'font-size',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'heading_typography_line_height_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Line Height', 'uabb' ),
							'description' => 'em',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-wrapper .table-header-th',
								'property' => 'line-height',
								'unit'     => 'em',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'table_headings_typography_transform' => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-table-header .table-header-th',
								'property' => 'text-transform',
							),
						),
						'table_headings_letter_spacing'  => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'placeholder' => '0',
							'size'        => '5',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-header .table-header-th',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
					),
				),
				'content_typography' => array(
					'title'  => __( 'Content', 'uabb' ),
					'fields' => array(
						'content_typography_font_family'  => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.uabb-table-wrapper .table-body-td',
							),
						),
						'content_typography_font_size_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-wrapper .table-body-td',
								'property' => 'font-size',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'content_typography_line_height_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Line Height', 'uabb' ),
							'description' => 'em',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-wrapper .table-body-td',
								'property' => 'line-height',
								'unit'     => 'em',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'table_rows_typography_transform' => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.uabb-table-features .table-body-td',
								'property' => 'text-transform',
							),
						),
						'table_rows_letter_spacing'       => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'placeholder' => '0',
							'size'        => '5',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.uabb-table-features .table-body-td',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
					),
				),
				'filter_typography'  => array(
					'title'  => __( 'Entries Dropdown & Search Field', 'uabb' ),
					'fields' => array(
						'filter_typography_font_family'    => array(
							'type'    => 'font',
							'label'   => __( 'Font Family', 'uabb' ),
							'default' => array(
								'family' => 'Default',
								'weight' => 'Default',
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.entries-wrapper .lbl-entries, .entries-wrapper .select-filter, .search-input',
							),
						),
						'filter_typography_font_size_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Font Size', 'uabb' ),
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.entries-wrapper .lbl-entries, .entries-wrapper .select-filter, .search-input',
								'property' => 'font-size',
								'unit'     => 'px',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'filter_typography_line_height_unit' => array(
							'type'        => 'unit',
							'label'       => __( 'Line Height', 'uabb' ),
							'description' => 'em',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.entries-wrapper .lbl-entries, .entries-wrapper .select-filter, .search-input',
								'property' => 'line-height',
								'unit'     => 'em',
							),
							'responsive'  => array(
								'placeholder' => array(
									'default'    => '',
									'medium'     => '',
									'responsive' => '',
								),
							),
						),
						'table_filters_typography_transform' => array(
							'type'    => 'select',
							'label'   => __( 'Transform', 'uabb' ),
							'default' => '',
							'options' => array(
								''           => __( 'Default', 'uabb' ),
								'uppercase'  => __( 'UPPERCASE', 'uabb' ),
								'lowercase'  => __( 'lowercase', 'uabb' ),
								'capitalize' => __( 'Capitalize', 'uabb' ),
							),
							'preview' => array(
								'type'     => 'css',
								'selector' => '.entries-wrapper .lbl-entries, .entries-wrapper .select-filter, .search-input',
								'property' => 'text-transform',
							),
						),
						'table_filters_letter_spacing'     => array(
							'type'        => 'unit',
							'label'       => __( 'Letter Spacing', 'uabb' ),
							'placeholder' => '0',
							'size'        => '5',
							'description' => 'px',
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.entries-wrapper .lbl-entries, .entries-wrapper .select-filter, .search-input',
								'property' => 'letter-spacing',
								'unit'     => 'px',
							),
						),
					),
				),
			),
		),
	)
);

FLBuilder::register_settings_form(
	'thead_row_form', array(
		'title' => __( 'Heading Cell', 'uabb' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'uabb' ),
				'sections' => array(
					'title'              => array(
						'title'  => __( 'Edit Heading Cell', 'uabb' ),
						'fields' => array(
							'head_action'       => array(
								'type'    => 'select',
								'label'   => __( 'Choose Action', 'uabb' ),
								'default' => 'row',
								'options' => array(
									'row'  => __( 'New Row', 'uabb' ),
									'cell' => __( 'New Cell', 'uabb' ),
								),
							),
							'heading'           => array(
								'type'        => 'text',
								'label'       => __( 'Content', 'uabb' ),
								'connections' => array( 'string', 'html' ),
							),
							'head_advanced_opt' => array(
								'type'    => 'select',
								'label'   => __( 'Advanced Options', 'uabb' ),
								'default' => 'no',
								'help'    => __( 'Enable the Advanced options for additional styling, image / icon options, column and row spans etc.', 'uabb' ),
								'options' => array(
									'yes' => __( 'Yes', 'uabb' ),
									'no'  => __( 'No', 'uabb' ),
								),
								'toggle'  => array(
									'yes' => array(
										'sections' => array( 'head_color_setting', 'col_span_setting', 'row_span_setting', 'head_icon_basic', 'head_link_basic', 'custom_col_width' ),
									),
								),
							),
						),
					),
					'head_color_setting' => array(
						'title'  => __( 'Styling', 'uabb' ),
						'fields' => array(
							'head_text_color' => array(
								'type'       => 'color',
								'label'      => __( 'Text Color', 'uabb' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'head_bg_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Text Background Color', 'uabb' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
					'row_span_setting'   => array(
						'title'  => __( 'Row Span', 'uabb' ),
						'fields' => array(
							'head_row_span' => array(
								'type'        => 'unit',
								'label'       => __( 'Rowspan', 'uabb' ),
								'placeholder' => '1',
								'description' => UABBTable::get_description( 'head_row_span' ),
							),
						),
					),
					'col_span_setting'   => array(
						'title'  => __( 'Column Span', 'uabb' ),
						'fields' => array(
							'head_col_span' => array(
								'type'        => 'unit',
								'label'       => __( 'Colspan', 'uabb' ),
								'placeholder' => '1',
								'description' => UABBTable::get_description( 'head_col_span' ),
							),
						),
					),
					'custom_col_width'   => array(
						'title'  => __( 'Custom Column Width', 'uabb' ),
						'fields' => array(
							'custom_header_col_width' => array(
								'type'        => 'unit',
								'label'       => __( 'Enter Width (in px)', 'uabb' ),
								'size'        => '8',
								'description' => UABBTable::get_description( 'custom_header_col_width' ),
							),
						),
					),
					'head_icon_basic'    => array(
						'title'  => __( 'Icon / Image', 'uabb' ),
						'fields' => array(
							'head_icon_type'       => array(
								'type'    => 'select',
								'label'   => __( 'Image Type', 'uabb' ),
								'default' => 'none',
								'options' => array(
									'none'  => __( 'None', 'uabb' ),
									'icon'  => __( 'Icon', 'uabb' ),
									'photo' => __( 'Photo', 'uabb' ),
								),
								'toggle'  => array(
									'icon'  => array(
										'fields' => array( 'head_icon', 'head_icon_img_width', 'head_icon_color', 'head_icon_position' ),
									),
									'photo' => array(
										'fields' => array( 'head_photo', 'head_photo_img_width', 'head_icon_position' ),
									),
								),
							),
							'head_icon'            => array(
								'type'        => 'icon',
								'label'       => __( 'Icon', 'uabb' ),
								'show_remove' => true,
							),
							'head_icon_img_width'  => array(
								'type'        => 'unit',
								'label'       => __( 'Icon Size', 'uabb' ),
								'placeholder' => '15',
								'description' => 'px',
								'size'        => '8',
							),
							'head_icon_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Icon Color', 'uabb' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'head_photo'           => array(
								'type'        => 'photo',
								'label'       => __( 'Photo', 'uabb' ),
								'show_remove' => true,
							),
							'head_photo_img_width' => array(
								'type'        => 'unit',
								'label'       => __( 'Photo Width', 'uabb' ),
								'placeholder' => '50',
								'description' => 'px',
								'size'        => '8',
							),
							'head_icon_position'   => array(
								'type'    => 'select',
								'label'   => __( 'Image Position', 'uabb' ),
								'default' => 'before',
								'options' => array(
									'before' => __( 'Before Text', 'uabb' ),
									'after'  => __( 'After Text', 'uabb' ),
								),
							),
						),
					),
					'head_link_basic'    => array(
						'title'  => __( 'Link', 'uabb' ),
						'fields' => array(
							'head_link'          => array(
								'type'        => 'link',
								'label'       => __( 'Link', 'uabb' ),
								'placeholder' => 'https://www.example.com',
								'preview'     => array(
									'type' => 'none',
								),
								'connections' => array( 'url' ),
							),
							'head_link_target'   => array(
								'type'    => 'select',
								'label'   => __( 'Link Target', 'uabb' ),
								'default' => '_self',
								'options' => array(
									'_self'  => __( 'Same Window', 'uabb' ),
									'_blank' => __( 'New Window', 'uabb' ),
								),
								'preview' => array(
									'type' => 'none',
								),
							),
							'head_link_nofollow' => array(
								'type'        => 'select',
								'label'       => __( 'Link Nofollow', 'uabb' ),
								'description' => '',
								'default'     => '0',
								'help'        => __( 'Enable this to make this link nofollow.', 'uabb' ),
								'options'     => array(
									'1' => __( 'Yes', 'uabb' ),
									'0' => __( 'No', 'uabb' ),
								),
							),
						),
					),
				),
			),
		),
	)
);

FLBuilder::register_settings_form(
	'tbody_row_form', array(
		'title' => __( 'Body Row / Cell', 'uabb' ),
		'tabs'  => array(
			'general' => array(
				'title'    => __( 'General', 'uabb' ),
				'sections' => array(
					'features'              => array(
						'title'  => __( 'Edit Body Row / Cell', 'uabb' ),
						'fields' => array(
							'action'            => array(
								'type'    => 'select',
								'label'   => __( 'Choose Action', 'uabb' ),
								'default' => 'row',
								'options' => array(
									'row'  => __( 'New Row', 'uabb' ),
									'cell' => __( 'New Cell', 'uabb' ),
								),
							),
							'features'          => array(
								'type'        => 'text',
								'label'       => __( 'Content', 'uabb' ),
								'connections' => array( 'string', 'html' ),
							),
							'body_advanced_opt' => array(
								'type'    => 'select',
								'label'   => __( 'Advanced Options', 'uabb' ),
								'default' => 'no',
								'help'    => __( 'Enable the Advanced options for additional styling, image / icon options, column and row spans etc.', 'uabb' ),
								'options' => array(
									'yes' => __( 'Yes', 'uabb' ),
									'no'  => __( 'No', 'uabb' ),
								),
								'toggle'  => array(
									'yes' => array(
										'sections' => array( 'body_color_setting', 'body_row_span_setting', 'body_col_span_setting', 'body_icon_basic', 'body_link_basic' ),
									),
									'no'  => array(),
								),
							),
						),
					),
					'body_color_setting'    => array(
						'title'  => __( 'Styling', 'uabb' ),
						'fields' => array(
							'body_text_color' => array(
								'type'       => 'color',
								'label'      => __( 'Text Color', 'uabb' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'body_bg_color'   => array(
								'type'       => 'color',
								'label'      => __( 'Text Background Color', 'uabb' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
						),
					),
					'body_row_span_setting' => array(
						'title'  => __( 'Row Span', 'uabb' ),
						'fields' => array(
							'body_row_span' => array(
								'type'        => 'unit',
								'label'       => __( 'Rowspan', 'uabb' ),
								'placeholder' => '1',
								'description' => UABBTable::get_description( 'body_row_span' ),
							),
						),
					),
					'body_col_span_setting' => array(
						'title'  => __( 'Column Span', 'uabb' ),
						'fields' => array(
							'body_col_span' => array(
								'type'        => 'unit',
								'label'       => __( 'Colspan', 'uabb' ),
								'placeholder' => '1',
								'description' => UABBTable::get_description( 'body_col_span' ),
							),
						),
					),
					'body_icon_basic'       => array(
						'title'  => __( 'Icon / Image', 'uabb' ),
						'fields' => array(
							'body_icon_type'       => array(
								'type'    => 'select',
								'label'   => __( 'Image Type', 'uabb' ),
								'default' => 'none',
								'options' => array(
									'none'  => __( 'None', 'uabb' ),
									'icon'  => __( 'Icon', 'uabb' ),
									'photo' => __( 'Photo', 'uabb' ),
								),
								'toggle'  => array(
									'icon'  => array(
										'fields' => array( 'body_icon', 'body_icon_img_width', 'body_icon_color', 'body_icon_position' ),
									),
									'photo' => array(
										'fields' => array( 'body_photo', 'body_photo_img_width', 'body_icon_position' ),
									),
								),
							),
							'body_icon'            => array(
								'type'        => 'icon',
								'label'       => __( 'Icon', 'uabb' ),
								'show_remove' => true,
							),
							'body_icon_img_width'  => array(
								'type'        => 'unit',
								'label'       => __( 'Icon Size', 'uabb' ),
								'placeholder' => '15',
								'description' => 'px',
								'size'        => '8',
							),
							'body_icon_color'      => array(
								'type'       => 'color',
								'label'      => __( 'Icon Color', 'uabb' ),
								'show_reset' => true,
								'show_alpha' => true,
							),
							'body_photo'           => array(
								'type'        => 'photo',
								'label'       => __( 'Photo', 'uabb' ),
								'show_remove' => true,
							),
							'body_photo_img_width' => array(
								'type'        => 'unit',
								'label'       => __( 'Photo Width', 'uabb' ),
								'placeholder' => '50',
								'description' => 'px',
								'size'        => '8',
							),
							'body_icon_position'   => array(
								'type'    => 'select',
								'label'   => __( 'Image Position', 'uabb' ),
								'default' => 'before',
								'options' => array(
									'before' => __( 'Before Text', 'uabb' ),
									'after'  => __( 'After Text', 'uabb' ),
								),
							),
						),
					),
					'body_link_basic'       => array(
						'title'  => __( 'Link', 'uabb' ),
						'fields' => array(
							'body_link'          => array(
								'type'        => 'link',
								'label'       => __( 'Link', 'uabb' ),
								'placeholder' => 'https://www.example.com',
								'preview'     => array(
									'type' => 'none',
								),
								'connections' => array( 'url' ),
							),
							'body_link_target'   => array(
								'type'    => 'select',
								'label'   => __( 'Link Target', 'uabb' ),
								'default' => '_self',
								'options' => array(
									'_self'  => __( 'Same Window', 'uabb' ),
									'_blank' => __( 'New Window', 'uabb' ),
								),
								'preview' => array(
									'type' => 'none',
								),
							),
							'body_link_nofollow' => array(
								'type'        => 'select',
								'label'       => __( 'Link Nofollow', 'uabb' ),
								'description' => '',
								'default'     => '0',
								'help'        => __( 'Enable this to make this link nofollow.', 'uabb' ),
								'options'     => array(
									'1' => __( 'Yes', 'uabb' ),
									'0' => __( 'No', 'uabb' ),
								),
							),
						),
					),
				),
			),
		),
	)
);
