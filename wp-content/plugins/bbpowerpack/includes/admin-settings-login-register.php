<h3><?php _e('Login / Register Pages Setup', 'bb-powerpack'); ?></h3>
<?php if ( ! is_network_admin() && is_multisite() ) : ?>

<div class="notice notice-info">
	<p><?php _e( '<b>NOTE:</b> By changing setting here will override the network settings.', 'bb-powerpack' ); ?></p>
</div>

<?php endif; ?>

<table class="form-table maintenance-mode-config">
	<tr align="top">
		<th scope="row" valign="top">
			<label for="bb_powerpack_login_page"><?php esc_html_e('Login page', 'bb-powerpack'); ?></label>
		</th>
		<td>
			<?php $selected = BB_PowerPack_Admin_Settings::get_option('bb_powerpack_login_page', true); ?>
			<select id="bb_powerpack_login_page" name="bb_powerpack_login_page" style="min-width: 200px;">
				<?php echo BB_PowerPack_Login_Register::get_pages( $selected ); ?>
			</select>
			<p class="description"><?php _e( 'It will replace native login page with the custom one. Please make sure that you have login form on the selected page.', 'bb-powerpack' ); ?></p>
		</td>
	</tr>
	<tr align="top">
		<th scope="row" valign="top">
			<label for="bb_powerpack_register_page"><?php esc_html_e('Register page', 'bb-powerpack'); ?></label>
		</th>
		<td>
			<?php $selected = BB_PowerPack_Admin_Settings::get_option('bb_powerpack_register_page', true); ?>
			<select id="bb_powerpack_register_page" name="bb_powerpack_register_page" style="min-width: 200px;">
				<?php echo BB_PowerPack_Login_Register::get_pages( $selected ); ?>
			</select>
			<p class="description"><?php _e( 'It will replace native registration page with the custom one. Please make sure that you have registration form on the selected page.', 'bb-powerpack' ); ?></p>
		</td>
	</tr>
</table>

<?php submit_button(); ?>