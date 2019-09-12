<?php
/**
 * PowerPack admin settings extensions tab.
 *
 * @since 1.0.0
 * @package bb-powerpack
 */

?>

<?php
$extensions         = pp_extensions();
$enabled_extensions = self::get_enabled_extensions();
?>

<table class="form-table">
	<tbody>
		<?php if ( ! class_exists( 'FLBuilderUIContentPanel' ) ) { ?>
		<tr valign="top">
			<th scope="row" valign="top">
				<?php esc_html_e('Quick Preview', 'bb-powerpack'); ?>
			</th>
			<td>
				<p>
					<label>
						<input type="checkbox" name="bb_powerpack_quick_preview" value="1" <?php echo ( $quick_preview == 1 ) ? 'checked="checked"' : ''; ?> />
						<?php esc_html_e('Enable Quick Preview', 'bb-powerpack'); ?>
					</label>
				</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" valign="top">
				<?php esc_html_e('Search Box', 'bb-powerpack'); ?>
			</th>
			<td>
				<p>
					<label>
						<input type="checkbox" name="bb_powerpack_search_box" value="1" <?php echo ( $search_box == 1 ) ? 'checked="checked"' : ''; ?> />
						<?php esc_html_e('Enable Search Box in panel', 'bb-powerpack'); ?>
					</label>
				</p>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<h3><?php esc_html_e( 'Row Extensions', 'bb-powerpack' ); ?></h3>
<table class="form-table pp-flex-table">
	<?php foreach ( $extensions['row'] as $key => $extension ) { ?>
		<tr valign="top">
			<th scope="row" valign="top">
				<label for="pp_extension_row_<?php echo $key; ?>"><?php echo $extension['label']; ?></label>
				<p class="description"><?php echo $extension['description']; ?></p>
			</th>
			<td>
				<?php
				$is_enabled = ( array_key_exists( $key, $enabled_extensions['row'] ) || in_array( $key, $enabled_extensions['row'] ) ) ? true : false;
				?>
				<label class="pp-admin-field-toggle">
					<input id="pp_extension_row_<?php echo $key; ?>" name="bb_powerpack_extensions[row][]" type="checkbox" value="<?php echo $key; ?>"<?php echo $is_enabled ? ' checked="checked"' : '' ?> />
					<span class="pp-admin-field-toggle-slider"></span>
				</label>
			</td>
		</tr>
	<?php } ?>
</table>

<h3><?php esc_html_e( 'Column Extensions', 'bb-powerpack' ); ?></h3>
<table class="form-table pp-flex-table">
	<?php foreach ( $extensions['col'] as $key => $extension ) { ?>
		<tr valign="top">
			<th scope="row" valign="top">
				<label for="pp_extension_col_<?php echo $key; ?>"><?php echo $extension['label']; ?></label>
				<p class="description"><?php echo $extension['description']; ?></p>
			</th>
			<td>
				<?php
				$is_enabled = ( array_key_exists( $key, $enabled_extensions['col'] ) || in_array( $key, $enabled_extensions['col'] ) ) ? true : false;
				?>
				<label class="pp-admin-field-toggle">
					<input id="pp_extension_col_<?php echo $key; ?>" name="bb_powerpack_extensions[col][]" type="checkbox" value="<?php echo $key; ?>"<?php echo $is_enabled ? ' checked="checked"' : '' ?> />
					<span class="pp-admin-field-toggle-slider"></span>
				</label>
			</td>
		</tr>
	<?php } ?>
</table>

<?php submit_button(); ?>
<?php wp_nonce_field('pp-extensions', 'pp-extensions-nonce'); ?>
<input type="hidden" name="bb_powerpack_override_ms" value="1" />
