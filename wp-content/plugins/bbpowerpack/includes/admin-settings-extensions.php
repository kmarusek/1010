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

<?php if ( ! class_exists( 'FLBuilderUIContentPanel' ) ) { ?>
<table class="form-table">
	<tbody>
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
	</tbody>
</table>
<?php } ?>

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
					<span class="pp-admin-field-toggle-slider" aria-hidden="true"></span>
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

<hr>
<h3><?php _e('Taxonomy Thumbnail', 'bb-powerpack'); ?></h3>
<p><?php echo __( 'Add Image Thumbnail option to Taxonomies.', 'bb-powerpack' ); ?></p>

<table class="form-table">
	<tr align="top">
		<th scope="row" valign="top">
			<label for="bb_powerpack_taxonomy_thumbnail_enable"><?php esc_html_e('Enable Taxonomy Thumbnail', 'bb-powerpack'); ?></label>
		</th>
		<td>

			<select id="bb_powerpack_taxonomy_thumbnail_enable" name="bb_powerpack_taxonomy_thumbnail_enable" style="min-width: 200px;">
				<?php $selected = BB_PowerPack_Admin_Settings::get_option('bb_powerpack_taxonomy_thumbnail_enable', true); ?>
				<option value="enabled" <?php selected( $selected, 'enabled' ); ?>><?php _e('Enabled', 'bb-powerpack'); ?></option>
				<option value="disabled" <?php selected( $selected, 'disabled' ); ?>><?php _e('Disabled', 'bb-powerpack'); ?></option>
			</select>
		</td>
	</tr>
	<tr align="top">
		<th scope="row" valign="top">
			<label for="bb_powerpack_taxonomy_thumbnail_taxonomies"><?php esc_html_e('Select Taxonomies', 'bb-powerpack'); ?></label>
		</th>
		<td>
			<?php
			BB_PowerPack_Taxonomy_Thumbnail::get_taxonomies_checklist();
			?>
		</td>
	</tr>
</table>

<hr>
<h3><?php _e('Author Social Profile URLs', 'bb-powerpack'); ?></h3>
<table class="form-table">
	<tr align="top">
		<th scope="row" valign="top">
			<label for="bb_powerpack_user_social_profile_urls"><?php esc_html_e('Enable Social Profile URLs', 'bb-powerpack'); ?></label>
			<p class="description"><?php echo __( 'Enabling this option will add controls to add social media URLs under the user profile.', 'bb-powerpack' ); ?></p>
		</th>
		<td>
			<label class="pp-admin-field-toggle">
				<input id="bb_powerpack_user_social_profile_urls" name="bb_powerpack_user_social_profile_urls" type="checkbox" value="yes"<?php echo 'yes' === get_option( 'bb_powerpack_user_social_profile_urls' ) ? ' checked="checked"' : '' ?> />
				<span class="pp-admin-field-toggle-slider"></span>
			</label>
		</td>
	</tr>
</table>

<hr>
<h3><?php _e('Toggle Lazy Load', 'bb-powerpack'); ?></h3>
<table class="form-table">
	<tr align="top">
		<th scope="row" valign="top">
			<label for="bb_powerpack_disable_wp_lazyload"><?php esc_html_e('Disable Lazy Load', 'bb-powerpack'); ?></label>
			<p class="description"><?php _e( 'If you find some modules with images behaving weird then it maybe a lazyload issue of browser. You can disable the lazyload from here.', 'bb-powerpack' ); ?></p>
		</th>
		<td>
			<label class="pp-admin-field-toggle">
				<input id="bb_powerpack_disable_wp_lazyload" name="bb_powerpack_disable_wp_lazyload" type="checkbox" value="yes"<?php echo 'yes' === get_option( 'bb_powerpack_disable_wp_lazyload' ) ? ' checked="checked"' : '' ?> />
				<span class="pp-admin-field-toggle-slider"></span>
			</label>
		</td>
	</tr>
</table>

<?php submit_button(); ?>
<?php wp_nonce_field('pp-extensions', 'pp-extensions-nonce'); ?>
<input type="hidden" name="bb_powerpack_override_ms" value="1" />